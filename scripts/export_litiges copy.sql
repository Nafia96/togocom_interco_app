-- Script MySQL pour extraire les factures litigieuses / litiges
-- Adapté au schéma utilisé par ce projet Laravel
-- Tables concernées : invoice, contestation, creditnote, operation, operator, users
SELECT

    -- Diagnostic block (décommentez pour l'exécuter) :
    -- liste des factures ayant plusieurs contestations / creditnotes et les valeurs différentes
    --
    -- SELECT
    --   i.id AS invoice_id,
    --   i.invoice_number,
    --   i.period,
    --   i.invoice_date,
    --   o.name AS operator_name,
    --   COUNT(DISTINCT c.id) AS contestation_count,
    --   COUNT(DISTINCT cn.id) AS creditnote_count,
    --   GROUP_CONCAT(DISTINCT c.amount SEPARATOR ' | ') AS contestation_amounts,
    --   GROUP_CONCAT(DISTINCT cn.comment SEPARATOR ' || ') AS creditnote_comments
    -- FROM invoice i
    -- LEFT JOIN contestation c ON c.id_invoice = i.id AND c.is_delete = 0
    -- LEFT JOIN creditnote cn ON cn.id_invoice = i.id AND cn.is_delete = 0
    -- LEFT JOIN operator o ON o.id = i.operator_id
    -- WHERE i.is_delete = 0
    --   AND (i.invoice_type = 'litigious' OR c.id IS NOT NULL)
    -- GROUP BY i.id
    -- HAVING contestation_count + creditnote_count > 1
    -- ORDER BY i.invoice_date, i.invoice_number
    -- LIMIT 200;

    -- Requête principale : une seule ligne par `invoice` (récupère la dernière contestation/creditnote si plusieurs)
    (SELECT cc.contesation_date
     FROM contestation cc
     WHERE cc.id_invoice = i.id AND cc.is_delete = 0
     ORDER BY cc.contesation_date DESC
     LIMIT 1) AS dispute_date,
    o.name AS operator_name,
    i.period,
    i.invoice_date,
    i.invoice_number,
    COALESCE(
      (SELECT cc.amount
       FROM contestation cc
       WHERE cc.id_invoice = i.id AND cc.is_delete = 0
       ORDER BY cc.contesation_date DESC
       LIMIT 1),
      i.amount
    ) AS dispute_amount,
    i.amount AS invoice_amount,
    (SELECT cn.debt
     FROM creditnote cn
     WHERE cn.id_invoice = i.id AND cn.is_delete = 0
     ORDER BY cn.id DESC
     LIMIT 1) AS creditnote_debt,
    (SELECT cn.receivable
     FROM creditnote cn
     WHERE cn.id_invoice = i.id AND cn.is_delete = 0
     ORDER BY cn.id DESC
     LIMIT 1) AS creditnote_receivable,
    (SELECT cn.comment
     FROM creditnote cn
     WHERE cn.id_invoice = i.id AND cn.is_delete = 0
     ORDER BY cn.id DESC
     LIMIT 1) AS creditnote_comment

FROM invoice i
LEFT JOIN operator o
    ON o.id = i.operator_id
WHERE i.is_delete = 0
  AND (
        i.invoice_type = 'litigious'
        OR EXISTS (SELECT 1 FROM contestation cc WHERE cc.id_invoice = i.id AND cc.is_delete = 0)
      )
ORDER BY
    dispute_date IS NULL,
    dispute_date,
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
