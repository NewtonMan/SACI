<?php
require_once APP.'/Lib/eXorus/PhpMimeMailParser/Parser.php';

class DomainsShell extends AppShell {
    
    public $uses = array('Domain');
    public $ip = null;
    
    public function main(){
        
    }
    
    public function testDomain($d){
        $err = false;
        $mxs = @dns_get_record($d['Domain']['domain'], DNS_MX);
        $totalMX = count($mxs);
        if ($totalMX==1){
            $ip = gethostbyname($mxs[0]['target']);
            if ($ip!=$d['Server']['ip']){
                $err = true;
            }
        } else {
            $err = true;
        }
        $this->Domain->id = $d['Domain']['id'];
        if ($err){
            $this->Domain->saveField('dns_ok', null);
        } else {
            $this->Domain->saveField('dns_ok', 1);
        }
    }
    
    public function testDomains(){
        $this->ip = Configure::read('SACI-INSTANCE-IP');
        foreach ($this->Domain->find('all') as $d){
            if ($this->ip==$d['Server']['ip']) $this->testDomain($d);
        }
    }
    
}