"""Convert the SIM test workbook to CSV and repeatable, non-destructive SQL."""

import argparse
import csv
import re
import sys
from pathlib import Path

from openpyxl import load_workbook


FIELDS = ["group_number", "msisdn", "imsi", "rp_name", "country", "comments"]
REQUIRED_COLUMNS = ["msisdn", "imsi", "rp_name", "country", "commentaires"]


def parse_args():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("excel_path", help="Chemin du classeur Excel source")
    parser.add_argument("--sheet", default="0", help="Nom ou index de la feuille")
    parser.add_argument("--out-csv", default="sim_test_roaming.csv")
    parser.add_argument("--out-sql", default="sim_test_roaming.sql")
    parser.add_argument("--table", default="sim_test_roaming")
    return parser.parse_args()


def clean(value):
    if value is None:
        return ""
    if isinstance(value, float) and value.is_integer():
        return str(int(value))
    return str(value).strip()


def sql_string(value):
    if value == "":
        return "NULL"
    return "'" + value.replace("'", "''") + "'"


def load_rows(excel_path, sheet_arg):
    workbook = load_workbook(excel_path, read_only=True, data_only=True)
    if sheet_arg.isdigit():
        sheet_index = int(sheet_arg)
        if sheet_index >= len(workbook.worksheets):
            raise ValueError("Index de feuille Excel invalide")
        sheet = workbook.worksheets[sheet_index]
    else:
        if sheet_arg not in workbook.sheetnames:
            raise ValueError("Feuille Excel introuvable : " + sheet_arg)
        sheet = workbook[sheet_arg]

    rows = sheet.iter_rows(values_only=True)
    header_row = None
    header_index = None
    for row_index, row in enumerate(rows):
        normalized = [clean(value).lower() for value in row]
        if all(name in normalized for name in REQUIRED_COLUMNS):
            header_row = normalized
            header_index = row_index
            break
    if header_row is None:
        raise ValueError("En-têtes Msisdn, Imsi, RP_name, Country et COMMENTAIRES introuvables")

    column_indexes = {name: header_row.index(name) for name in REQUIRED_COLUMNS}
    group_index = column_indexes["msisdn"] - 1
    current_group = ""
    output = []

    for row_index, row in enumerate(rows, start=header_index + 2):
        values = list(row)

        def cell(name):
            index = column_indexes[name]
            return clean(values[index]) if index < len(values) else ""

        group_value = clean(values[group_index]) if 0 <= group_index < len(values) else ""
        if group_value:
            if not group_value.isdigit():
                raise ValueError("Numéro de groupe invalide à la ligne Excel " + str(row_index))
            current_group = group_value

        msisdn = cell("msisdn")
        imsi = cell("imsi")
        rp_name = cell("rp_name")
        country = cell("country")
        comments = cell("commentaires")
        if not any([msisdn, imsi, rp_name, country, comments]):
            continue
        if not all([current_group, msisdn, imsi, rp_name, country]):
            raise ValueError("Données obligatoires manquantes à la ligne Excel " + str(row_index))

        output.append({
            "group_number": current_group,
            "msisdn": msisdn,
            "imsi": imsi,
            "rp_name": rp_name,
            "country": country,
            "comments": comments,
        })

    if not output:
        raise ValueError("Aucune ligne SIM trouvée dans le classeur")
    return output


def write_csv(rows, path):
    with Path(path).open("w", newline="", encoding="utf-8") as output_file:
        writer = csv.DictWriter(output_file, fieldnames=FIELDS)
        writer.writeheader()
        writer.writerows(rows)


def write_sql(rows, path, table):
    if not re.fullmatch(r"[A-Za-z_][A-Za-z0-9_]*", table):
        raise ValueError("Nom de table SQL invalide")

    with Path(path).open("w", encoding="utf-8") as output_file:
        output_file.write("-- Import non destructif : les lignes existantes ne sont jamais supprimees.\n")
        output_file.write("START TRANSACTION;\n")
        for offset in range(0, len(rows), 500):
            batch = rows[offset:offset + 500]
            values = []
            for row in batch:
                values.append("(" + ", ".join([
                    str(int(row["group_number"])),
                    sql_string(row["msisdn"]),
                    sql_string(row["imsi"]),
                    sql_string(row["rp_name"]),
                    sql_string(row["country"]),
                    sql_string(row["comments"]),
                ]) + ")")
            output_file.write(
                "INSERT INTO " + table + " (" + ", ".join(FIELDS) + ") VALUES\n"
                + ",\n".join(values)
                + "\nON DUPLICATE KEY UPDATE rp_name = VALUES(rp_name), "
                + "country = VALUES(country), comments = VALUES(comments);\n"
            )
        output_file.write("COMMIT;\n")


def main():
    args = parse_args()
    try:
        rows = load_rows(args.excel_path, args.sheet)
        write_csv(rows, args.out_csv)
        write_sql(rows, args.out_sql, args.table)
    except (OSError, ValueError) as error:
        sys.exit(str(error))

    print(str(len(rows)) + " lignes extraites.")
    print("CSV -> " + str(Path(args.out_csv).resolve()))
    print("SQL -> " + str(Path(args.out_sql).resolve()))


if __name__ == "__main__":
    main()
