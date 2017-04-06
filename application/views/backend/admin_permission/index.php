<?php $this->load->view('backend/_header', array(
    'title' => '权限列表',
    'onView' => 'adminPermission'
)); ?>
<script type="text/javascript">
    $(document).ready(function () {
        //表单全选
        checkAll();

        //管理操作
        $('#manageName').change(function () {
            var manageName = $(this).val();
            if (manageName == 'setStatus') {
                $('#setStatus').css('display', '');
            } else {
                $('#setStatus').css('display', 'none');
            }
        });
    });
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">系统权限列表</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>admin_permission/save">添加权限</a></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form name="form1" id="form1" method="post" action="<?php echo B_URL; ?>admin_permission/manage">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>描述</th>
                            <th>状态</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result != null) : ?>
                            <?php foreach ($result as $value) : ?>
                                <tr class="bd_table_tr">
                                    <th scope="row">
                                        <label>
                                            <input type="checkbox" name="ids[]" value="<?php echo $value['id']; ?>" /> <?php echo $value['id']; ?>
                                        </label>
                                    </th>
                                    <td><?php echo $value['name']; ?></td>
                                    <td><?php echo $value['desc_txt']; ?></td>
                                    <td><?php echo $statuss[$value['status']]; ?></td>
                                    <td><?php echo $value['update_time']; ?></td>
                                    <td>
                                        <?php if (!$value['is_base']): ?>
                                            <a href="<?php echo B_URL; ?>admin_permission/save/?id=<?php echo $value['id']; ?>">编辑</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


            <div class="form-group">
                <label>
                    <input type="checkbox" id="checkAll"> 全选
                </label>

                <select name="manageName" id="manageName">
                    <option value="">请选择</option>
                    <option value="delete">删除</option>
                    <option value="setStatus">设置状态</option>
                </select>

                <select name="setStatus" id="setStatus" style="display: none;">
                    <option value="">请选择</option>
                    <?php if ($statuss != null) : ?>
                        <?php foreach ($statuss as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <input type="submit" id="manageButton"  value="提交" onclick="return confirmAction();" />
            </div>
        </form>

        <div class="bd_page">
            <nav>
                <ul class="pagination">
                    <?php echo $this->pagination->create_links(); ?>
                </ul>
            </nav>
        </div>

    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>