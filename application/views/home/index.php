<?php $this->load->view('_header', array('title'=>'欢迎使用 CICreator')); ?>
<div id="container">
	<h1>欢迎使用 CICreator v<?php echo CC_VERSION; ?></h1>

	<div id="body">
            <p>CICreator一个基于CodeIgniter 3 和 Bootstrap 3开发的网站后台系统. &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo B_URL; ?>" target="_blank">进入后台</a> (用户admin,密码admin)</p>
            <p><b>特色功能:</b> 自动生成代码: 一个能够根据数据表的配置自动生成对应的model, controller, view的等后台文件, 节省开发的时间.</p>
            <p><b>基础功能:</b> CMS内容管理系统功能包括: 管理员, 权限, 文章分类, 文章, 文章附件, 系统设置等.</p>
            <p>&nbsp;</p>
            <p style="color: darkred;">安装说明: 新建一个数据库,导入cicreator.sql,并修改application/config/database.php的相应数据库设置.</p>
            <p>&nbsp;</p>
            <p style="color: darkkhaki;">注意: IE浏览器版本低于IE10, 文章本地图片上传功能可能无法使用.</p>
            <p>&nbsp;</p>
            <p><a href="/welcome">CodeIgniter欢迎页</a></p>
            <p>更多CodeIgniter的内容您可以访问 <a href="http://www.codeigniter.com/" target="_blank">CodeIgniter官网</a>, &nbsp;&nbsp;<a href="http://codeigniter.org.cn/" target="_blank">CodeIgniter中国</a>.</p>
            <p>更多Bootstrap的内容您可以访问 <a href="http://getbootstrap.com/" target="_blank">Bootstrap官网</a>, &nbsp;&nbsp;<a href="http://www.bootcss.com/" target="_blank">Bootstrap中文网</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<?php $this->load->view('_footer'); ?>