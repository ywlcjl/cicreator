<?php $this->load->view('backend/_header', array('onView' => 'article')); ?>
<script type="text/javascript" src='/plugins/tinymce/tinymce.min.js'></script>
<script>
    tinymce.init({
        selector: '#editor_id',
        language: 'zh_CN',
        height: 300,
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'save table contextmenu directionality emoticons template paste textcolor'
        ],
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function() {
        articleUpload();
  
    });
</script>

<div class="row">
    <div class="col-md-6">
        <p class="bd_title">编辑文章</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"><a href="<?php echo B_URL; ?>article/index">返回列表</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-warning"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo B_URL; ?>article/save" method="post">
            <div class="form-group<?php if (form_error('title')) : ?> has-error<?php endif; ?>">
                <label for="input_title" class="control-label">标题</label>
                <input type="text" name="title" id="input_title" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('title'), $row['title']); ?>">
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('title'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('author')) : ?> has-error<?php endif; ?>">
                <label for="input_author" class="control-label">作者</label>
                <input type="text" name="author" id="input_author" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('author'), $row['author']); ?>">
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('author'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('source')) : ?> has-error<?php endif; ?>">
                <label for="input_source" class="control-label">来源</label>
                <input type="text" name="source" id="input_author" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('source'), $row['source']); ?>">
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('source'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('cover_pic')) : ?> has-error<?php endif; ?>">
                <label for="input_cover_pic" class="control-label">封面图</label>
                <input type="text" name="cover_pic" id="input_cover_pic" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('cover_pic'), $row['cover_pic']); ?>">
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('cover_pic'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('desc_txt')) : ?> has-error<?php endif; ?>">
                <label for="input_desc_txt" class="control-label">描述</label>
                <textarea name="desc_txt" id="input_name" aria-describedby="helpBlock" class="form-control" rows="3"><?php echo $this->backend_lib->getValue(set_value('desc_txt'), $row['desc_txt']); ?></textarea>
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('desc_txt'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('hop_link')) : ?> has-error<?php endif; ?>">
                <label for="input_hop_link" class="control-label">链接</label>
                <input type="text" name="hop_link" id="input_hop_link" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('hop_link'), $row['hop_link']); ?>">
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('hop_link'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('top')) : ?> has-error<?php endif; ?>">
                <label for="input_top" class="control-label">推荐</label>
                <select name="top" class="form-control">
                    <?php if ($tops) : ?>
                        <?php foreach ($tops as $key => $value) : ?>
                            <option value="<?php echo $key; ?>" <?php if ($row['top'] === (string) $key || set_value('top') === (string) $key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('top'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('article_category_id')) : ?> has-error<?php endif; ?>">
                <label for="input_article_category_id" class="control-label">文章分类</label>
                <select name="article_category_id" class="form-control">
                    <option value="0">默认</option>
                    <?php if ($categorys != null) : ?>
                        <?php foreach ($categorys as $category) : ?>
                            <option value="<?php echo $category['id']; ?>" <?php if ($row['article_category_id'] == $category['id'] || set_value('article_category_id') == $category['id']) : ?>selected="selected"<?php endif; ?>><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('article_category_id'); ?>
            </div>
            
            <div class="form-group<?php if (form_error('content')) : ?> has-error<?php endif; ?>">
                <label for="editor_id" class="control-label">内容</label>
                <textarea id="editor_id" name="content"><?php echo $this->backend_lib->getValue(set_value('content'), $row['content']); ?></textarea>
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('content'); ?>
            </div>
            
            <div class="form-group">
                <h5>附件列表</h5>
                <ul class="list-group" id="attachList">
                <?php if ($attachs != null) : ?>
                    <?php foreach ($attachs as $attach) : ?>
                        <li class="list-group-item">
                            <?php if ($attach['setInput']) : ?><input type="hidden" name="attachId[]" value="<?php echo $attach['id']; ?>" /><?php endif; ?>
                            <a href="/<?php echo $attach['path']; ?>" target="_blank" class="bd_attach_img"><img src="/<?php echo cc_get_img_path($attach['path'], 'thumb'); ?>" class="img-thumbnail" alt="<?php echo $attach['orig_name']; ?>"></a> 
                            <a onclick="return attachInsertEditor(this)" href="/<?php echo $attach['path']; ?>">插入文章</a>&nbsp;&nbsp;
                            <a onclick="return setCoverPic(this);" href="<?php echo $attach['path']; ?>">设为封面</a>&nbsp;&nbsp;
                            <a onclick="return attachInsertEditor(this);" href="/<?php echo cc_get_img_path($attach['path'], 'thumb'); ?>">插入缩略图</a>&nbsp;&nbsp;
                            <a onclick="return setCoverPic(this);" href="<?php echo cc_get_img_path($attach['path'], 'thumb'); ?>">封面缩略图</a>&nbsp;&nbsp;
                            <a onclick="return deleteAttach(this);" href="<?php echo $attach['id']; ?>">删除</a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                </ul>
                
                <label for="inputFile">图片上传</label>
                <input type="file" id="inputFile"> <a class="btn btn-default" href="#" role="button" id="buttonUpload">上传</a>
                <span id="helpBlock" class="help-block">支持.jpg .png</span>
                <p class="bg-warning" id="attachMessage"></p>
            </div>
            
            <div class="form-group<?php if (form_error('post_time')) : ?> has-error<?php endif; ?>">
                <label for="input_post_time" class="control-label">发布时间</label>
                <input type="text" name="post_time" id="input_post_time" class="form-control" aria-describedby="helpBlock" value="<?php echo $this->backend_lib->getValue(set_value('post_time'), $row['post_time']); ?>">
                <span id="helpBlock" class="help-block">2015-01-01 08:10:01</span>
                <?php echo $this->backend_lib->formError('post_time'); ?>
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
                <span id="helpBlock" class="help-block"></span>
                <?php echo $this->backend_lib->formError('status'); ?>
            </div>

            <input name="save" type="hidden" value="1" />
            <input name="id" type="hidden" value="<?php echo $this->backend_lib->getValue(set_value('id'), $row['id']); ?>" />
            <input class="btn btn-primary" type="submit" value="保存">
        </form>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>