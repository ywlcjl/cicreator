<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>上传文章附件</title>
        <script type="text/javascript" src="<?php echo base_url(); ?>asset/js/jquery-1.4.4.min.js"></script>

        <script type="text/javascript">
                    <?php if ($picUrl) : ?> 
                       $(document).ready(function(){
                    
                           var inputStr = '';
                           inputStr += '<li><input type="hidden" name="attachId[]" value="<?php echo $attachId; ?>" />';
                           inputStr += '<a href="<?php echo base_url() . $picUrl; ?>" target="_blank"><?php echo $uploadData['orig_name'] ?></a> ';
                           inputStr += '<a href="<?php echo base_url() . $picUrl; ?>" onclick="return attachInsertEditor(this)">插入文章</a> ';
                           inputStr += '<a href="<?php echo $picUrl; ?>" onclick="return setCoverPic(this);">设为封面</a> ';
                           inputStr += '<a href="<?php echo $picUrl; ?>" onclick="return insertImgToDesc(this);">插入描述</a> ';
                           inputStr += '|&nbsp;<a href="<?php echo base_url() . $picUrlThumb; ?>" target="_blank">缩略图</a> ';
                           inputStr += '<a href="<?php echo base_url() . $picUrlThumb; ?>" onclick="return attachInsertEditor(this);">插入缩略图</a> ';
                           inputStr += '<a href="<?php echo $picUrlThumb; ?>" onclick="return setCoverPic(this);">缩略图设为封面</a> ';
                           inputStr += '<a href="<?php echo $picUrlThumb; ?>" onclick="return insertImgToDesc(this);">缩略图插入描述</a> ';
                           inputStr += '<a href="<?php echo $attachId; ?>" onclick="return deleteAttach(this);">删除</a> ';
                           inputStr += '</li>';
                           
                           //插入到上一个框架
                           $(window.parent.document).find('#uploadAttach').append(inputStr);
                       });
                    <?php endif; ?>
        </script>
    </head>

    <body style="background-color: #fff;">
        <div style="font-size: 12px; line-height: 25px; padding: 2px;">
            <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <span style="color: #333;">请选择要上传的文件</span><br />
                <input type="file" name="userfile" id="attach" /> 
                <input type="hidden" name="post" value="1" /> 
                <input type="submit" name="button" id="button" value="上传" />&nbsp;&nbsp;<span>(图片少于2兆)</span>
            </form>
            <div><?php echo $error; ?></div>
        </div>
    </body>
</html>
