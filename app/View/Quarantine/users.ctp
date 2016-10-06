<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Quarantine by Users at <?=$domain?></h1>
    </div>
    <table class="table table-bordered table-condensed table-hover">
        <tr>
            <th><?=$this->Paginator->sort('DomainQuarantine.domain', 'Domain');?></th>
            <th><?=$this->Paginator->sort('DomainQuarantine.user', 'User');?></th>
            <th><?=$this->Paginator->sort('DomainQuarantine.spams', 'Spam Holded');?></th>
        </tr>
        <?foreach ($lista as $item){?>
        <tr onmouseover="this.style.cursor='pointer';" onclick="window.location.href='/quarantine/holdbox/<?=$item['DomainQuarantine']['domain']?>/<?=$item['DomainQuarantine']['user']?>';">
            <td><?=$item['DomainQuarantine']['domain']?></td>
            <td><?=$item['DomainQuarantine']['user']?></td>
            <td><?=$item['DomainQuarantine']['spams']?></td>
        </tr>
        <? } ?>
    </table>
    <div class="panel-footer">
        <?=$this->Paginator->numbers(array('prev' => '<<', 'next' => '>>'));?>
    </div>
</div>
