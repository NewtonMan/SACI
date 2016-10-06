<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
    <head>
        <title><?php echo $this->fetch('title'); ?></title>
        <style>
            body {
                background-color: #cccccc;
            }
            
            body table.container {
                background-color: aliceblue;
                width: 600px;
                margin: 20px auto;
            }
            
            body table.container thead tr th,body table.container tbody tr td,body table.container tfoot tr td {
                border: solid 1px appworkspace;
            }
            
            body table.container thead tr {
                background-color: #009933;
            }
            
            body table.container tbody tr,body table.container tfoot tr {
                background-color: #FFF;
            }
            
            body table.container thead tr th, body table.container thead tr th h1 {
                color: white;
            }
            
            body table.container tbody tr td.content {
                padding: 30px;
            }
        </style>
    </head>
    <body>
        <table class="container" border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="30%"><img src="cid:saci-logo"></th>
                    <th width="70%">
                        <h1>SACI - Email Gateway Advisor</h1>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="content">
                        <?php echo $this->fetch('content');?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        This is an automatic generated message, please donnot reply this kind of messages, if you have any questions contact your supoort team for e-mail services.
                    </td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>