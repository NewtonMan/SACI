<?php
class SpamMessage extends AppModel {
    
    public $useTable = 'spam_message';
    
    public $displayField = 'filename';
    
    public $virtualFields = array(
        'msg_size' => 'OCTET_LENGTH(SpamMessage.msg_content)',
    );
    
    public $belongsTo = array(
        'Server',
    );
    
}