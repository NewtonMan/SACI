DROP VIEW IF EXISTS domains_quarantines;
CREATE VIEW domains_quarantines AS SELECT `domain`, `user`, COUNT(*) as spams FROM `spam_message` SM1 GROUP BY `domain`, `user`