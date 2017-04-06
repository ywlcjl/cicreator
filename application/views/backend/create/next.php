<?php $this->load->view('backend/_header', array(
    'title' => '数据表明细',
    'onView' => 'create'
)); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="row">
    <div class="col-md-6">
        <p class="bd_title">数据表明细</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>create/index">返回上一步</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        <p>表格名: <?php echo $tableName; ?></p>
        <form action="<?php echo B_URL; ?>create/next" method="post">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>字段名</th>
                            <th>类型</th>
                            <th>描述</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result != null) : ?>
                            <?php foreach ($result as $value) : ?>
                                <tr class="bd_table_tr">
                                    <td><?php echo $value['COLUMN_NAME'] ?></td>
                                    <td><?php echo $value['COLUMN_TYPE']; ?></td>
                                    <td><?php echo $value['COLUMN_COMMENT']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <h3>自动生成代码文件列表</h3>
            <div>
                <?php $canPost = TRUE; //能够提交 ?>
                <?php if ($files) : ?>
                    <?php foreach ($files as $key => $value): ?>
                        <p><?php echo $value; ?> 
                            <?php if (file_exists($value)) : ?>
                                <?php $canPost = FALSE; ?>
                            <span class="bg-danger">错误, 文件已存在,你需要手动删除.</span>
                            <?php else : ?>
                            <span class="bg-success">准备就绪</span>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <input name="post" type="hidden" value="1">
            <input name="table" type="hidden" value="<?php echo cc_get_value(set_value('table')); ?>">
            <input class="btn btn-primary" type="submit" value="生成代码" onclick="return confirmAction();">
        </form>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>
