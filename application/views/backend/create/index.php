<?php $this->load->view('backend/_header', array(
    'title' => '代码自动生成',
    'onView' => 'create'
)); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="row">
    <div class="col-md-6">
        <p class="bd_title">代码自动生成</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo B_URL; ?>/create/index" method="post">
            <div class="form-group<?php if (form_error('table')) : ?> has-error<?php endif; ?>">
                <label for="input_table" class="control-label">数据表名称</label>
                <input type="text" name="table" id="input_table" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('table')); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('table'); ?></span>
            </div>
            
            <div class="form-group">
                <p>&nbsp;</p>
                <p>该功能能够自动创建新数据表的模型,控制器,视图的代码. </p>
                <p><span class="bg-danger">警告:若该表已存在对应的model,controller,view的文件则会被自动覆盖.</span></p>
                <p>测试请输入: example</p>
                <p>&nbsp;</p>
                <p>在数据表的字段描述填入魔法字符则会生成对应的特殊功能:</p>
                <table class="table">
                    <tr>
                        <td>状态$array$0:停用|1:启用</td>
                        <td>(说明: $array$键值:键名|键值:键名   例如字段为status需要有两个状态数组)</td>
                    </tr>
                    <tr>
                        <td>文章ID$id$article</td>
                        <td>(说明: $id$表名   例如字段article_id为关联article表的id)</td>
                    </tr>
                    <tr>
                        <td>价格$max$</td>
                        <td>(说明: 常用于price价格等需要最大值和最小值的字段)</td>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <p>中文名称描述也可以留空.例如: $array$0:停用|1:启用</p>
                <p>&nbsp;</p>
                <p>详细请查看example表.</p>
                <p>默认保留字段: id, update_time, create_time 在创建表时必须要有, 具体参考example表.</p>
            </div>
            <input name="post" type="hidden" value="1">
            <input class="btn btn-primary" type="submit" value="提交">
        </form>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>