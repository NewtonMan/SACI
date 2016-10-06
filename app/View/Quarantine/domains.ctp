<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Quarantine by Domains</h1>
    </div>
    <table class="table table-bordered table-condensed table-hover">
        <tr>
            <th><?=$this->Paginator->sort('Domain.domain', 'Domain Name');?></th>
            <th><?=$this->Paginator->sort('Domain.users', 'Quarantines');?></th>
            <th><?=$this->Paginator->sort('Domain.spams', 'Spam Holded');?></th>
        </tr>
        <?foreach ($lista as $item){?>
        <tr onmouseover="this.style.cursor='pointer';" onclick="window.location.href='/quarantine/users/<?=$item['Domain']['domain']?>';">
            <td><?=$item['Domain']['domain']?></td>
            <td><?=$item['Domain']['users']?></td>
            <td><?=$item['Domain']['spams']?></td>
        </tr>
        <? } ?>
    </table>
    <div class="panel-footer">
        <?=$this->Paginator->numbers(array('prev' => '<<', 'next' => '>>'));?>
    </div>
</div>
