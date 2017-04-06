<?php $this->load->view('backend/_header', array(
    'title' => '管理员列表',
    'onView' => 'admin'
)); ?>
<script type="text/javascript">
    $(document).ready(function () {
        //全选
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
        <p class="bd_title">管理员列表</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>admin/save">添加管理员</a></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form name="form1" id="form1" method="post" action="<?php echo B_URL; ?>admin/manage">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>用户名</th>
                            <th>状态</th>
                            <th>最后登录</th>
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
                                    <td><?php echo $value['username']; ?></td>
                                    <td><?php echo $statuss[$value['status']]; ?></td>
                                    <td><?php echo $value['login_time']; ?></td>
                                    <td>
                                        <a href="<?php echo B_URL; ?>admin/save/?id=<?php echo $value['id']; ?>">编辑</a> 
                                        <a href="<?php echo B_URL; ?>admin/addPermission/?adminId=<?php echo $value['id']; ?>">权限</a>
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