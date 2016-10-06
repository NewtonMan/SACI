<?$this->start('script')?>
function ui_setup_helper(){
    var _domain = $('#DomainDomain').val();
    if (_domain=='') _domain = 'examplo.com';
    var _server = $('#DomainServerId option:selected').text();
    $('#server').html(_server);
    $('#saci-domain').html('saci.'+_domain);
}
<?$this->end()?>
<?$this->start('script-onload')?>
$('#DomainServerId').change(function(){ ui_setup_helper(); }).change();
$('#DomainDomain').change(function(){ ui_setup_helper(); }).change();
<?$this->end()?>
<?=$this->Form->create('Domain')?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Domain Form</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <?=$this->Form->input('Domain.server_id', array('label' => 'Delegate to this SACI Server', 'class' => 'form-control', 'empty' => false))?>
                <?=$this->Form->input('Domain.domain', array('type' => 'text', 'label' => 'Domain Name', 'class' => 'form-control', 'placeholder' => 'Type here the domain name'))?>
                <?=$this->Form->input('Domain.mta_host', array('type' => 'text', 'class' => 'form-control', 'label' => 'Delivery to this MTA', 'placeholder' => 'Type here the old MX server'))?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">
                <?=$this->Form->input('Domain.active', array('label' => 'Domain Active'))?>
            </div>
            <div class="col-xs-3">
                <?=$this->Form->input('Domain.protect_spam', array('label' => 'Protect from Spam'))?>
            </div>
            <div class="col-xs-3">
                <?=$this->Form->input('Domain.protect_virus', array('label' => 'Protect from Vírus'))?>
            </div>
            <div class="col-xs-3">
                <?=$this->Form->input('Domain.protect_pishing', array('label' => 'Protect from Pishing'))?>
            </div>
        </div>
        <div class="well well-sm">
            <h3>SACI - DNS Setup</h3>
            <ol>
                <li>On DNS zone create a CNAME registry named "<strong>saci</strong>" and points to <strong id="server"></strong></li>
                <li>Remove all MX records from this DNS zone</li>
                <li>Create one MX, prio 10, that points to <strong id="saci-domain"></strong></li>
                <li>Now SACI will feed the <strong>MTA</strong> with cleanup E-mail</li>
            </ol>
            <hr/>
            <h3>Advantages</h3>
            <ul>
                <li>free of memoryeater server wrapers</li>
                <li>no rbl checks</li>
                <li>users take care of their junk</li>
            </ul>
        </div>
    </div>
    <div class="panel-footer">
        <?=$this->Form->submit('Save', array('class' => 'btn btn-success'));?>
    </div>
</div>
<?=$this->Form->end();?>