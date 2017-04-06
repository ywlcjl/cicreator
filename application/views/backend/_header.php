<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?><?php if (!$notShowName) : ?> - CICreator网站后台<?php endif; ?></title>

        <!-- Bootstrap -->
        <link href="/asset/backend/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/asset/js/html5shiv.min.js"></script>
          <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/asset/js/respond.min.js"></script>
        <![endif]-->
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="/asset/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/asset/backend/bootstrap/js/bootstrap.min.js"></script>
        
        <link href="/asset/backend/bootstrap/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <script src="/asset/backend/bootstrap/datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="/asset/backend/bootstrap/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>

        <link href="/asset/backend/style.css" rel="stylesheet" type="text/css" />
        <script src="/asset/backend/public.js" type="text/javascript"></script>
        
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>CICreator<small> 网站后台</small></h1>
                </div>
                <div class="col-md-6">
                    <p class="text-right">
                    <?php if (isset($_SESSION['adminId'])) : ?>
                        欢迎回来, <?php echo $_SESSION['adminUsername']; ?>
                        &nbsp;&nbsp;<a href="<?php echo B_URL; ?>login/logout">登出</a>
                        &nbsp;&nbsp;<a href="/" target="_blank">查看网站</a>
                    <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo B_URL; ?>">后台首页</a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <?php if ($this->backend_lib->checkPermission(1)) : ?>
                                <li class="dropdown <?php if (in_array($onView, array('admin', 'adminPermission', 'setting', 'maintain', 'create'))): ?> active<?php endif; ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">系统设置 <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li <?php if ($onView == 'admin'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>admin">管理员</a></li>
                                        <li <?php if ($onView == 'adminPermission'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>admin_permission">权限</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li <?php if ($onView == 'setting'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>setting">系统设置</a></li>
                                        <li <?php if ($onView == 'cronLog'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>cron_log">日志</a></li>
                                        <li <?php if ($onView == 'maintain'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>home/maintain">维护</a></li>
                                        <li <?php if ($onView == 'create'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>create">代码生成</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->backend_lib->checkPermission(2)) : ?>
                                <li class="dropdown<?php if (in_array($onView, array('articleCategory', 'article', 'attach'))): ?> active<?php endif; ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">文章管理 <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li <?php if ($onView == 'articleCategory'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>article_category">分类</a></li>
                                        <li <?php if ($onView == 'article'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>article">文章</a></li>
                                        <li <?php if ($onView == 'attach'): ?>class="active"<?php endif; ?>><a href="<?php echo B_URL; ?>attach">附件</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                </div>
            </nav>

