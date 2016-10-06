<?php
class Server extends AppModel {
    
    public $useTable = 'servers';
    
    public $displayField = 'host';
    
    public $hasMany = array(
        'Domain',
    );
    
}