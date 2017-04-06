<?php $this->load->view('backend/_header', array(
    'title' => 'CiCreator网站后台',
    'onView' => 'home',
    'notShowName' => 1,
)); ?>
<div class="row">
    <div class="col-md-12">
        <p class="bd_title">控制台</p>
        <p>CICreator v<?php echo CC_VERSION; ?></p>
        <p>Time <?php echo date('Y-m-d H:i:s'); ?>, 时区 <?php echo  date_default_timezone_get(); ?></p>
        <p>Support: <a href="https://github.com/ywlcjl/cicreator" target="_blank">https://github.com/ywlcjl/cicreator</a></p>
        <p>License : </p>
        <p>The MIT License (MIT)</p>
        <p>and GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 (GPL)</p>
        <p></p>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>