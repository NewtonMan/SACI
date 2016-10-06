BEGIN
    IF OLD.not_junk IS NULL AND NEW.not_junk=1 THEN
        INSERT IGNORE INTO domain_domain_whitelists (`domain`,`whitelist`) VALUES (NEW.`domain`, NEW.`from_domain`);
    END IF;
END