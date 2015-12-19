<?php $this->load->view('backend/_header', array('onView' => 'home')); ?>
<div class="row">
    <div class="col-md-12">
        <p class="bd_title">控制台</p>
        <p>CICreator v1.3.0</p>
        <p>Time <?php echo date('Y-m-d H:i:s'); ?>, 时区 <?php echo  date_default_timezone_get(); ?></p>
    </div>
</div>
<?php $this->load->view('backend/_footer'); ?>