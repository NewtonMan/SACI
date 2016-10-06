BEGIN
INSERT INTO `log_domain_user_spam_day` (`server_id`, `domain`, `user`, `day`, `total`) VALUES (NEW.server_id,NEW.domain,NEW.user,NEW.date,1) ON DUPLICATE KEY UPDATE total = total + 1;
INSERT INTO `log_domain_spam_day` (`server_id`, `domain`, `day`, `total`) VALUES (NEW.server_id,NEW.domain,NEW.date,1) ON DUPLICATE KEY UPDATE total = total + 1;
INSERT INTO `log_spam_from_ip` (`ip_address`, `reverse_dns`, `spams`, `last_time`) VALUES (NEW.from_client_address,NEW.from_client_address_reverse,1,NOW()) ON DUPLICATE KEY UPDATE last_time=NOW(), spams = spams + 1;
INSERT INTO `log_spam_from_domain` (`domain`, `spams`, `last_time`) VALUES (NEW.from_domain,1,NOW()) ON DUPLICATE KEY UPDATE last_time=NOW(), spams = spams + 1;
END