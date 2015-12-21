<?php $this->load->view('backend/_header', array('onView' => 'admin')); ?>
<script type="text/javascript">
    $(document).ready(function () {
    });
</script>


<div class="row">
    <div class="col-md-6">
        <p class="bd_title">权限分配</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>admin/index">返回列表</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo B_URL; ?>admin/addPermission" method="post">
            <h5>管理员: <?php echo $admin['username']; ?> 的权限</h5>

            <?php if ($permissionList != null) : ?>
                <div class="checkbox">
                    <?php foreach ($permissionList as $value) : ?>
                        <label>
                            <input type="checkbox" name="permission[]" id="checkbox<?php echo $value['id']; ?>" value="<?php echo $value['id']; ?>" <?php if (in_array($value['id'], $permissionArray)) : ?>checked="checked"<?php endif; ?> /> 
                            <?php echo $value['name']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <input name="id" type="hidden" value="<?php echo $this->backend_lib->getValue(set_value('id'), $admin['id']); ?>" />
            <input name="add" type="hidden" value="1" />
            <input class="btn btn-primary" type="submit" value="保存" />
        </form>
        <p>&nbsp;</p>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>