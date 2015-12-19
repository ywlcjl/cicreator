<?php $this->load->view('backend/_header', array('onView' => 'attach')); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="main">
    <div class="mainLeft">
        <?php $this->load->view('backend/_menu', array('onView' => 'attach')); ?>
    </div>
    <div class="mainRight">
        <div class="block1">
            <div class="titleStyle1">
                attach 编辑<span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>attach/index" class="white">返回列表</a></span>
            </div>
            <div class="contentBlock1">
                <?php if ($message) : ?>
                    <div class="errorMessage">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo B_URL; ?>attach/save" method="post">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>name:</th>
                            <td>
                                <input name="name" type="text" value="<?php echo $this->backend_lib->getValue(set_value('name'), $row['name']); ?>" />
                                <?php echo form_error('name'); ?>
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>orig_name:</th>
                            <td>
                                <input name="orig_name" type="text" value="<?php echo $this->backend_lib->getValue(set_value('orig_name'), $row['orig_name']); ?>" />
                                <?php echo form_error('orig_name'); ?>
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>path:</th>
                            <td>
                                <input name="path" type="text" value="<?php echo $this->backend_lib->getValue(set_value('path'), $row['path']); ?>" />
                                <?php echo form_error('path'); ?>
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>type:</th>
                            <td>
                                <select name="type">
                                    <?php if ($types != null) : ?>
                                        <?php foreach ($types as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($row['type'] === (string)$key || set_value('type') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('type'); ?>
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>article_id:</th>
                            <td>
                                <select name="article_id">
                                    <?php if ($articles != null) : ?>
                                        <?php foreach ($articles as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php if ($row['article_id'] === (string)$value['id'] || set_value('article_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('article_id'); ?>
                            </td>
                            <td></td>
                        </tr>

                    </table>
                    <input name="save" type="hidden" value="1" />
                    <input name="id" type="hidden" value="<?php echo $this->backend_lib->getValue(set_value('id'), $row['id']); ?>" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="保存" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>
