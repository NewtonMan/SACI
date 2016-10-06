<?php
require_once APP.'/Lib/eXorus/PhpMimeMailParser/Parser.php';

class PostfixShell extends AppShell {
    
    public $uses = array('Domain', 'DomainWhitelist');
    public $ip = null;
    
    public function main(){
        $changes = false;
        if ($this->transport()) $changes = true;
        if ($this->relay_rcpt_maps()) $changes = true;
        if ($this->header_checks()) $changes = true;
        if ($this->mainConfig()) $changes = true;
        
        if ($changes){
            exec("service postfix restart");
        }
    }
    
    public function mainConfig(){
        $domains = array_values($this->Domain->find('list'));
        Configure::write('SACI-DOMAINS', implode(', ', $domains));
        $postfix_path = Configure::read('SACI-POSTFIX-CONFIG-FILE');
        $orig = md5_file($postfix_path);
        $postfix_main = Configure::read('SACI-POSTFIX-MAIN-TEMPLATE');
        $configs = Configure::read();
        foreach ($configs as $k => $v){
            if (substr($k, 0, 5)=='SACI-'){
                $postfix_main = str_replace($k, $v, $postfix_main);
            }
        }
        $fp = fopen($postfix_path, 'w');
        fputs($fp, $postfix_main, strlen($postfix_main));
        fclose($fp);
        return md5_file($postfix_path)!==$orig;
    }
    
    public function transport(){
        $configFilePath = Configure::read('SACI-POSTFIX-TRANSPORT');
        $orig = md5_file($configFilePath);
        $configFileContent = "";
        foreach ($this->Domain->find('all') as $d){
            $configFileContent .= "{$d['Domain']['domain']}\t\tsmtp:{$d['Domain']['mta_host']}\n";
        }
        $fp = fopen($configFilePath, 'w');
        fputs($fp, $configFileContent, strlen($configFileContent));
        fclose($fp);
        if (md5_file($configFilePath)!==$orig) exec("postmap {$configFilePath}");
        return md5_file($configFilePath)!==$orig;
    }
    
    public function relay_rcpt_maps(){
        $configFilePath = Configure::read('SACI-POSTFIX-RELAY-RCPT-MAPS');
        $orig = md5_file($configFilePath);
        $configFileContent = "";
        foreach ($this->Domain->find('all') as $d){
            $configFileContent .= "@{$d['Domain']['domain']}\t\tx\n";
        }
        $fp = fopen($configFilePath, 'w');
        fputs($fp, $configFileContent, strlen($configFileContent));
        fclose($fp);
        if (md5_file($configFilePath)!==$orig) exec("postmap {$configFilePath}");
        return md5_file($configFilePath)!==$orig;
    }
    
    public function header_checks(){
        $configFilePath = Configure::read('SACI-POSTFIX-HEADER-CHECKS');
        $orig = md5_file($configFilePath);
        $configFileContent = Configure::read('SACI-POSTFIX-HEADER-TEMPLATE');
        $fp = fopen($configFilePath, 'w');
        fputs($fp, $configFileContent, strlen($configFileContent));
        fclose($fp);
        return md5_file($configFilePath)!==$orig;
    }
    
}