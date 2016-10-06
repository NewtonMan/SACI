<?php
require_once APP.'/Lib/eXorus/PhpMimeMailParser/Parser.php';

class QuarantineShell extends AppShell {
    
    public $uses = array('Server','SpamMessage','SpamMessageTask','DomainQuarantine');
    
    public function main(){
        
    }
    
    private function sanitizar_utf8($texto) {
        $saida = '';

        $i = 0;
        $len = strlen($texto);
        while ($i < $len) {
            $char = $texto[$i++];
            $ord = ord($char);

            // Primeiro byte 0xxxxxxx: simbolo ascii possui 1 byte
            if (($ord & 0x80) == 0x00) {

                // Se e' um caractere de controle
                if (($ord >= 0 && $ord <= 31) || $ord == 127) {

                    // Incluir se for: tab, retorno de carro ou quebra de linha
                    if ($ord == 9 || $ord == 10 || $ord == 13) {
                        $saida .= $char;
                    }

                    // Simbolo ASCII
                } else {
                    $saida .= $char;
                }

                // Primeiro byte 110xxxxx ou 1110xxxx ou 11110xxx: simbolo possui 2, 3 ou 4 bytes
            } else {

                // Determinar quantidade de bytes analisando os bits da esquerda para direita
                $bytes = 0;
                for ($b = 7; $b >= 0; $b--) {
                    $bit = $ord & (1 << $b);
                    if ($bit) {
                        $bytes += 1;
                    } else {
                        break;
                    }
                }

                switch ($bytes) {
                    case 2: // 110xxxxx 10xxxxxx
                    case 3: // 1110xxxx 10xxxxxx 10xxxxxx
                    case 4: // 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
                        $valido = true;
                        $saida_padrao = $char;
                        $i_inicial = $i;
                        for ($b = 1; $b < $bytes; $b++) {
                            if (!isset($texto[$i])) {
                                $valido = false;
                                break;
                            }
                            $char_extra = $texto[$i++];
                            $ord_extra = ord($char_extra);

                            if (($ord_extra & 0xC0) == 0x80) {
                                $saida_padrao .= $char_extra;
                            } else {
                                $valido = false;
                                break;
                            }
                        }
                        if ($valido) {
                            $saida .= $saida_padrao;
                        } else {
                            $saida .= ($ord < 0x7F || $ord > 0x9F) ? utf8_encode($char) : '';
                            $i = $i_inicial;
                        }
                        break;
                    case 1:  // 10xxxxxx: ISO-8859-1
                    default: // 11111xxx: ISO-8859-1
                        $saida .= ($ord < 0x7F || $ord > 0x9F) ? utf8_encode($char) : '';
                        break;
                }
            }
        }
        return $saida;
    }
    
    public function message($filename){
        $filename = str_replace('//', '/', $filename);// IF SOMEONE TYPE A FINAL SLASH THE EXPLODE BELOW BREAKS
        $server = Configure::read('SACI-INSTANCE-IP');
        $sid = $this->Server->find('first', array('conditions' => array(
            'Server.ip' => $server,
        )));
        $server_id = $sid['Server']['id'];
        $existe = $this->SpamMessage->find('count', array('conditions' => array(
            'SpamMessage.server_id' => $server_id,
            'SpamMessage.filename' => $filename,
        )));
        if ($existe==0){
            $folder_parts = explode('/', $filename);
            $spam_message = array(
                'server_id' => $server_id,
                'filename' => $filename,
                'domain' => $folder_parts[5],
                'user' => $folder_parts[6],
                'date' => substr($folder_parts[7], 0, 4) . '-' . substr($folder_parts[7], 4, 2) . '-' . substr($folder_parts[7], 6, 2),
            );
            exec("postcat $filename", $msg);
            foreach ($msg as $msg_line){
                $ap = explode(': ', $msg_line, 2);
                if (is_array($ap)){
                    switch ($ap[0]){
                        case 'sender':
                            $spam_message['msg_sender'] = trim($ap[1]);
                            $pair = explode('@', $spam_message['msg_sender']);
                            $spam_message['from_domain'] = $pair[1];
                            break;
                        case 'original_recipient':
                            $spam_message['msg_orig_to'] = trim($ap[1]);
                            break;
                        case 'recipient':
                            $spam_message['msg_rcpt_to'] = trim($ap[1]);
                            break;
                        case 'named_attribute':
                            $ap[1] = trim($ap[1]);
                            $pair = explode('=', $ap[1]);
                            if ($pair[0]=='client_address'){
                                $spam_message['from_client_address'] = $pair[1];
                            } elseif ($pair[0]=='reverse_client_name'){
                                $spam_message['from_client_address_reverse'] = $pair[1];
                            }
                            break;
                    }
                }
            }
            $msg_queue = implode("\n", $msg);
            $msg_parts = explode('***', $msg_queue);
            $message = trim($this->sanitizar_utf8($msg_parts[4]));
            $Parser = new eXorus\PhpMimeMailParser\Parser();
            $Parser->setText($message);
            $mid = $Parser->getHeader('message-id');
            $content_type = $Parser->getHeader('content-type');
            $to = $Parser->getHeader('to');
            $from = $Parser->getHeader('from');
            $subject = $Parser->getHeader('subject');
            $spam_message['msg_id'] = $this->sanitizar_utf8($mid);
            $spam_message['msg_content_type'] = $this->sanitizar_utf8($content_type);
            $spam_message['msg_from'] = $this->sanitizar_utf8($from);
            $spam_message['msg_rcpt_to'] = $this->sanitizar_utf8($to);
            $spam_message['msg_subject'] = $this->sanitizar_utf8($subject);
            $spam_message['msg_content'] = $message;
            if (empty($spam_message['from_domain'])){
                $fp = explode("@", $spam_message['msg_from']);
                $spam_message['from_domain'] = $fp[1];
            }
            
            if (empty($spam_message['msg_sender']) && empty($spam_message['msg_orig_to'])) return;
            $this->SpamMessage->create();
            $this->SpamMessage->save($spam_message);
        }
    }
    
    public function messages(){
        $quarantineFolder = Configure::read('SACI-MAILSCANNER-QUARANTINE');
        exec("ls -la {$quarantineFolder}/*/*/*/* | awk '{print $9}'", $lista);
        foreach ($lista as $filename){
            $this->message($filename);
        }
    }
    
    public function deliver(){
        $serverIP = Configure::read('SACI-INSTANCE-IP');
        $sa_prefs = Configure::read('SACI-MAILSCANNER-SA-PREFS');
        $moveTo = Configure::read('SACI-MAILSCANNER-RELEASE-FOLDER');
        foreach ($this->SpamMessage->find('all', array('conditions' => array(
            'Server.ip' => $serverIP,
            'SpamMessage.not_junk' => 1,
        ))) as $smsg){
            $cmd = "line_count=`postcat {$smsg['SpamMessage']['filename']} | wc -l` && postcat {$smsg['SpamMessage']['filename']} | tail -$((\$line_count-6)) | head -$((\$line_count-10)) | sa-learn --ham -p $sa_prefs && cp -rpv {$smsg['SpamMessage']['filename']} {$moveTo}/".pathinfo($smsg['SpamMessage']['filename'], PATHINFO_FILENAME) . " && chmod 755 {$moveTo}/".pathinfo($smsg['SpamMessage']['filename'], PATHINFO_FILENAME);
            exec($cmd, $saida);
            $this->SpamMessage->id = $smsg['SpamMessage']['id'];
            $this->SpamMessage->delete($smsg['SpamMessage']['id']);
        }
    }
    
    public function delete(){
        $serverIP = Configure::read('SACI-INSTANCE-IP');
        foreach ($this->SpamMessage->find('all', array('conditions' => array(
            'Server.ip' => $serverIP,
            'SpamMessage.delete' => 1,
        ))) as $smsg){
            $cmd = "rm -rfv {$smsg['SpamMessage']['filename']}";
            exec($cmd, $saida);
            $this->SpamMessage->id = $smsg['SpamMessage']['id'];
            $this->SpamMessage->delete($smsg['SpamMessage']['id']);
            $this->out($saida);
        }
    }
    
    public function advisor(){
        $lista = $this->DomainQuarantine->find('all');
        foreach ($lista as $item){
            $domain = $item['DomainQuarantine']['domain'];
            $user = $item['DomainQuarantine']['user'];
            if (!filter_var("{$user}@{$domain}", FILTER_VALIDATE_EMAIL)){
                $this->SpamMessage->deleteAll(array(
                    'SpamMessage.domain' => $domain,
                    'SpamMessage.user' => $user,
                ));
                continue;
            }
            $messages = $item['DomainQuarantine']['spams'];
            $url = "http://" . Configure::read('SACI-DB-HOST') . "/quarantine/holdbox/{$domain}/{$user}";
            $Email = new CakeEmail();
            $Email->template('messages_holded', 'default')
                ->emailFormat('both')
                ->to("{$user}@{$domain}")
                ->from("{$user}+no-reply-saci@{$domain}")
                ->subject('Mensagens bloqueadas pelo SACI / SACI holded messages')
                ->viewVars(array(
                    'total_messages' => $messages,
                    'domain' => $domain,
                    'user' => $user,
                    'url' => $url,
                ))
                ->attachments(array(
                    'logo_50x50.png' => array(
                        'file' => WWW_ROOT . 'img' . DS . 'logo_50x50.png',
                        'mimetype' => 'image/png',
                        'contentId' => 'saci-logo'
                    ),
                ))
                ->send();
        }
    }
    
}