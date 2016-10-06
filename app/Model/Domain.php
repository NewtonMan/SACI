<?php
class Domain extends AppModel {
    
    public $useTable = 'domains';
    
    public $displayField = 'domain';
    
    public $belongsTo = array(
        'Server',
    );
    
    public $virtualFields = array(
        'users' => 'SELECT COUNT(*) FROM domains_quarantines WHERE domain=Domain.domain',
        'spams' => 'SELECT COUNT(*) FROM spam_message WHERE domain=Domain.domain',
    );
    
    public $validate = array(
        'domain' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'Type the domain name here',
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'This Domain name is already in SACI.',
            ),
        ),
    );
    
}