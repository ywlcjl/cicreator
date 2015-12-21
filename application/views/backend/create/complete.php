<?php $this->load->view('backend/_header', array('onView' => 'create')); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="row">
    <div class="col-md-6">
        <p class="bd_title">自动生成代码完成</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>create/index">返回开始</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        <p><?php echo $tableName; ?>表的文件已创建完成</p>
        <p>您需要手动添加菜单链接, views/backend/_header.php</p>
        <p><a href="<?php echo B_URL.$tableName; ?>/">查看页面</a> </p>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>
