"""
excel_to_sql_roaming.py

Transforme le fichier Excel "YAS TOGO ROAMING OPENED PER SERVICE"
(tableau a en-tetes fusionnees sur 2 lignes) en une ligne par operateur,
avec une colonne 0/1 par service et direction.

Installation :
    Aucune dependance externe (bibliotheques standard Python uniquement)

Usage :
    python excel_to_sql_roaming.py "resources/views/ouvertures_roaming.xlsx"

Si le resultat est decale (colonnes ou lignes qui ne correspondent pas),
ajuste les 3 constantes ci-dessous (indices 0-based) selon la position
reelle des lignes dans TON fichier Excel, puis relance.
"""

import argparse
import csv
import posixpath
import sys
import xml.etree.ElementTree as ET
import zipfile
from pathlib import Path

# --- A ajuster selon la position reelle dans le fichier Excel (0-based) ---
GROUP_HEADER_ROW = 3   # ligne "POSTPAID / CAMEL / DATA 3G / ... / K2R / VRS"
SUB_HEADER_ROW = 4     # ligne "Inbound / Outbound / ... "
DATA_START_ROW = 5     # premiere ligne de donnees (N, PAYS, CODE, OPERATEUR, ...)

FIRST_DATA_COL = 4     # 0-based : 0=N, 1=PAYS, 2=CODE, 3=OPERATEUR, 4=premiere colonne de service


def parse_args():
    p = argparse.ArgumentParser(description=__doc__)
    p.add_argument("excel_path", help="Chemin du fichier Excel source")
    p.add_argument("--sheet", default=0, help="Nom ou index de la feuille (defaut: premiere feuille)")
    p.add_argument("--out-csv", default="resources/views/ouvertures_roaming.csv")
    p.add_argument("--out-sql", default="resources/views/ouvertures_roaming.sql")
    p.add_argument("--table", default="roaming_openings")
    return p.parse_args()


def sql_str(value: str) -> str:
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"


def column_index(cell_reference: str) -> int:
    result = 0
    for character in cell_reference:
        if not character.isalpha():
            break
        result = result * 26 + ord(character.upper()) - ord("A") + 1
    return result - 1


def read_excel_rows(excel_path: str, sheet_name):
    namespaces = {
        "main": "http://schemas.openxmlformats.org/spreadsheetml/2006/main",
        "office_rel": "http://schemas.openxmlformats.org/officeDocument/2006/relationships",
        "package_rel": "http://schemas.openxmlformats.org/package/2006/relationships",
    }

    with zipfile.ZipFile(excel_path) as workbook_file:
        workbook = ET.fromstring(workbook_file.read("xl/workbook.xml"))
        sheets = workbook.findall("main:sheets/main:sheet", namespaces)
        if isinstance(sheet_name, int) or str(sheet_name).isdigit():
            sheet = sheets[int(sheet_name)]
        else:
            sheet = next((item for item in sheets if item.attrib["name"] == sheet_name), None)
            if sheet is None:
                raise ValueError(f"Feuille Excel introuvable : {sheet_name}")

        relationships = ET.fromstring(workbook_file.read("xl/_rels/workbook.xml.rels"))
        relationship_id = sheet.attrib[f"{{{namespaces['office_rel']}}}id"]
        target = next(
            item.attrib["Target"]
            for item in relationships.findall("package_rel:Relationship", namespaces)
            if item.attrib["Id"] == relationship_id
        )
        sheet_path = target.lstrip("/")
        if not sheet_path.startswith("xl/"):
            sheet_path = posixpath.normpath(posixpath.join("xl", sheet_path))

        shared_strings = []
        if "xl/sharedStrings.xml" in workbook_file.namelist():
            shared_root = ET.fromstring(workbook_file.read("xl/sharedStrings.xml"))
            shared_strings = [
                "".join(item.itertext())
                for item in shared_root.findall("main:si", namespaces)
            ]

        sheet_root = ET.fromstring(workbook_file.read(sheet_path))
        sheet_rows = sheet_root.findall("main:sheetData/main:row", namespaces)
        max_row = max((int(row.attrib["r"]) for row in sheet_rows), default=0)
        rows = [[] for _ in range(max_row)]

        for row_element in sheet_rows:
            row_index = int(row_element.attrib["r"]) - 1
            for cell in row_element.findall("main:c", namespaces):
                cell_index = column_index(cell.attrib["r"])
                while len(rows[row_index]) <= cell_index:
                    rows[row_index].append("")

                cell_type = cell.attrib.get("t")
                if cell_type == "inlineStr":
                    value = "".join(cell.find("main:is", namespaces).itertext())
                else:
                    value_element = cell.find("main:v", namespaces)
                    value = "" if value_element is None else value_element.text or ""
                    if cell_type == "s" and value:
                        value = shared_strings[int(value)]
                rows[row_index][cell_index] = value

    return rows


TARGET_SERVICES = [
    ("postpaid_inbound", "POSTPAID", "inbound"),
    ("postpaid_outbound", "POSTPAID", "outbound"),
    ("camel_inbound", "CAMEL", "inbound"),
    ("camel_outbound", "CAMEL", "outbound"),
    ("data_3g_inbound", "3G", "inbound"),
    ("data_3g_outbound", "3G", "outbound"),
    ("data_4g_inbound", "4G", "inbound"),
    ("data_4g_outbound", "4G", "outbound"),
    ("data_5g_nsa_inbound", "5G", "inbound"),
    ("data_5g_nsa_outbound", "5G", "outbound"),
    ("k2r", "K2R", None),
    ("vrs", "VRS", None),
]


def match_target_key(service: str, direction: str | None) -> str | None:
    normalized = service.upper().replace(" ", "")
    for key, keyword, expected_direction in TARGET_SERVICES:
        if keyword in normalized and expected_direction == direction:
            return key
    return None


def main():
    args = parse_args()

    rows = read_excel_rows(args.excel_path, args.sheet)
    group_row = list(rows[GROUP_HEADER_ROW])
    sub_row = rows[SUB_HEADER_ROW]
    last_group = ""
    for col_idx, value in enumerate(group_row):
        if value.strip():
            last_group = value.strip()
        else:
            group_row[col_idx] = last_group

    # Construit la correspondance entre les colonnes Excel et les services cibles.
    service_columns = {}
    for col_idx in range(FIRST_DATA_COL, max(len(group_row), len(sub_row))):
        sub_label = sub_row[col_idx] if col_idx < len(sub_row) else ""
        if not sub_label.strip():
            continue  # colonne vide = simple espaceur entre groupes fusionnes
        sub_label = sub_label.strip()
        if sub_label in ("Inbound", "Outbound"):
            service = group_row[col_idx].strip() if col_idx < len(group_row) else ""
            direction = sub_label.lower()
        else:
            # Colonnes simples type K2R / VRS : pas de In/Out
            service = sub_label
            direction = None
        key = match_target_key(service, direction)
        if key:
            service_columns[col_idx] = key
        else:
            label = f"{service} ({direction})" if direction else service
            print(f"Avertissement : colonne {col_idx} ({label}) non reconnue, ignoree.")

    if not service_columns:
        sys.exit(
            "Aucune colonne de service detectee : verifie GROUP_HEADER_ROW / SUB_HEADER_ROW "
            "en ouvrant le fichier et en comptant les lignes depuis 0."
        )

    detected_keys = set(service_columns.values())
    missing = [key for key, _, _ in TARGET_SERVICES if key not in detected_keys]
    if missing:
        print("Avertissement : colonnes cibles non trouvees : " + ", ".join(missing))

    rows_out = []
    current_country = None

    for row_idx in range(DATA_START_ROW, len(rows)):
        row = rows[row_idx]

        pays_cell = row[1] if len(row) > 1 else ""
        if pays_cell.strip():
            current_country = pays_cell.strip()

        code = row[2].strip() if len(row) > 2 else ""
        operateur = row[3].strip() if len(row) > 3 else ""
        if not code and not operateur:
            continue  # ligne totalement vide (separateur / fin de tableau)

        record = {
            "numero": row[0].strip() if row else "",
            "country": current_country or "",
            "code": code,
            "operator": operateur,
        }
        for key, _, _ in TARGET_SERVICES:
            record[key] = 0

        for col_idx, key in service_columns.items():
            value = row[col_idx] if col_idx < len(row) else ""
            if value.strip().upper() in ("OK", "1", "OUI", "YES", "TRUE"):
                record[key] = 1

        rows_out.append(record)

    if not rows_out:
        sys.exit(
            "Aucune ligne de donnees trouvee : verifie DATA_START_ROW et la detection des colonnes "
            "(regarde le detail affiche ci-dessus)."
        )

    fieldnames = ["numero", "country", "code", "operator"] + [key for key, _, _ in TARGET_SERVICES]

    # --- CSV (pour LOAD DATA INFILE ou import Laravel) ---
    out_csv = Path(args.out_csv)
    with out_csv.open("w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows_out)

    # --- SQL (INSERT prets a l'emploi, par lots de 500) ---
    out_sql = Path(args.out_sql)
    with out_sql.open("w", encoding="utf-8") as f:
        f.write(f"-- Genere automatiquement depuis {args.excel_path}\n")
        f.write(f"-- {len(rows_out)} lignes, une par operateur\n")
        f.write("-- Remplace integralement la table dans une transaction.\n\n")
        f.write("START TRANSACTION;\n")
        f.write(f"DELETE FROM {args.table};\n\n")
        batch = []
        columns = ["numero", "country", "code", "operator"] + [key for key, _, _ in TARGET_SERVICES]
        for r in rows_out:
            values = "({}, {}, {}, {}, {})".format(
                "NULL" if not r["numero"] else str(int(float(r["numero"]))),
                sql_str(r["country"]),
                sql_str(r["code"]),
                sql_str(r["operator"]),
                ", ".join(str(r[key]) for key, _, _ in TARGET_SERVICES),
            )
            batch.append(values)
            if len(batch) == 500:
                f.write(
                    f"INSERT INTO {args.table} ({', '.join(columns)}) VALUES\n"
                    + ",\n".join(batch) + ";\n\n"
                )
                batch = []
        if batch:
            f.write(
                f"INSERT INTO {args.table} ({', '.join(columns)}) VALUES\n"
                + ",\n".join(batch) + ";\n\n"
            )
        f.write("COMMIT;\n")

    print(f"\n{len(rows_out)} lignes extraites.")
    print(f"CSV  -> {out_csv.resolve()}")
    print(f"SQL  -> {out_sql.resolve()}")


if __name__ == "__main__":
    main()
