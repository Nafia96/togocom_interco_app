-- Script MySQL pour extraire les factures litigieuses / litiges
-- Adapté au schéma utilisé par ce projet Laravel
-- Tables concernées : invoice, contestation, creditnote, operation, operator, users

SELECT
    c.id AS dispute_id,
    c.contesation_date AS dispute_date,
    COALESCE(c.amount, i.amount) AS dispute_amount,
    c.comment AS dispute_comment,
    c.is_delete AS dispute_deleted,

    i.id AS invoice_id,
    i.invoice_number,
    i.period,
    i.periodDate,
    i.invoice_date,
    i.amount AS invoice_amount,
    i.call_volume,
    i.number_of_call,
    i.invoice_type,
    i.comment AS invoice_comment,
    i.tgc_invoice,
    i.facture_name AS invoice_file,
    i.is_delete AS invoice_deleted,

    o.id AS operator_id,
    o.name AS operator_name,
    o.currency AS operator_currency,
    o.country AS operator_country,
    o.email AS operator_email,
    o.tel AS operator_tel,
    o.adresse AS operator_address,
    o.ope_account_number AS operator_account_number,
    o.banque_adresse AS operator_bank_address,
    o.swift_code AS operator_swift,

    op.id AS operation_id,
    op.operation_name,
    op.operation_type,
    op.repayment_type,
    op.amount AS operation_amount,
    op.incoming_balance,
    op.output_balance,
    op.new_netting,
    op.new_debt,
    op.new_receivable,
    op.comment AS operation_comment,
    op.created_at AS operation_created_at,

    cn.id AS creditnote_id,
    cn.debt AS creditnote_debt,
    cn.receivable AS creditnote_receivable,
    cn.comment AS creditnote_comment,
    cn.is_delete AS creditnote_deleted,

    u.id AS added_by_user_id,
    COALESCE(CONCAT_WS(' ', u.first_name, u.last_name), u.login, u.email) AS added_by_user_name,
    u.login AS added_by_login,
    u.email AS added_by_email
FROM invoice i
LEFT JOIN contestation c
    ON c.id_invoice = i.id
   AND c.is_delete = 0
LEFT JOIN creditnote cn
    ON cn.id_invoice = i.id
   AND cn.is_delete = 0
LEFT JOIN operator o
    ON o.id = i.operator_id
LEFT JOIN operation op
    ON op.id = c.operation_id
LEFT JOIN users u
    ON u.id = op.add_by
WHERE i.is_delete = 0
  AND (
        i.invoice_type = 'litigious'
        OR c.id IS NOT NULL
      )
ORDER BY
    c.contesation_date IS NULL,
    c.contesation_date,
    i.invoice_date,
    i.invoice_number;

-- Version CSV (optionnelle, si vous souhaitez exporter directement vers un fichier)
-- Ajustez le chemin si nécessaire.
--
-- SELECT
--     c.id AS dispute_id,
--     c.contesation_date AS dispute_date,
--     COALESCE(c.amount, i.amount) AS dispute_amount,
--     c.comment AS dispute_comment,
--     i.id AS invoice_id,
--     i.invoice_number,
--     i.invoice_date,
--     i.amount AS invoice_amount,
--     i.invoice_type,
--     o.name AS operator_name,
--     o.currency AS operator_currency,
--     o.country AS operator_country,
--     op.operation_name,
--     op.amount AS operation_amount,
--     cn.debt AS creditnote_debt,
--     cn.receivable AS creditnote_receivable,
--     cn.comment AS creditnote_comment
-- INTO OUTFILE 'C:/temp/litiges.csv'
-- FIELDS TERMINATED BY ';'
-- ENCLOSED BY '"'
-- LINES TERMINATED BY '\n'
-- FROM invoice i
-- LEFT JOIN contestation c ON c.id_invoice = i.id AND c.is_delete = 0
-- LEFT JOIN creditnote cn ON cn.id_invoice = i.id AND cn.is_delete = 0
-- LEFT JOIN operator o ON o.id = i.operator_id
-- LEFT JOIN operation op ON op.id = c.operation_id
-- WHERE i.is_delete = 0
--   AND (i.invoice_type = 'litigious' OR c.id IS NOT NULL)
-- ORDER BY c.contesation_date, i.invoice_date, i.invoice_number;
