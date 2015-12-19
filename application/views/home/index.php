<?php $this->load->view('_header', array('title'=>'欢迎使用 CICreator')); ?>
<div id="container">
	<h1>欢迎使用 CICreator - 一个基于CodeIgniter 2.2.5的网站后台系统</h1>

	<div id="body">
            <p>一个基于CodeIgniter 2.2.5开发的网站后台系统. &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo B_URL; ?>" target="_blank">进入后台</a> (用户名和密码均是admin)</p>
            <p><b>特色功能:</b> 能够根据数据表生成对应的model, controller, view的文件, 节省开发的时间.</p>
            <p><b>系统功能:</b> 系统已自带一些基础功能, 管理员, 权限, 文章分类, 文章, 文章附件, 代码生成, 系统设置等内容管理的功能</p>
            <p>&nbsp;</p>
            <p style="color: darkred;">安装说明: 新建数据库,导入cicreator.sql到数据库,并修改application/config/database.php的相应设置.</p>
            <p>&nbsp;</p>
            <p>您可以在以下目录找到该页面文件:</p>
		<code>application/views/home/index.php</code>

		<p>您可以在以下目录找到该控制器:</p>
		<code>application/controllers/home.php</code>

                <p>更多功能您可以上<a href="http://www.codeigniter.com/" target="_blank">CodeIgniter官网</a>, &nbsp;&nbsp;<a href="http://codeigniter.org.cn/" target="_blank">CodeIgniter中国</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<?php $this->load->view('_footer'); ?>