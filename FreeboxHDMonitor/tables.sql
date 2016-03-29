BEGIN TRANSACTION;
CREATE TABLE `releves` (
	`rowid`	INTEGER,
	`rel_dt`	TEXT,
	`rel_version_firmware`	TEXT,
	`rel_protocole`	TEXT,
	`rel_debit_montant`	INTEGER,
	`rel_debit_descendant`	INTEGER,
	PRIMARY KEY(rowid)
);
COMMIT;
