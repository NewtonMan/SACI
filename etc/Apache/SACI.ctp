<VirtualHost *:80>
    ServerName SACI-DB-HOST
    DocumentRoot /var/www/html/SACI/app/webroot
    ErrorLog saci-error.log
    CustomLog saci-access.log combined
</VirtualHost>