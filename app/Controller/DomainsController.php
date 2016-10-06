<?php
class DomainsController extends AppController {
    
    public $uses = array('Domain','Server');
    
    public function index(){
        $lista = $this->paginate('Domain');
        $this->set(compact('lista'));
    }
    
    public function add(){
        $this->form();
    }
    
    public function edit($id){
        $this->form($id);
    }
    
    public function form($id=null){
        if ($this->request->is('post') || $this->request->is('put')){
            if (empty($id)){
                $this->Domain->create();
            } else {
                $this->Domain->id = $id;
            }
            if ($this->Domain->save($this->request->data)){
                $this->Session->setFlash('Ok, Domain saved.', 'alerts/success');
                return $this->redirect('/domains');
            } else {
                $this->Session->setFlash('Error: Check the Form.', 'alerts/error');
            }
        } elseif (!empty($id)){
            $this->request->data = $this->Domain->read(null, $id);
        } else {
            $this->request->data['Domain']['protect_spam'] = 1;
            $this->request->data['Domain']['protect_virus'] = 1;
            $this->request->data['Domain']['protect_pishing'] = 1;
        }
        $servers = $this->Server->find('list');
        $this->set(compact('servers'));
        $this->render('form');
    }
    
}