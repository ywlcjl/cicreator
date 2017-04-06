<?php $this->load->view('backend/_header', array(
    'title' => '编辑分类',
    'onView' => 'articleCategory'
)); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">编辑分类</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>article_category/index">返回列表</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo B_URL; ?>article_category/save" method="post">
            <div class="form-group<?php if (form_error('name')) : ?> has-error<?php endif; ?>">
                <label for="input_name" class="control-label">分类名称</label>
                <input type="text" name="name" id="input_name" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('name'), $row['name']); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('name'); ?></span>
            </div>

            <div class="form-group<?php if (form_error('parent_id')) : ?> has-error<?php endif; ?>">
                <label for="input_parent_id" class="control-label">父分类</label>
                <select name="parent_id" class="form-control" id="input_parent_id">
                    <option value="0">未分类</option>
                    <?php if ($categorys != null) : ?>
                        <?php foreach ($categorys as $category) : ?>
                            <option value="<?php echo $category['id']; ?>" <?php if ($row['parent_id'] == $category['id'] || set_value('parent_id') == $category['id']) : ?>selected="selected"<?php endif; ?>><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"><?php echo form_error('parent_id'); ?></span>
            </div>
                        
            <div class="form-group<?php if (form_error('hop_link')) : ?> has-error<?php endif; ?>">
                <label for="input_hop_link" class="control-label">跳转链接</label>
                <input type="text" name="hop_link" id="input_hop_link" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('hop_link'), $row['hop_link']); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('hop_link'); ?></span>
            </div>
            
            <div class="form-group<?php if (form_error('sort')) : ?> has-error<?php endif; ?>">
                <label for="input_sort" class="control-label">排序</label>
                <input type="text" name="sort" id="input_sort" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('sort'), $row['sort'], '0'); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('sort'); ?></span>
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