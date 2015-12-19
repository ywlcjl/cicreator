<?php $this->load->view('backend/_header'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        toMessage();
    });
</script>

<div class="row">
    <div class="col-md-12">
<div class="panel panel-default">
  <div class="panel-heading">信息提示</div>
  <div class="panel-body text-center">
      <p><b><?php echo $message; ?></b></p>
      <p><a href="<?php echo $url; ?>" id="toUrl">马上跳转...</a> 剩余 <span id="second" class="text-danger"><?php echo $second; ?></span> 秒</p>
  </div>
</div>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>