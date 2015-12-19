<?php $this->load->view('backend/_header', array('onView' => 'attach')); ?>
<script type="text/javascript">
    $(document).ready(function() {
        //表单全选
        checkAll();
        
        //表格行效果
        trView();

        //搜索框
        searchForm();

        //加载日期
        $('input.datepicker').Zebra_DatePicker();

        //搜索框隐藏bug修正, 必须放在日期插件加载后隐藏
        <?php if (!$search) : ?>
            $('#searchForm').css('display', 'none');
        <?php endif; ?>

        //管理操作
        $('#manageName').change(function() {
            var manageName = $(this).val();
            if(manageName == 'set_type') {
                $('#set_type').css('display', '');
            } else {
                $('#set_type').css('display', 'none');
            }
            if(manageName == 'set_article_id') {
                $('#set_article_id').css('display', '');
            } else {
                $('#set_article_id').css('display', 'none');
            }

        });
    });
</script>

<div class="main">
    <div class="mainLeft">
        <?php $this->load->view('backend/_menu', array('onView' => 'attach')); ?>
    </div>
    <div class="mainRight">
        <div class="block1">
            <div class="titleStyle1">attach列表<span class="titleStyleRight"><span class="titleStyleRightLine">|</span></span></div>
            <div class="contentBlock1">
                <form name="form1" id="form1" method="post" action="<?php echo B_URL; ?>attach/manage">
                    <table class="listForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="listFormHeader">
                            <th><b>ID</b></th>
                            <td>name</td>
                            <td>orig_name</td>
                            <td>path</td>
                            <td>type</td>
                            <td>article_id</td>
                            <td>create_time</td>
                            <td>操作</td>
                        </tr>
                        <?php if ($result != null) : ?>
                            <?php foreach ($result as $value) : ?>
                                <tr class="listFormContent">
                                    <th><input type="checkbox" name="ids[]" class="ids" value="<?php echo $value['id']; ?>" /> <?php echo $value['id']; ?></th>
                                    <td><?php echo $value['name']; ?></td>
                                    <td><?php echo $value['orig_name']; ?></td>
                                    <td><?php echo $value['path']; ?></td>
                                    <td><?php echo $types[$value['type']]; ?></td>
                                    <td><?php echo $value['article_id']; ?></td>
                                    <td><?php echo $value['create_time']; ?></td>
                                    <td>
                                        <a href="<?php echo B_URL; ?>attach/save/?id=<?php echo $value['id']; ?>">编辑</a> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                    <div class="pageLink"><?php echo $this->pagination->create_links(); ?></div>
                    <div class="listFormManage">
                        <a href="#" id="checkAll">全选</a>
                        <select name="manageName" id="manageName">
                            <option value="">请选择</option>
                            <option value="delete">删除</option>
                            <option value="set_type">设置type</option>
                            <option value="set_article_id">设置article_id</option>
                        </select>
                        <select name="set_type" id="set_type" style="display: none;">
                            <option value="">请选择</option>
                            <?php if ($types != null) : ?>
                                <?php foreach ($types as $key=>$value) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <select name="set_article_id" id="set_article_id" style="display: none;">
                            <option value="">请选择</option>
                            <?php if ($articles != null) : ?>
                                <?php foreach ($articles as $key=>$value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['id']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <input type="submit" name="manageButton" id="manageButton"  value="操作" onclick="return confirmAction();" />
                    </div>
                </form>
            </div>

        <div class="blank30"></div>

        <div class="block1">
            <div class="titleStyle1">
                <a href="#" id="searchFormTitle" class="white">搜索筛选+</a><span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>attach/index" class="white">清空条件</a></span>
            </div>
            <div class="contentBlock1" id="searchForm">
                <form action="<?php echo B_URL; ?>attach/index/" method="get">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>id:</th>
                            <td>
                                <input name="id" type="text" value="<?php echo $id; ?>" />
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>name:</th>
                            <td>
                                <input name="name" type="text" value="<?php echo $name; ?>" />
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>orig_name:</th>
                            <td>
                                <input name="orig_name" type="text" value="<?php echo $orig_name; ?>" />
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>path:</th>
                            <td>
                                <input name="path" type="text" value="<?php echo $path; ?>" />
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <th>type:</th>
                            <td>
                                <select name="type">
                                    <option value="">请选择</option>
                                    <?php if ($types != null) : ?>
                                        <?php foreach ($types as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($type === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>article_id:</th>
                            <td>
                                <select name="article_id">
                                    <option value="">请选择</option>
                                    <?php if ($articles != null) : ?>
                                        <?php foreach ($articles as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php if ($article_id === $value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>create_time:</th>
                            <td>
                                <input name="create_time_start" class="datepicker" type="text" value="<?php echo $create_time_start; ?>" /> - <input class="datepicker" name="create_time_end" type="text" value="<?php echo $create_time_end; ?>" />
                            </td>
                            <td></td>
                        </tr>

                    </table>
                    <input name="search" type="hidden" value="1" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="搜索" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>
