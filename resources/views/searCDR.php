# Charger le fichier CSV initial
file_path = '/mnt/data/fct_isbc_202410091338.csv'

# Sélectionner les colonnes spécifiques à garder
columns_to_keep = ['file_name', 'record_type', 'edr_seq_num', 'original_a_num', 'served_msisdn', 'event_start_time']

# Lire le fichier CSV avec les colonnes sélectionnées
filtered_data = pd.read_csv(file_path, usecols=columns_to_keep)

# Chemin du nouveau fichier CSV
new_file_path = '/mnt/data/fct_isbc_202410091338_filtered.csv'

# Sauvegarder le fichier avec les colonnes sélectionnées
filtered_data.to_csv(new_file_path, index=False)

# Afficher le chemin du nouveau fichier CSV créé
new_file_path



---------------------------------------------------Debut----------------------------------------------------

CREATE TABLE ALL_DAY_CDR (
    file_name VARCHAR,                         -- Autres colonnes en VARCHAR
    record_type VARCHAR,
    edr_seq_num VARCHAR,
    edr_session_id VARCHAR,
    original_a_num VARCHAR,
    served_msisdn VARCHAR,
    original_b_num VARCHAR,
    other_msisdn VARCHAR,
    event_start_time VARCHAR,
    serv_end_timestamp VARCHAR,
    event_end_time VARCHAR,
    apn_name VARCHAR,
    partial_record_num VARCHAR,
    service_reason_return_code VARCHAR,
    ss_service_category VARCHAR,
    call_type VARCHAR,
    call_direction VARCHAR,
    served_imsi VARCHAR,
    other_imsi VARCHAR,
    roaming_ind VARCHAR,
    rating_action VARCHAR,
    original_dur VARCHAR,
    in_tg_grp_id VARCHAR,
    out_tg_grp_id VARCHAR,
    rounded_call_duration VARCHAR,
    small_incoming_trunk_id VARCHAR,
    small_outgoing_trunk_id VARCHAR,
    ipbin_v4_address VARCHAR,
    sys_id_key VARCHAR,
    ne_id_key VARCHAR,
    subs_bu_key VARCHAR,
    event_type_key VARCHAR,
    event_direction_key VARCHAR,
    event_time_slot_key VARCHAR,
    event_date VARCHAR,
    population_date_time VARCHAR,
    served_msisdn_dial_digit_key VARCHAR,
    other_msisdn_dial_digit_key VARCHAR,
    rec_type_id_key VARCHAR,
    termination_reason_key VARCHAR,
    event_status_key VARCHAR,
    srv_type_key VARCHAR,
    in_tg_id_key VARCHAR,
    out_tg_id_key VARCHAR,
    ri_mismatch_reason VARCHAR,
    ri_mismatch_ind VARCHAR,
    flexi_col_1 VARCHAR,
    flexi_col_2 VARCHAR,
    flexi_col_3 VARCHAR,
    flexi_col_4 VARCHAR,
    flexi_col_5 VARCHAR,
    flexi_col_6 VARCHAR,
    flexi_col_7 VARCHAR,
    flexi_col_8 VARCHAR,
    flexi_col_9 VARCHAR,
    flexi_col_10 VARCHAR,
    flexi_ind_1 VARCHAR,
    flexi_ind_2 VARCHAR,
    flexi_ind_3 VARCHAR,
    flexi_ind_4 VARCHAR,
    call_type_desc VARCHAR,
    role_of_node VARCHAR
);


-----------------------------insertion------------------------------------------------------------------

COPY all_day_cdr
FROM '/tmp/fct_isbc_202410211225.csv'
WITH (FORMAT csv, HEADER, NULL '');


\copy ALL_DAY_CDR (
    file_name,
    record_type,
    edr_seq_num,
    edr_session_id,
    original_a_num,
    served_msisdn,
    original_b_num,
    other_msisdn,
    event_start_time,
    serv_end_timestamp,
    event_end_time,
    apn_name,
    partial_record_num,
    service_reason_return_code,
    ss_service_category,
    call_type,
    call_direction,
    served_imsi,
    other_imsi,
    roaming_ind,
    rating_action,
    original_dur,
    in_tg_grp_id,
    out_tg_grp_id,
    rounded_call_duration,
    small_incoming_trunk_id,
    small_outgoing_trunk_id,
    ipbin_v4_address,
    sys_id_key,
    ne_id_key,
    subs_bu_key,
    event_type_key,
    event_direction_key,
    event_time_slot_key,
    event_date,
    population_date_time,
    served_msisdn_dial_digit_key,
    other_msisdn_dial_digit_key,
    rec_type_id_key,
    termination_reason_key,
    event_status_key,
    srv_type_key,
    in_tg_id_key,
    out_tg_id_key,
    ri_mismatch_reason,
    ri_mismatch_ind,
    flexi_col_1,
    flexi_col_2,
    flexi_col_3,
    flexi_col_4,
    flexi_col_5,
    flexi_col_6,
    flexi_col_7,
    flexi_col_8,
    flexi_col_9,
    flexi_col_10,
    flexi_ind_1,
    flexi_ind_2,
    flexi_ind_3,
    flexi_ind_4,
    call_type_desc,
    role_of_node
)
FROM '/tmp/fct_isbc_202410211224.csv' WITH (FORMAT csv, NULL '');
DELIMITER ','
CSV HEADER;



\copy ALL_DAY_CDR (
    file_name,
    record_type,
    edr_seq_num,
    edr_session_id,
    original_a_num,
    served_msisdn,
    original_b_num,
    other_msisdn,
    event_start_time,
    serv_end_timestamp,
    event_end_time,
    apn_name,
    partial_record_num,
    service_reason_return_code,
    ss_service_category,
    call_type,
    call_direction,
    served_imsi,
    other_imsi,
    roaming_ind,
    rating_action,
    original_dur,
    in_tg_grp_id,
    out_tg_grp_id,
    rounded_call_duration,
    small_incoming_trunk_id,
    small_outgoing_trunk_id,
    ipbin_v4_address,
    sys_id_key,
    ne_id_key,
    subs_bu_key,
    event_type_key,
    event_direction_key,
    event_time_slot_key,
    event_date,
    population_date_time,
    served_msisdn_dial_digit_key,
    other_msisdn_dial_digit_key,
    rec_type_id_key,
    termination_reason_key,
    event_status_key,
    srv_type_key
)
FROM '/tmp/fct_isbc_202410211225.csv'
WITH (FORMAT csv, HEADER, NULL '');

------------Suppression des colonnes unitules-------------------------------------------------------------------

ALTER TABLE ALL_CDR
DROP COLUMN record_type,
DROP COLUMN edr_seq_num,
DROP COLUMN edr_session_id,
DROP COLUMN apn_name,
DROP COLUMN partial_record_num,
DROP COLUMN ss_service_category,
DROP COLUMN call_type,
DROP COLUMN call_direction,
DROP COLUMN served_imsi,
DROP COLUMN other_imsi,
DROP COLUMN roaming_ind,
DROP COLUMN rating_action,
DROP COLUMN original_dur,
DROP COLUMN in_tg_grp_id,
DROP COLUMN out_tg_grp_id,
DROP COLUMN ipbin_v4_address,
DROP COLUMN sys_id_key,
DROP COLUMN ne_id_key,
DROP COLUMN subs_bu_key,
DROP COLUMN event_type_key,
DROP COLUMN event_direction_key,
DROP COLUMN event_time_slot_key,
DROP COLUMN population_date_time,
DROP COLUMN served_msisdn_dial_digit_key,
DROP COLUMN other_msisdn_dial_digit_key,
DROP COLUMN rec_type_id_key,
DROP COLUMN termination_reason_key,
DROP COLUMN event_status_key,
DROP COLUMN srv_type_key,
DROP COLUMN in_tg_id_key,
DROP COLUMN out_tg_id_key,
DROP COLUMN ri_mismatch_reason,
DROP COLUMN ri_mismatch_ind,
DROP COLUMN flexi_col_1,
DROP COLUMN flexi_col_2,
DROP COLUMN flexi_col_3,
DROP COLUMN flexi_col_4,
DROP COLUMN flexi_col_5,
DROP COLUMN flexi_col_6,
DROP COLUMN flexi_col_7,
DROP COLUMN flexi_col_8,
DROP COLUMN flexi_col_9,
DROP COLUMN flexi_col_10,
DROP COLUMN flexi_ind_1,
DROP COLUMN flexi_ind_2,
DROP COLUMN flexi_ind_3,
DROP COLUMN flexi_ind_4,
DROP COLUMN call_type_desc,



----------------------------Filtrage de original-a-number-------------------------------

UPDATE ALL_CDR
SET original_a_num = CASE
    -- Extrait l'adresse IP de la chaîne si elle existe
    WHEN original_a_num ~ '([0-9]{1,3}\.){3}[0-9]{1,3}' THEN
        -- Extraire l'adresse IP à l'aide de la fonction regexp_replace
        regexp_replace(original_a_num, '.*@([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}).*', '\1')
    ELSE
        -- Mettre N
ULL si aucune adresse IP n'est trouvée
        NULL
END;


------------------------------ Filtrage de  original_b_num------------------------------------

UPDATE ALL_CDR
SET original_b_num = CASE
    -- Extrait l'adresse IP de la chaîne si elle existe
    WHEN original_b_num ~ '([0-9]{1,3}\.){3}[0-9]{1,3}' THEN
        -- Extraire l'adresse IP à l'aide de la fonction regexp_replace
        regexp_replace(original_b_num, '.*@([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}).*', '\1')
    ELSE
        -- Mettre NULL si aucune adresse IP n'est trouvée
        NULL
END;

----------------------------------FIN--------------------------------------------------------------


CREATE TABLE CDR_FILTERED (
    id SERIAL PRIMARY KEY,                     -- Colonne ID auto-incrémentée
    file_name VARCHAR,  --1
    original_a_num VARCHAR, --2
    served_msisdn VARCHAR, --12

    original_b_num VARCHAR, --3
    other_msisdn VARCHAR, --4
    event_start_time VARCHAR, --5

    event_end_time VARCHAR, --6

    service_reason_return_code VARCHAR, --7

    rounded_call_duration VARCHAR, --8
    small_incoming_trunk_id VARCHAR, --9
    small_outgoing_trunk_id VARCHAR, --10

    event_date VARCHAR, --11

);

CREATE TABLE ALL_CDR (
    id SERIAL PRIMARY KEY,                     -- Colonne ID auto-incrémentée
    file_name VARCHAR(255),                    --1
    original_a_num VARCHAR(255),               --2
    served_msisdn VARCHAR(255),                --12
    original_b_num VARCHAR(255),               --3
    other_msisdn VARCHAR(255),                 --4
    event_start_time VARCHAR(255),             --5
    event_end_time VARCHAR(255),               --6
    service_reason_return_code VARCHAR(255),   --7
    rounded_call_duration VARCHAR(255),        --8
    small_incoming_trunk_id VARCHAR(255),      --9
    small_outgoing_trunk_id VARCHAR(255),      --10
    event_date VARCHAR(255)                    --11
);


\copy CDR_FILTERED (
    file_name VARCHAR(255),                    --1
    original_a_num VARCHAR(255),               --2
    served_msisdn VARCHAR(255),                --12
    original_b_num VARCHAR(255),               --3
    other_msisdn VARCHAR(255),                 --4
    event_start_time VARCHAR(255),             --5
    event_end_time VARCHAR(255),               --6
    service_reason_return_code VARCHAR(255),   --7
    rounded_call_duration VARCHAR(255),        --8
    small_incoming_trunk_id VARCHAR(255),      --9
    small_outgoing_trunk_id VARCHAR(255),      --10
    event_date VARCHAR(255)                    --11
)
FROM '/tmp/fct_isbc_202410091339.csv'
DELIMITER ','
CSV HEADER;



INSERT INTO trunk_data (site, site_ip, mss_trunk_group_id, togocom_ip, isbc_trunk_group_id, carrier_ip, carrier_name, trunk_status) VALUES
('CAC', '10.220.0.36', '600', '197.148.112.4', '2000', '41.138.62.10', 'NIGER TELECOM', 'UP'),
('CAC', '10.220.0.36', '800', '197.148.112.4', '2000', '41.138.62.10', 'NIGER TELECOM', 'UP'),
('LOM', '10.221.0.36', '700', '197.148.113.4', '2500', '41.138.62.10', 'NIGER TELECOM', 'UP'),
('LOM', '10.221.0.36', '900', '197.148.113.4', '2500', '41.138.62.10', 'NIGER TELECOM', 'UP'),
('CAC', '10.220.0.36', '601', '197.148.112.4', '2001', '41.181.104.152', 'MTN GLOBAL CONNECT', 'UP'),
('CAC', '10.220.0.36', '801', '197.148.112.4', '2002', '41.181.104.184', 'MTN GLOBAL CONNECT', 'UP'),
('LOM', '10.221.0.36', '701', '197.148.113.4', '2501', '41.181.104.184', 'MTN GLOBAL CONNECT', 'UP'),
('LOM', '10.221.0.36', '901', '197.148.113.4', '2502', '41.181.104.152', 'MTN GLOBAL CONNECT', 'UP'),
('CAC', '10.220.0.36', '603', '197.148.112.4', '2003', '204.15.169.110', 'BANKAI GROUP', 'UP'),
('CAC', '10.220.0.36', '803', '197.148.112.4', '2004', '204.15.169.78', 'BANKAI GROUP', 'UP'),
('LOM', '10.221.0.36', '703', '197.148.113.4', '2504', '204.15.169.78', 'BANKAI GROUP', 'UP'),
('LOM', '10.221.0.36', '903', '197.148.113.4', '2503', '204.15.169.110', 'BANKAI GROUP', 'UP'),
('CAC', '10.220.0.36', '605', '197.148.112.4', '2005', '89.221.45.22', 'TIS', 'UP'),
('CAC', '10.220.0.36', '805', '197.148.112.4', '2005', '89.221.45.22', 'TIS', 'UP'),
('LOM', '10.221.0.36', '705', '197.148.113.4', '2505', '89.221.45.22', 'TIS', 'UP'),
('LOM', '10.221.0.36', '905', '197.148.113.4', '2505', '89.221.45.22', 'TIS', 'UP'),
('CAC', '10.220.0.36', '607', '197.148.112.4', '2007', '41.78.117.148', 'MOOV AFRICA NIGER', 'UP'),
('CAC', '10.220.0.36', '807', '197.148.112.4', '2007', '41.78.117.148', 'MOOV AFRICA NIGER', 'UP'),
('LOM', '10.221.0.36', '707', '197.148.113.4', '2507', '41.78.117.148', 'MOOV AFRICA NIGER', 'UP'),
('LOM', '10.221.0.36', '907', '197.148.113.4', '2507', '41.78.117.148', 'MOOV AFRICA NIGER', 'UP'),
('CAC', '10.220.0.36', '608', '197.148.112.4', '2008', '79.170.64.152', 'BTS', 'UP'),
('CAC', '10.220.0.36', '808', '197.148.112.4', '2009', '79.170.64.180', 'BTS', 'UP'),
('LOM', '10.221.0.36', '708', '197.148.113.4', '2509', '79.170.64.180', 'BTS', 'UP'),
('LOM', '10.221.0.36', '908', '197.148.113.4', '2508', '79.170.64.152', 'BTS', 'UP'),
('CAC', '10.220.0.36', '610', '197.148.112.4', '2010', '41.82.253.132', 'SONATEL - ORANGE', 'UP'),
('CAC', '10.220.0.36', '810', '197.148.112.4', '2011', '196.207.195.85', 'SONATEL - ORANGE', 'UP'),
('LOM', '10.221.0.36', '710', '197.148.113.4', '2510', '196.207.195.85', 'SONATEL - ORANGE', 'UP'),
('LOM', '10.221.0.36', '910', '197.148.113.4', '2511', '41.82.253.132', 'SONATEL - ORANGE', 'UP'),
('CAC', '10.220.0.36', '612', '197.148.112.4', '2012', '80.84.31.13', 'BICS', 'UP'),
('CAC', '10.220.0.36', '812', '197.148.112.4', '2013', '94.102.175.32', 'BICS', 'UP'),
('LOM', '10.221.0.36', '712', '197.148.113.4', '2513', '80.84.31.13', 'BICS', 'UP'),
('LOM', '10.221.0.36', '912', '197.148.113.4', '2512', '94.102.175.32', 'BICS', 'UP'),
('CAC', '10.220.0.36', '614', '197.148.112.4', '2014', '94.102.175.36', 'BICS', 'UP'),
('CAC', '10.220.0.36', '814', '197.148.112.4', '2014', '94.102.175.36', 'BICS', 'UP'),
('LOM', '10.221.0.36', '714', '197.148.113.4', '2514', '80.84.31.16', 'BICS', 'UP'),
('LOM', '10.221.0.36', '914', '197.148.113.4', '2514', '80.84.31.16', 'BICS', 'UP'),
('CAC', '10.220.0.36', '615', '197.148.112.4', '2015', '81.52.202.1', 'ORANGE-IC ', 'UP'),
('CAC', '10.220.0.36', '815', '197.148.112.4', '2016', '81.52.202.33', 'ORANGE-IC ', 'UP'),
('LOM', '10.221.0.36', '715', '197.148.113.4', '2515', '81.52.202.2', 'ORANGE-IC ', 'UP'),
('LOM', '10.221.0.36', '915', '197.148.113.4', '2516', '81.52.202.34', 'ORANGE-IC ', 'UP'),
('CAC', '10.220.0.36', '632', '197.148.112.4', '2032', '81.52.202.2', 'ORANGE-IC ', 'UP'),
('CAC', '10.220.0.36', '832', '197.148.112.4', '2033', '81.52.202.34', 'ORANGE-IC ', 'UP'),
('LOM', '10.221.0.36', '732', '197.148.113.4', '2532', '81.52.202.1', 'ORANGE-IC ', 'UP'),
('LOM', '10.221.0.36', '932', '197.148.113.4', '2533', '81.52.202.33', 'ORANGE-IC ', 'UP'),
('CAC', '10.220.0.36', '617', '197.148.112.4', '2017', '62.93.150.244', 'IBASIS', 'UP'),
('CAC', '10.220.0.36', '817', '197.148.112.4', '2017', '62.93.150.244', 'IBASIS', 'UP'),
('LOM', '10.221.0.36', '717', '197.148.113.4', '2517', '62.93.150.244', 'IBASIS', 'UP'),
('LOM', '10.221.0.36', '917', '197.148.113.4', '2517', '62.93.150.244', 'IBASIS', 'UP'),
('CAC', '10.220.0.36', '618', '197.148.112.4', '2018', '62.93.141.16', 'IBASIS', 'UP'),
('CAC', '10.220.0.36', '818', '197.148.112.4', '2019', '216.168.188.134', 'IBASIS', 'UP'),
('LOM', '10.221.0.36', '718', '197.148.113.4', '2518', '62.93.141.16', 'IBASIS', 'UP'),
('LOM', '10.221.0.36', '918', '197.148.113.4', '2519', '216.168.188.134', 'IBASIS', 'UP'),
('CAC', '10.220.0.36', '620', '197.148.112.4', '2020', '62.93.156.16', 'IBASIS', 'UP'),
('LOM', '10.221.0.36', '720', '197.148.113.4', '2520', '62.93.156.16', 'IBASIS', 'UP'),
('CAC', '10.220.0.36', '622', '197.148.112.4', '2022', '160.154.18.20', 'ORANGE-CI', 'UP'),
('CAC', '10.220.0.36', '822', '197.148.112.4', '2023', '160.154.19.20', 'ORANGE-CI', 'UP'),
('LOM', '10.221.0.36', '722', '197.148.113.4', '2522', '160.154.19.20', 'ORANGE-CI', 'UP'),
('LOM', '10.221.0.36', '922', '197.148.113.4', '2523', '160.154.18.20', 'ORANGE-CI', 'UP'),
('CAC', '10.220.0.36', '643', '197.148.112.4', '2043', '41.138.91.212', 'MOOVAFRICA BENIN', 'UP'),
('CAC', '10.220.0.36', '843', '197.148.112.4', '2043', '41.138.91.212', 'MOOVAFRICA BENIN', 'UP'),
('LOM', '10.221.0.36', '743', '197.148.113.4', '2543', '41.138.91.212', 'MOOVAFRICA BENIN', 'UP'),
('LOM', '10.221.0.36', '943', '197.148.113.4', '2543', '41.138.91.212', 'MOOVAFRICA BENIN', 'UP'),
('CAC', '10.220.0.36', '644', '197.148.112.4', '2044', '197.239.92.2', 'ORANGE BF', 'UP'),
('CAC', '10.220.0.36', '844', '197.148.112.4', '2044', '197.239.92.2', 'ORANGE BF', 'UP'),
('LOM', '10.221.0.36', '744', '197.148.113.4', '2544', '197.239.92.2', 'ORANGE BF', 'UP'),
('LOM', '10.221.0.36', '944', '197.148.113.4', '2544', '197.239.92.2', 'ORANGE BF', 'UP'),
('CAC', '10.220.0.36', '626', '197.148.112.4', '2026', '102.164.129.250', 'FREE SENEGAL', 'UP'),
('CAC', '10.220.0.36', '826', '197.148.112.4', '2027', '102.164.129.233', 'FREE SENEGAL', 'UP'),
('LOM', '10.221.0.36', '726', '197.148.113.4', '2526', '102.164.129.233', 'FREE SENEGAL', 'UP'),
('LOM', '10.221.0.36', '926', '197.148.113.4', '2527', '102.164.129.250', 'FREE SENEGAL', 'UP'),
('CAC', '10.220.0.36', '647', '197.148.112.4', '2047', '41.158.0.18', 'MAGABON', 'UP'),
('CAC', '10.220.0.36', '847', '197.148.112.4', '2048', '41.158.0.26', 'MAGABON', 'UP'),
('LOM', '10.221.0.36', '747', '197.148.113.4', '2547', '41.158.0.26', 'MAGABON', 'UP'),
('LOM', '10.221.0.36', '947', '197.148.113.4', '2548', '41.158.0.18', 'MAGABON', 'UP'),
('CAC', '10.220.0.36', '628', '197.148.112.4', '2028', '41.203.141.114', 'ZAMANI TELECOM', 'UP'),
('CAC', '10.220.0.36', '828', '197.148.112.4', '2029', '41.203.141.114', 'ZAMANI TELECOM', 'UP'),
('LOM', '10.221.0.36', '728', '197.148.113.4', '2528', '41.203.141.115', 'ZAMANI TELECOM', 'UP'),
('LOM', '10.221.0.36', '928', '197.148.113.4', '2529', '41.203.141.115', 'ZAMANI TELECOM', 'UP'),
('CAC', '10.220.0.36', '630', '197.148.112.4', '2030', '41.203.141.105', 'ZAMANI TELECOM', 'UP'),
('CAC', '10.220.0.36', '830', '197.148.112.4', '2031', '41.203.141.106', 'ZAMANI TELECOM', 'UP'),
('LOM', '10.221.0.36', '730', '197.148.113.4', '2530', '41.203.141.106', 'ZAMANI TELECOM', 'UP'),
('LOM', '10.221.0.36', '930', '197.148.113.4', '2531', '41.203.141.105', 'ZAMANI TELECOM', 'UP'),
('CAC', '10.220.0.36', '642', '197.148.112.4', '2042', '217.64.109.36', 'MOOVAFRICA MALI', 'UP'),
('CAC', '10.220.0.36', '842', '197.148.112.4', '2042', '217.64.109.36', 'MOOVAFRICA MALI', 'UP'),
('LOM', '10.221.0.36', '742', '197.148.113.4', '2542', '217.64.109.36', 'MOOVAFRICA MALI', 'UP'),
('LOM', '10.221.0.36', '942', '197.148.113.4', '2542', '217.64.109.36', 'MOOVAFRICA MALI', 'UP'),
('CAC', '10.220.0.36', '624', '197.148.112.4', '2024', '196.28.243.213', 'MOOV AFRICA BURKINA FASO', 'UP'),
('CAC', '10.220.0.36', '824', '197.148.112.4', '2025', '196.28.240.229', 'MOOV AFRICA BURKINA FASO', 'UP'),
('LOM', '10.221.0.36', '724', '197.148.113.4', '2524', '196.28.243.229', 'MOOV AFRICA BURKINA FASO', 'UP'),
('LOM', '10.221.0.36', '924', '197.148.113.4', '2525', '196.28.240.213', 'MOOV AFRICA BURKINA FASO', 'UP'),
('CAC', '10.220.0.36', '638', '197.148.112.4', '2038', '154.126.15.20', 'TELMA', 'UP'),
('CAC', '10.220.0.36', '838', '197.148.112.4', '2039', '154.126.15.116 ', 'TELMA', 'UP'),
('LOM', '10.221.0.36', '738', '197.148.113.4', '2538', '154.126.15.116', 'TELMA', 'UP'),
('LOM', '10.221.0.36', '938', '197.148.113.4', '2539', '154.126.15.20 ', 'TELMA', 'UP'),
('CAC', '10.220.0.36', '649', '197.148.112.4', '2049', '154.0.186.1', 'AIRTEL GABON', 'UP'),
('CAC', '10.220.0.36', '849', '197.148.112.4', '2050', '154.0.186.4', 'AIRTEL GABON', 'UP'),
('LOM', '10.221.0.36', '749', '197.148.113.4', '2549', '154.0.186.4', 'AIRTEL GABON', 'UP'),
('LOM', '10.221.0.36', '949', '197.148.113.4', '2550', '154.0.186.1', 'AIRTEL GABON', 'UP'),
('CAC', '10.220.0.36', '634', '197.148.112.4', '2034', '41.191.68.84', 'MOOV AFRICA CI', 'UP'),
('CAC', '10.220.0.36', '834', '197.148.112.4', '2035', '154.0.25.228', 'MOOV AFRICA CI', 'UP'),
('LOM', '10.221.0.36', '734', '197.148.113.4', '2534', '154.0.25.228', 'MOOV AFRICA CI', 'UP'),
('LOM', '10.221.0.36', '934', '197.148.113.4', '2535', '41.191.68.84', 'MOOV AFRICA CI', 'UP'),
('CAC', '10.220.0.36', '636', '197.148.112.4', '2036', '41.191.68.85', 'MOOV AFRICA CI', 'UP'),
('CAC', '10.220.0.36', '836', '197.148.112.4', '2037', '154.0.25.229', 'MOOV AFRICA CI', 'UP'),
('LOM', '10.221.0.36', '736', '197.148.113.4', '2536', '154.0.25.229', 'MOOV AFRICA CI', 'UP'),
('LOM', '10.221.0.36', '936', '197.148.113.4', '2537', '41.191.68.85', 'MOOV AFRICA CI', 'UP'),
('CAC', '10.220.0.36', '651', '197.148.112.4', '2051', '35.234.149.197', 'GOZEM', 'UP'),
('CAC', '10.220.0.36', '851', '197.148.112.4', '2051', '35.234.149.197', 'GOZEM', 'UP'),
('LOM', '10.221.0.36', '751', '197.148.113.4', '2551', '35.234.149.197', 'GOZEM', 'UP'),
('LOM', '10.221.0.36', '951', '197.148.113.4', '2551', '35.234.149.197', 'GOZEM', 'UP'),
('CAC', '10.220.0.36', '652', '197.148.112.4', '2052', '204.15.169.170', 'BANKAI IN', 'UP'),
('LOM', '10.221.0.36', '952', '197.148.113.4', '2552', '204.15.169.170', 'BANKAI IN', 'UP'),
('CAC', '10.220.0.36', '653', '197.148.112.4', '2053', '41.215.161.77', 'AIRTEL-TIGO', 'UP'),
('CAC', '10.220.0.36', '853', '197.148.112.4', '2054', '41.215.166.237', 'AIRTEL-TIGO', 'UP'),
('LOM', '10.221.0.36', '753', '197.148.113.4', '2553', '41.215.166.237', 'AIRTEL-TIGO', 'UP'),
('LOM', '10.221.0.36', '953', '197.148.113.4', '2554', '41.215.161.77', 'AIRTEL-TIGO', 'UP'),
('CAC', '10.220.0.36', '655', '197.148.112.4', '2055', '41.181.104.152', 'MTNGC-ECOWAS', 'UP'),
('CAC', '10.220.0.36', '855', '197.148.112.4', '2056', '41.181.104.184', 'MTNGC-ECOWAS', 'UP'),
('LOM', '10.221.0.36', '755', '197.148.113.4', '2555', '41.181.104.152', 'MTNGC-ECOWAS', 'UP'),
('LOM', '10.221.0.36', '955', '197.148.113.4', '2556', '41.181.104.184', 'MTNGC-ECOWAS', 'UP'),
('CAC', '10.220.0.36', '657', '197.148.112.4', '2057', '41.216.51.133 ', 'SBIN', 'UP'),
('CAC', '10.220.0.36', '857', '197.148.112.4', '2058', '41.216.52.133 ', 'SBIN', 'UP'),
('LOM', '10.221.0.36', '757', '197.148.113.4', '2557', '41.216.51.133 ', 'SBIN', 'UP'),
('LOM', '10.221.0.36', '957', '197.148.113.4', '2558', '41.216.52.133 ', 'SBIN', 'UP'),
('CAC', '10.220.0.36', '659', '197.148.112.4', '2059', '41.216.51.101 ', 'SBIN LAB', 'UP'),
('CAC', '10.220.0.36', '859', '197.148.112.4', '2059', '41.216.51.101 ', 'SBIN LAB', 'UP'),
('LOM', '10.221.0.36', '759', '197.148.113.4', '2559', '41.216.51.101 ', 'SBIN LAB', 'UP'),
('LOM', '10.221.0.36', '959', '197.148.113.4', '2559', '41.216.51.101 ', 'SBIN LAB', 'UP'),
('CAC', '10.220.0.36', '661', '197.148.112.4', '2061', '102.176.113.98', 'VODAFONE GHANA', 'UP'),
('CAC', '10.220.0.36', '861', '197.148.112.4', '2062', '102.176.114.154', 'VODAFONE GHANA', 'UP'),
('LOM', '10.221.0.36', '761', '197.148.113.4', '2561', '102.176.113.98', 'VODAFONE GHANA', 'UP'),
('LOM', '10.221.0.36', '961', '197.148.113.4', '2562', '102.176.114.154', 'VODAFONE GHANA', 'UP'),
('CAC', '10.220.0.36', '663', '197.148.112.4', '2063', '146.59.203.14', 'MOONTG', 'UP'),
('CAC', '10.220.0.36', '863', '197.148.112.4', '2064', '146.59.203.14', 'MOONTG', 'UP'),
('LOM', '10.221.0.36', '763', '197.148.113.4', '2563', '146.59.203.14', 'MOONTG', 'UP'),
('LOM', '10.221.0.36', '963', '197.148.113.4', '2564', '146.59.203.14', 'MOONTG', 'UP'),
('CAC', '10.220.0.36', '606', '197.148.112.4', '2006', '185.101.180.190', 'MANIFONE', 'UP'),
('CAC', '10.220.0.36', '806', '197.148.112.4', '2021', '185.101.180.252', 'MANIFONE', 'UP'),
('LOM', '10.221.0.36', '706', '197.148.113.4', '2521', '185.101.180.252', 'MANIFONE', 'UP'),
('LOM', '10.221.0.36', '906', '197.148.113.4', '2506', '185.101.180.190', 'MANIFONE', 'UP'),
('CAC', '10.220.0.36', '640', '197.148.112.4', '2040', '89.31.241.220', 'LANCK TELECOM', 'DOWN'),
('CAC', '10.220.0.36', '840', '197.148.112.4', '2041', '89.31.241.210', 'LANCK TELECOM', 'DOWN'),
('LOM', '10.221.0.36', '740', '197.148.113.4', '2541', '89.31.241.210', 'LANCK TELECOM', 'DOWN'),
('LOM', '10.221.0.36', '940', '197.148.113.4', '2540', '89.31.241.220', 'LANCK TELECOM', 'DOWN'),
('CAC', '10.220.0.36', '645', '197.148.112.4', '2045', '192.210.11.210', 'LANCK TELECOM', 'DOWN'),
('CAC', '10.220.0.36', '845', '197.148.112.4', '2046', '203.189.27.210', 'LANCK TELECOM', 'DOWN'),
('LOM', '10.221.0.36', '745', '197.148.113.4', '2546', '203.189.27.210', 'LANCK TELECOM', 'DOWN'),
('LOM', '10.221.0.36', '945', '197.148.113.4', '2545', '192.210.11.210', 'LANCK TELECOM', 'DOWN');












-----------------------------------------------------
25-
10-
2024

SELECT SUM(total_duration_in_minutes) AS total_sum_duration_in_minutes
FROM (
    SELECT td.carrier_name,
           SUM(CAST(NULLIF(ac.rounded_call_duration, '') AS INTEGER)) / 60 AS total_duration_in_minutes
    FROM trunk_data td
    JOIN all_day_cdr_dedup ac
    ON CAST(td.isbc_trunk_group_id AS VARCHAR) = ac.small_outgoing_trunk_id
    WHERE NULLIF(ac.rounded_call_duration, '') IS NOT NULL
      AND CAST(NULLIF(ac.rounded_call_duration, '') AS INTEGER) > 0
      AND CAST(ac.role_of_node AS INTEGER) = 0
    GROUP BY td.carrier_name
) AS subquery;



---------------------------------------------------------------------------

SELECT
    CASE
        WHEN role_of_node = 0 THEN LEFT(served_msisdn, 3) -- B-num pour les appels sortants
        WHEN role_of_node = 1 THEN LEFT(other_msisdn, 3) -- A-num pour les appels entrants
    END AS indicatif_pays,
    role_of_node,
    COUNT(*) AS nombre_appels
FROM
all_day_cdr -- Remplacez par le nom de votre table CDR
WHERE
    (role_of_node = 0 AND served_msisdn IS NOT NULL) OR
    (role_of_node = 1 AND other_msisdn IS NOT NULL)
GROUP BY
    indicatif_pays, role_of_node
ORDER BY
    nombre_appels DESC;
---------------------------------------------------------------------------------------filtre par pays-----------------

SELECT
    LEFT(served_msisdn, 3) AS indicatif_pays,  -- Préfixe pour les appels sortants
    role_of_node,
    COUNT(*) AS nombre_appels,
    SUM(CAST(NULLIF(rounded_call_duration, '') AS INTEGER)) / 60 AS volume_total_duree_minutes,  -- Durée totale en minutes par indicatif
    (SELECT SUM(CAST(NULLIF(rounded_call_duration, '') AS INTEGER)) / 60 FROM all_day_cdr WHERE CAST(role_of_node AS INTEGER) = 1) AS volume_global_minutes -- Durée totale en minutes pour tous les appels sortants
FROM
    all_day_cdr
WHERE
    CAST(role_of_node AS INTEGER) = 1 AND served_msisdn IS NOT NULL
GROUP BY
    indicatif_pays, role_of_node
ORDER BY
    nombre_appels DESC;


    --------------------------------------TEST-----------------------------------------------------
    INSERT INTO countrycode (country_code, country_name, region_code)
SELECT
    CASE
        WHEN position('-' IN country_code) > 0 THEN split_part(country_code, '-', 2)  -- Région (si présente)
        ELSE NULL
    END AS region_code,
    country_name
FROM temp_import;



















CREATE OR REPLACE FUNCTION extract_country_info(phone_number VARCHAR)
RETURNS TABLE(country_code VARCHAR, country_name VARCHAR)
LANGUAGE plpgsql AS $$
DECLARE
    code VARCHAR(3);
    region VARCHAR(3);
BEGIN
    -- Boucle pour tester les codes pays, du plus long au plus court
    FOR code IN (SELECT DISTINCT country_code FROM CountryCode ORDER BY LENGTH(country_code) DESC) LOOP
        -- Vérifie si le numéro commence par le code pays actuel
        IF phone_number LIKE code || '%' THEN
            -- Extraire les 3 chiffres suivants après le code pays pour l'indicatif régional (si disponible)
            region := SUBSTRING(phone_number FROM LENGTH(code) + 1 FOR 3);

            -- Recherche du pays correspondant avec code pays et indicatif régional, ou NULL si non défini
            RETURN QUERY
            SELECT country_code, country_name
            FROM CountryCode
            WHERE country_code = code
            AND (region_code = region OR region_code IS NULL)
            LIMIT 1;

            EXIT; -- Arrête la recherche dès qu'une correspondance est trouvée
        END IF;
    END LOOP;

    -- Si aucun code pays valide n'est trouvé, retourne NULL pour les deux valeurs
    RETURN QUERY SELECT NULL, NULL;
END;
$$;
















----------------------------------$_COOKIE

httrack "URL_DU_SITE" -O "/chemin/vers/dossier_de_destination"


httrack "https://preview.themeforest.net/item/porto-ecommerce-shop-template/full_screen_preview/" -O "/home/djire-nahfiou/copied_site"


httrack "https://www.portotheme.com/html/porto_ecommerce/" -O "/home/djire-nahfiou/copied_site4" --robots=0 --user-agent "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36" -c2 -A10000


-----------------------------------------------------COUPER LE COUNTRY CODE ET LE COUNTRY NAME -----------------------------
UPDATE all_day_cdr
SET
    country_code = cc.country_code,
    country_name = cc.country_name
FROM
    countrycode cc
WHERE
    LEFT(all_day_cdr.served_msisdn, LENGTH(cc.country_code)) = cc.country_code;

------------------
    UPDATE all_day_cdr
SET
    country_code = cc.country_code,
    country_name = cc.network
FROM
    destination_network cc
WHERE
    LEFT(all_day_cdr.served_msisdn, LENGTH(cc.country_code)) = cc.country_code;
--------------------------------------------------------------------------------END CODE SQL -------------------
--------------------------------------------------------------REGROUPER PAR PAYS ET OPERATEURS--------------------------
SELECT
    country_name,
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 AS total_duration_minutes
FROM
    all_day_cdr
WHERE
    rounded_call_duration <> ''
GROUP BY
    country_name
HAVING
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 > 0
ORDER BY
    total_duration_minutes DESC;
---------------------------------------------------------------------------------FIN REGROUPEMENT---------------------------
---------------------ENTRANT TRAFIC----------------------------
SELECT
    country_name,
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 AS total_duration_minutes
FROM
    all_day_cdr
WHERE
    rounded_call_duration <> '' AND CAST(role_of_node AS INTEGER) = 1
GROUP BY
    country_name
HAVING
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 > 0
ORDER BY
    total_duration_minutes DESC;

    --------------------------------------------------END--------------------------------------

    ----------------------------Trafic sortant regroupement par country code et par pays ---------------
    UPDATE all_day_cdr
SET
    country_code_outband = cc.country_code,
    country_name_outband = cc.country_name
FROM
    countrycode cc
WHERE
    LEFT(all_day_cdr.other_msisdn, LENGTH(cc.country_code)) = cc.country_code;

---------------------------------------------------------End--------------------------------------------------------
SELECT
    country_name_outband,
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 AS total_duration_minutes
FROM
    all_day_cdr
WHERE
    rounded_call_duration <> '' AND CAST(role_of_node AS INTEGER) = 0
GROUP BY
country_name_outband
HAVING
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 > 0
ORDER BY
    total_duration_minutes DESC;
---------------------------------------------------------------------End

UPDATE all_day_cdr
SET
    number = SUBSTRING(served_msisdn FROM LENGTH(country_code) + 1);
--------------------------------fIN DE COUP DES NUMER SANS LE COUNTRY CODE --------------------
UPDATE all_day_cdr
SET
    network = dn.network
FROM
    destination_network dn
WHERE
    dn.country_code = all_day_cdr.country_code
    AND LEFT(all_day_cdr.number, LENGTH(dn.network_dest_code)) = dn.network_dest_code;


-----------------------------------------FIN ENVOI DES NETWORK NAME----------------------------------------------
SELECT
    network,
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 AS total_volume_minutes
FROM
    all_day_cdr
WHERE
rounded_call_duration <> '' AND CAST(role_of_node AS INTEGER) = 1
GROUP BY
   network
ORDER BY
    total_volume_minutes DESC;


    SELECT
    event_date,network,
    SUM(CAST(rounded_call_duration AS INTEGER)) / 60 AS total_volume_minutes
FROM
    (SELECT DISTINCT event_date, network, rounded_call_duration, role_of_node
     FROM all_day_cdr
     WHERE rounded_call_duration <> '' AND CAST(role_of_node AS INTEGER) = 1) AS unique_networks
GROUP BY
    network
ORDER BY
    network;
