<?php
class ServersController extends AppController {
    
    public $uses = array('Server');
    
    public function index(){
        $lista = $this->paginate('Server');
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
                $this->Server->create();
            } else {
                $this->Server->id = $id;
            }
            if ($this->Server->save($this->request->data)){
                $this->Session->setFlash('Ok, Server saved.', 'alerts/success');
                return $this->redirect('/servers');
            } else {
                $this->Session->setFlash('Error: Check the Form.', 'alerts/error');
            }
        } elseif (!empty($id)){
            $this->request->data = $this->Server->read(null, $id);
        } else {
            $this->request->data['Server']['protect_spam'] = 1;
            $this->request->data['Server']['protect_virus'] = 1;
            $this->request->data['Server']['protect_pishing'] = 1;
        }
        $servers = $this->Server->find('list');
        $this->set(compact('servers'));
        $this->render('form');
    }
    
}