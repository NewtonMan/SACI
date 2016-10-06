<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Domains Management</h1>
    </div>
    <table class="table table-bordered table-condensed">
        <tr>
            <th><?=$this->Paginator->sort('Domain.domain', 'Domain Name');?></th>
            <th><?=$this->Paginator->sort('Domain.users', 'Quarantines');?></th>
            <th><?=$this->Paginator->sort('Domain.spams', 'Spam Holded');?></th>
            <th><?=$this->Paginator->sort('Domain.mta_host', 'MTA');?></th>
            <th>
                <a href="/domains/add" class="btn btn-success btn-xs">New Domain</a>
            </th>
        </tr>
        <?foreach ($lista as $item){?>
        <tr class="bg-<?=($item['Domain']['dns_ok']==1 ? 'success':'danger')?>">
            <td><?=$item['Domain']['domain']?></td>
            <td><?=$item['Domain']['users']?></td>
            <td><?=$item['Domain']['spams']?></td>
            <td><?=$item['Domain']['mta_host']?></td>
            <td>
                <div class="btn-group btn-group-xs">
                    <a href="/quarantine/users/<?=$item['Domain']['domain']?>" class="btn btn-default btn-xs" title="Show Domains Spam Quarantines"><i class="fa fa-users"></i></a>
                    <!--a href="/reports/<?=$item['Domain']['domain']?>" class="btn btn-info btn-xs" title="Show Reports for This Domain"><i class="fa fa-area-chart"></i></a-->
                    <a href="/domains/edit/<?=$item['Domain']['id']?>" class="btn btn-warning btn-xs" title="Edit this Domain"><i class="fa fa-edit"></i></a>
                </div>
            </td>
        </tr>
        <? } ?>
    </table>
    <div class="panel-footer">
        <?=$this->Paginator->numbers(array('prev' => '<<', 'next' => '>>'));?>
    </div>
</div>
