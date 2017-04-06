<?php $this->load->view('backend/_header', array(
    'title' => '编辑日志',
    'onView' => 'cronLog',
)); ?>
<script type="text/javascript">
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">编辑日志</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>cron_log/index">返回列表</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo B_URL; ?>cron_log/save" method="post">
            <div class="form-group<?php if (form_error('type')) : ?> has-error<?php endif; ?>">
                <label for="input_type" class="control-label">类型</label>
                <select name="type" class="form-control" id="input_type">
                    <?php if ($types) : ?>
                        <?php foreach ($types as $key => $value) : ?>
                            <option value="<?php echo $key; ?>" <?php if ($row['type'] === (string) $key || set_value('type') === (string) $key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"><?php echo form_error('type'); ?></span>
            </div>

            <div class="form-group<?php if (form_error('memo')) : ?> has-error<?php endif; ?>">
                <label for="input_memo" class="control-label">日志</label>
                <input type="text" name="memo" id="input_memo" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('memo'), $row['memo']); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('memo'); ?></span>
            </div>

           <div class="form-group<?php if (form_error('admin_id')) : ?> has-error<?php endif; ?>">
                <label for="input_admin_id" class="control-label">管理员ID</label>
                <select name="admin_id" class="form-control">
                    <option value="">请选择</option>
                    <?php if ($admins != null) : ?>
                        <?php foreach ($admins as $value) : ?>
                            <option value="<?php echo $value['id']; ?>" <?php if ($row['admin_id'] === (string)$value['id'] || set_value('admin_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"><?php echo form_error('admin_id'); ?></span>
            </div>

            <div class="form-group<?php if (form_error('status')) : ?> has-error<?php endif; ?>">
                <label for="input_status" class="control-label">状态</label>
                <select name="status" class="form-control" id="input_status">
                    <?php if ($statuss) : ?>
                        <?php foreach ($statuss as $key => $value) : ?>
                            <option value="<?php echo $key; ?>" <?php if ($row['status'] === (string) $key || set_value('status') === (string) $key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"><?php echo form_error('status'); ?></span>
            </div>

            <input name="save" type="hidden" value="1" />
            <input name="id" type="hidden" value="<?php echo cc_get_value(set_value('id'), $row['id']); ?>" />
            <input class="btn btn-primary" type="submit" value="保存" />
        </form>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>

