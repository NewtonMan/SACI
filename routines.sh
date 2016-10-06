sudo /var/www/html/SACI/app/Console/cake apache
sudo /var/www/html/SACI/app/Console/cake mail_scanner
sudo /var/www/html/SACI/app/Console/cake postfix
sudo /var/www/html/SACI/app/Console/cake domains testDomains
sudo /var/www/html/SACI/app/Console/cake quarantine delete >> /var/log/saci.delete.log
sudo /var/www/html/SACI/app/Console/cake quarantine deliver >> /var/log/saci.delivery.log
sudo /var/www/html/SACI/app/Console/cake quarantine messages >> /var/log/saci.messages.log