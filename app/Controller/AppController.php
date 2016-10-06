<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class AppController extends Controller {

    public $helpers = array(
        'Html' => array(
            'className' => 'Bootstrap3.BootstrapHtml'
        ),
        'Form' => array(
            'className' => 'Bootstrap3.BootstrapForm'
        ),
        'Modal' => array(
            'className' => 'Bootstrap3.BootstrapModal'
        ),
        'Paginator' => array(
            'className' => 'Bootstrap3.BootstrapPaginator'
        ),
    );
    
    public $paginate = array(
        'paramType' => 'querystring',
        'limit' => 10,
    );

}
