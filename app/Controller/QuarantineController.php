<?php
class QuarantineController extends AppController {
    
    public $uses = array('Domain','DomainQuarantine','SpamMessage','SpamMessageTask');
    
    public function domains(){
        $this->Session->write('refer', $_SERVER['REQUEST_URI']);
        $lista = $this->paginate('Domain');
        $this->set(compact('lista'));
    }
    
    public function users($domain){
        $this->Session->write('refer', $_SERVER['REQUEST_URI']);
        $lista = $this->paginate('DomainQuarantine', array('DomainQuarantine.domain' => $domain));
        $this->set(compact('lista','domain'));
    }
    
    public function holdbox($domain, $user){
        $this->Session->write('refer', $_SERVER['REQUEST_URI']);
        $this->layout = 'quarantine';
        $lista = $this->paginate('SpamMessage', array('SpamMessage.domain' => $domain, 'SpamMessage.user' => $user));
        $this->set(compact('lista','domain','user'));
    }
    
    public function download($id){
        $msg = $this->SpamMessage->read(null, $id);
        $this->response->body($msg['SpamMessage']['msg_content']);
        $this->response->type('message/rfc822');
        $this->response->download('message.eml');
        return $this->response;
    }
    
    public function delete($id){
        $msg = $this->SpamMessage->read(null, $id);
        $this->SpamMessage->saveField('delete', 1);
        $refer = $this->Session->read('refer');
        return $this->redirect($refer);
    }
    
    public function deleteAll($domain, $user){
        $this->SpamMessage->updateAll(
            array(
                'SpamMessage.delete' => 1,
            ), 
            array(
                'SpamMessage.domain' => $domain,
                'SpamMessage.user' => $user,
                'SpamMessage.not_junk IS NULL',
                'SpamMessage.delete IS NULL',
            )
        );
        $refer = $this->Session->read('refer');
        return $this->redirect($refer);
    }
    
    public function notJunk($id){
        $msg = $this->SpamMessage->read(null, $id);
        $this->SpamMessage->saveField('not_junk', 1);
        $refer = $this->Session->read('refer');
        return $this->redirect($refer);
    }
    
}