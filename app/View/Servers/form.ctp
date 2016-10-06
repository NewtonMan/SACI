<?=$this->Form->create('Server')?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Server Form</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <?=$this->Form->input('Server.ip', array('type' => 'text', 'label' => 'Server IP', 'class' => 'form-control', 'placeholder' => 'Type here the server IP Address'))?>
                <?=$this->Form->input('Server.host', array('type' => 'text', 'label' => 'Server HOST', 'class' => 'form-control', 'placeholder' => 'Type here the server Hostname'))?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <?=$this->Form->submit('Save', array('class' => 'btn btn-success'));?>
    </div>
</div>
<?=$this->Form->end();?>