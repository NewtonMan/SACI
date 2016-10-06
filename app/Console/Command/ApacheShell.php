<?php
require_once APP.'/Lib/eXorus/PhpMimeMailParser/Parser.php';

class ApacheShell extends AppShell {
    
    public function main(){
        $changes = false;
        if ($this->SACI()) $changes = true;
        
        if ($changes){
            exec("service httpd restart");
        }
    }
    
    public function SACI(){
        $apache_path = Configure::read('SACI-APACHE-CONFIG-FILE');
        $orig = md5_file($apache_path);
        $apache_main = Configure::read('SACI-APACHE-TEMPLATE');
        $configs = Configure::read();
        foreach ($configs as $k => $v){
            if (substr($k, 0, 5)=='SACI-'){
                $apache_main = str_replace($k, $v, $apache_main);
            }
        }
        $fp = fopen($apache_path, 'w');
        fputs($fp, $apache_main, strlen($apache_main));
        fclose($fp);
        return md5_file($apache_path)!==$orig;
    }
    
}