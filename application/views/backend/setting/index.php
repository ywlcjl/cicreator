<?php $this->load->view('backend/_header', array(
    'title' => '设置列表',
    'onView' => 'setting'
)); ?>
<script type="text/javascript">
    $(document).ready(function () {
    });
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">系统设置</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>setting/save">添加设置</a></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>键</th>
                        <th>值</th>
                        <th>描述</th>
                        <th>状态</th>
                        <th>更新</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result != null) : ?>
                        <?php foreach ($result as $value) : ?>
                            <tr class="bd_table_tr">
                                <th scope="row"><?php echo $value['id']; ?></th>
                                <td><?php echo $value['key']; ?></td>
                                <td><?php echo $value['value']; ?></td>
                                <td><?php echo $value['txt']; ?></td>
                                <td><?php echo $statuss[$value['status']]; ?></td>
                                <td><?php echo $value['update_time']; ?></td>
                                <td><a href="<?php echo B_URL; ?>setting/save/?id=<?php echo $value['id']; ?>">编辑</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>