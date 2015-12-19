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
                生成代码明细<span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>create" class="white">返回上一步</a></span>
            </div>
            <div class="contentBlock1">
                <?php if ($message) : ?>
                    <div class="errorMessage">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo B_URL; ?>create/next" method="post">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>表格名:</th>
                            <td><?php echo $tableName; ?></td>
                            <td></td>
                        </tr>
                        <?php if ($result) : ?>
                            <?php foreach ($result as $key => $value) : ?>
                                <tr>
                                    <th><?php echo $value['COLUMN_NAME'] ?>:</th>
                                    <td><?php echo $value['COLUMN_TYPE']; ?></td>
                                    <td><?php echo $value['COLUMN_COMMENT']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <th>&nbsp;</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>生成对应的文件列表</th>
                            <td>
                                <?php $canPost = TRUE; //能够提交 ?>
                                <?php if ($files) : ?>
                                    <?php foreach ($files as $key => $value): ?>
                                        <p><?php echo $value; ?> 
                                            <?php if(file_exists($value)) :?>
                                            <?php $canPost = FALSE; ?>
                                            错误, 文件已存在,你需要手动删除.
                                            <?php else : ?>
                                            准备就绪
                                            <?php endif; ?>
                                        </p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <input name="post" type="hidden" value="1" />
                    <input name="table" type="hidden" value="<?php echo $this->backend_lib->getValue(set_value('table')); ?>" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="生成代码" onclick="return confirmAction();" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>