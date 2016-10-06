<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">
            User's Quarantine: <?=$user?>(at)<?=$domain?>
            <a href="/quarantine/deleteAll/<?=$domain?>/<?=$user?>" class="btn btn-danger btn-xs pull-right" onclick="return confirm('WARNING: Do you really want to erase all messages in this Quarantine?');">Empty Quarantine</a>
        </h1>
    </div>
    <table class="table table-bordered table-condensed">
        <tr>
            <th><?=$this->Paginator->sort('SpamMessage.msg_subject', 'Subject');?></th>
            <th><?=$this->Paginator->sort('SpamMessage.msg_from', 'From');?></th>
            <th><?=$this->Paginator->sort('SpamMessage.msg_rcpt_to', 'To');?></th>
            <th><?=$this->Paginator->sort('SpamMessage.date', 'Hold Date');?> <?=$this->Paginator->sort('SpamMessage.msg_size', 'Size');?></th>
            <th width="120">Options</th>
        </tr>
        <?foreach ($lista as $x => $item){?>
        <tr class="<?=($item['SpamMessage']['delete']==1 ? 'bg-danger':($item['SpamMessage']['not_junk']==1 ? 'bg-info':''))?>">
            <td><?=str_replace(array('<','>'), array('[',']'), $item['SpamMessage']['msg_subject'])?></td>
            <td><?=str_replace(array('<','>'), array('[',']'), $item['SpamMessage']['msg_from'])?></td>
            <td><?=str_replace(array('<','>'), array('[',']'), $item['SpamMessage']['msg_rcpt_to'])?></td>
            <td>
                <?=$item['SpamMessage']['date']?><br/>
                <?=$item['SpamMessage']['msg_size']?>
            </td>
            <td nowrap="nowrap">
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-xs" onclick="if (confirm('WARNING: Marking as not Junk will lead SACI to learn this message as not Spam, do you want to proceed?')) { window.location.href='/quarantine/notJunk/<?=$item['SpamMessage']['id']?>' } else { return false };">Not Junk</button>
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">More Options</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="/quarantine/download/<?=$item['SpamMessage']['id']?>" onclick="return confirm('WARNING: SACI cannot protect your Privacy if you choose to Download this message, proceed anyway?');"><i class="fa fa-download"></i> Download Only</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/quarantine/notJunk/<?=$item['SpamMessage']['id']?>" onclick="return confirm('WARNING: Marking as not Junk will lead SACI to learn this message as not Spam, do you want to proceed?');"><i class="fa fa-envelope"></i> Mark as Not Junk</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <? } ?>
    </table>
    <div class="panel-footer">
        <?=$this->Paginator->numbers(array('prev' => '<<', 'next' => '>>'));?>
    </div>
</div>
