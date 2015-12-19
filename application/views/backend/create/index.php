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
                生成代码<span class="titleStyleRight"><span class="titleStyleRightLine">|</span></span>
            </div>
            <div class="contentBlock1">
                <?php if ($message) : ?>
                    <div class="errorMessage">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo B_URL; ?>create" method="post">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>数据表名称:</th>
                            <td>
                                <input name="table" type="text" value="<?php echo $this->backend_lib->getValue(set_value('table')); ?>" />
                                <?php echo form_error('table'); ?>
                            </td>
                            <td>
                                <p style="color: black;">该功能能够自动创建新数据表的模型,控制器,视图的代码. <span style="color: darkred;">警告:若该表已存在对应的model,controller,view的文件则会被自动覆盖. </span></p>
                                <p>测试请输入: example</p>
                                <p>&nbsp;</p>
                                <p>在数据表的字段描述填入魔法字符则会生成对应的特殊功能:</p>
                                <p style="color: black;">$array$0:停用|1:启用 &nbsp;(说明: $array$键值:键名|键值:键名 例如字段为status需要有两个状态数组)</p>
                                <p style="color: black;">$id$article &nbsp;(说明: $id$表名 例如字段article_id为关联article表的id)</p>
                                <p style="color: black;">$max$ &nbsp;(说明: 常用于price价格等需要最大值和最小值的字段)</p>
                                <p>&nbsp;</p>
                                <p>详细请查看example表.</p>
                                <p style="color: black;">默认保留字段: id, update_time, create_time 在创建表时必须要有, 具体参考example表.</p>
                            </td>
                        </tr>
                    </table>
                    <input name="post" type="hidden" value="1" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="提交" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>