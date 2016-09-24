<?php $this->load->view('backend/_header', array('onView' => 'maintain')); ?>
<div class="row">
    <div class="col-md-6">
        <p class="bd_title">维护</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <p><a class="btn btn-default" role="button" href="<?php echo B_URL; ?>home/clearCache" target="_blank" onclick="return confirmAction();">删除缓存</a></p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>