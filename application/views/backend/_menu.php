<script type="text/javascript" >
    $(document).ready(function() {
        $('.menuTitle').toggle(function(){
            $(this).next('ul').hide('fast');
        }, function(){
            $(this).next('ul').show('fast');
        });
    });
</script>
<div class="menuBlock">
    <div class="menuTitle">控制台</div>
    <ul class="menuContent">
        <li <?php if ($onView == 'home'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>home">后台首页</a>
        </li>
    </ul>
</div>

<?php if($this->backend_lib->checkPermission(1)) : ?>
<div class="menuBlock">
    <div class="menuTitle">系统设置</div>
    <ul class="menuContent">
        <li <?php if ($onView == 'admin'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>admin">管理员</a>
        </li>
        <li <?php if ($onView == 'adminPermission'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>admin_permission">系统权限</a>
        </li>
        <li <?php if ($onView == 'setting'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>setting">设置</a>
        </li>
        <li <?php if ($onView == 'maintain'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>home/maintain">维护</a>
        </li>
        <li <?php if ($onView == 'create'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>create">生成代码</a>
        </li>
    </ul>
</div>
<?php endif; ?>

<?php if($this->backend_lib->checkPermission(2)) : ?>
<div class="menuBlock">
    <div class="menuTitle">文章管理</div>
    <ul class="menuContent">
        <li <?php if ($onView == 'articleCategory'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>article_category">文章分类</a>
        </li>
        <li <?php if ($onView == 'article'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>article">文章</a>
        </li>
        <li <?php if ($onView == 'attach'): ?>class="menuOnView"<?php endif; ?>>
            <a href="<?php echo B_URL; ?>attach">附件</a>
        </li>        
    </ul>
</div>
<?php endif; ?>
