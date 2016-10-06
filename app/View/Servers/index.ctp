<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Servers Management</h1>
    </div>
    <table class="table table-bordered table-condensed">
        <tr>
            <th><?=$this->Paginator->sort('Server.ip', 'Server IP');?></th>
            <th><?=$this->Paginator->sort('Server.host', 'Server Host');?></th>
            <th>
                <a href="/servers/add" class="btn btn-success btn-xs">New Server</a>
            </th>
        </tr>
        <?foreach ($lista as $item){?>
        <tr>
            <td><?=$item['Server']['ip']?></td>
            <td><?=$item['Server']['host']?></td>
            <td>
                <div class="btn-group btn-group-xs">
                    <a href="/servers/edit/<?=$item['Server']['id']?>" class="btn btn-warning btn-xs" title="Edit this Server"><i class="fa fa-edit"></i></a>
                </div>
            </td>
        </tr>
        <? } ?>
    </table>
    <div class="panel-footer">
        <?=$this->Paginator->numbers(array('prev' => '<<', 'next' => '>>'));?>
    </div>
</div>
