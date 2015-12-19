<?php $this->load->view('backend/_header', array('onView' => 'create')); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="main">
    <div class="mainLeft">
        <?php $this->load->view('backend/_menu', array('onView' => 'create')); ?>
    </div>
    <div class="mainRight">
        <div class="block1">
            <div class="titleStyle1">
                生成代码完成<span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>create" class="white">返回开始</a></span>
            </div>
            <div class="contentBlock1">
                <?php if ($message) : ?>
                    <div class="errorMessage">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>


                <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?php echo $tableName; ?>表的文件已创建完成</th>
                        <td>您需要手动添加菜单链接, views/backend/_menu.php</td>
                        <td><a href="<?php echo B_URL.$tableName; ?>/" target="_blank">查看页面</a> </td>
                    </tr>
                </table>


            </div>
        </div>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>