<?php $this->load->view('backend/_header', array('onView' => 'article')); ?>

<script type="text/javascript">
    $(document).ready(function() {
        //表单全选
        checkAll();
        
        //日期控件
        bootstrapDate();
        
        //搜索框
        searchForm();

        //搜索框隐藏bug修正, 必须放在日期插件加载后隐藏
        <?php if (!$search) : ?>
            $('#searchForm').css('display', 'none');
            $('#searchFormTitle').attr('class', 'btn btn-default');
        <?php endif; ?>

        //管理操作
        $('#manageName').change(function() {
            var manageName = $(this).val();
            if(manageName == 'setCategoryId') {
                $('#setCategoryId').css('display', '');
            } else {
                $('#setCategoryId').css('display', 'none');
            }
            if(manageName == 'setStatus') {
                $('#setStatus').css('display', '');
            } else {
                $('#setStatus').css('display', 'none');
            }
            if(manageName == 'setTop') {
                $('#setTop').css('display', '');
            } else {
                $('#setTop').css('display', 'none');
            }
        });
        
    });
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">文章列表</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>article/save">添加文章</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form name="form1" id="form1" method="post" action="<?php echo B_URL; ?>article/manage">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>分类</th>
                            <th>推荐</th>
                            <th>管理员</th>
                            <th>状态</th>
                            <th>发布日期</th>
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
                                    <td><?php echo $value['title']; ?></td>
                                    <td><?php echo $value['categoryName']; ?></td>
                                    <td><?php echo $tops[$value['top']]; ?></td>
                                    <td><?php echo $value['adminName']; ?></td>
                                    <td><?php echo $statuss[$value['status']]; ?></td>
                                    <td><?php echo $value['post_time']; ?></td>
                                    <td><a href="<?php echo B_URL; ?>article/save/?id=<?php echo $value['id']; ?>">编辑</a></td>
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
                    <option value="setStatus">设置status</option>
                    <option value="setTop">设置top</option>
                    <option value="setCategoryId">设置分类</option>
                </select>
                
                <select name="setStatus" id="setStatus" style="display: none;">
                    <option value="">请选择</option>
                    <?php if ($statuss != null) : ?>
                        <?php foreach ($statuss as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="setTop" id="setTop" style="display: none;">
                    <option value="">请选择</option>
                    <?php if ($tops != null) : ?>
                        <?php foreach ($tops as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="setCategoryId" id="setCategoryId" style="display: none;">
                    <option value="">请选择</option>
                    <?php if ($categorys != null) : ?>
                        <?php foreach ($categorys as $category) : ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
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
        
        <div class="panel panel-default">
            <div class="panel-heading"><button class="btn btn-default active" type="submit" id="searchFormTitle">条件筛选+</button></div>
            <div class="panel-body" id="searchForm">
                <div class="col-md-8">
                <form action="<?php echo B_URL; ?>article/index/" method="get">
                    <div class="form-group">
                        <label for="search_id">ID</label>
                        <input type="text" name="id" class="form-control" id="search_id" value="<?php echo $id; ?>">
                    </div>

                    <div class="form-group">
                        <label for="search_title">标题</label>
                        <input type="text" name="title" class="form-control" id="search_title" value="<?php echo $title; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="search_author">作者</label>
                        <input type="text" name="author" class="form-control" id="search_author" value="<?php echo $author; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="search_top">推荐</label>
                        <select name="top" class="form-control" id="search_top">
                            <option value="">请选择</option>
                            <?php if ($tops != null) : ?>
                                <?php foreach ($tops as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>" <?php if ($top === (string) $key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="search_category">分类</label>
                        <select name="article_category_id" class="form-control" id="search_category">
                            <option value="">请选择</option>
                            <?php if ($categorys != null) : ?>
                                <?php foreach ($categorys as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php if ($article_category_id == $category['id']) : ?>selected="selected"<?php endif; ?>><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="search_admin">管理员</label>
                        <select name="adminId" class="form-control" id="search_admin">
                            <option value="">请选择</option>
                            <?php if ($admins != null) : ?>
                                <?php foreach ($admins as $admin) : ?>
                                    <option value="<?php echo $admin['id']; ?>" <?php if ($adminId == $admin['id']) : ?>selected="selected"<?php endif; ?>><?php echo $admin['username']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="search_status">状态</label>
                        <select name="status" class="form-control" id="search_status">
                            <option value="">请选择</option>
                            <?php if ($statuss != null) : ?>
                                <?php foreach ($statuss as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>" <?php if ($status === (string) $key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>发布日期</label>
                        <input name="postTimeStart" data-provide="datepicker" class="form-control" type="text" value="<?php echo $postTimeStart; ?>" placeholder=">= 起始日期"> - 
                        <input name="postTimeEnd" data-provide="datepicker" class="form-control" type="text" value="<?php echo $postTimeEnd; ?>" placeholder="< 结束日期">
                    </div>
                    
                    <div class="form-group">
                        <label>创建日期</label>
                        <input name="createTimeStart" data-provide="datepicker" class="form-control" type="text" value="<?php echo $createTimeStart; ?>" placeholder=">= 起始日期"> - 
                        <input name="createTimeEnd" data-provide="datepicker" class="form-control" type="text" value="<?php echo $createTimeEnd; ?>" placeholder="< 结束日期">
                    </div>
                    
                    <input name="search" type="hidden" value="1" />
                    <input class="btn btn-primary" type="submit" value="筛选"> 
                    <a class="btn btn-default" href="<?php echo B_URL; ?>article/index" role="button">清空条件</a>
                </form>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>