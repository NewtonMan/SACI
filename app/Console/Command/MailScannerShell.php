<?php
require_once APP.'/Lib/eXorus/PhpMimeMailParser/Parser.php';

class MailScannerShell extends AppShell {
    
    public $uses = array('Domain', 'DomainWhitelist');
    public $ip = null;
    
    public function main(){
        $changes = false;
        if ($this->bounces()) $changes = true;
        if ($this->maxMessageSize()) $changes = true;
        if ($this->whitelists()) $changes = true;
        if ($this->mainConfig()) $changes = true;
        
        if ($changes){
            exec("service mailscanner restart");
        }
    }
    
    public function mainConfig(){
        $mailscanner_path = Configure::read('SACI-MAILSCANNER-CONFIG-FILE');
        $orig = md5_file($mailscanner_path);
        $mailscanner_main = Configure::read('SACI-MAILSCANNER-MAIN-TEMPLATE');
        $configs = Configure::read();
        foreach ($configs as $k => $v){
            if (substr($k, 0, 5)=='SACI-'){
                $mailscanner_main = str_replace($k, $v, $mailscanner_main);
            }
        }
        $fp = fopen($mailscanner_path, 'w');
        fputs($fp, $mailscanner_main, strlen($mailscanner_main));
        fclose($fp);
        return md5_file($mailscanner_path)!==$orig;
    }
    
    public function bounces(){
        $ruleFilePath = Configure::read('SACI-MAILSCANNER-RULES-DIR');
        $orig = md5_file($ruleFilePath . DS . 'bounce.rules');
        $ruleFileContent = "FromOrTo:\tdefault\tno";
        $fp = fopen($ruleFilePath . DS . 'bounce.rules', 'w');
        fputs($fp, $ruleFileContent, strlen($ruleFileContent));
        fclose($fp);
        return md5_file($ruleFilePath . DS . 'bounce.rules')!==$orig;
    }
    
    public function maxMessageSize(){
        $ruleFilePath = Configure::read('SACI-MAILSCANNER-RULES-DIR');
        $orig = md5_file($ruleFilePath . DS . 'max.message.size.rules');
        $ruleFileContent = "FromOrTo:\tdefault\t0";
        $fp = fopen($ruleFilePath . DS . 'max.message.size.rules', 'w');
        fputs($fp, $ruleFileContent, strlen($ruleFileContent));
        fclose($fp);
        return md5_file($ruleFilePath . DS . 'max.message.size.rules')!==$orig;
    }
    
    public function whitelists(){
        $ruleFilePath = Configure::read('SACI-MAILSCANNER-RULES-DIR');
        $orig = md5_file($ruleFilePath . DS . 'spam.whitelist.rules');
        $ruleFileContent = '';
        foreach ($this->DomainWhitelist->find('all') as $dw){
            $ruleFileContent .= "FromOrTo:\t*@{$dw['DomainWhitelist']['whitelist']}\tyes\n";
        }
        $ruleFileContent .= "FromOrTo:\tdefault\tno";
        $fp = fopen($ruleFilePath . DS . 'spam.whitelist.rules', 'w');
        fputs($fp, $ruleFileContent, strlen($ruleFileContent));
        fclose($fp);
        return md5_file($ruleFilePath . DS . 'spam.whitelist.rules')!==$orig;
    }
    
}