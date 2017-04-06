<?php $this->load->view('backend/_header', array(
        'title' => '登陆',
)); ?>
<div class="row">
    <div class="col-md-6">
        <p class="bd_title">后台登录</p>
    </div>
    <div class="col-md-6">
        <p class="text-right"></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($message) && $message) : ?>
            <div class="bd_warning_bg">
                <p class="bg-danger"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo B_URL; ?>login/signIn" method="post">
            <div class="form-group<?php if (form_error('username')) : ?> has-error<?php endif; ?>">
                <label for="input_username" class="control-label">用户</label>
                <input type="text" name="username" id="input_username" class="form-control" aria-describedby="helpBlock" value="<?php echo cc_get_value(set_value('username'), $row['username']); ?>">
                <span id="helpBlock" class="help-block"><?php echo form_error('username'); ?></span>
            </div>

            <div class="form-group<?php if (form_error('password')) : ?> has-error<?php endif; ?>">
                <label for="input_password" class="control-label">密码</label>
                <input type="password" name="password" id="input_password" class="form-control" aria-describedby="helpBlock" value="">
                <span id="helpBlock" class="help-block"><?php echo form_error('password'); ?></span>
            </div>
                        
            <div class="form-group<?php if (form_error('captcha') || $captchaError) : ?> has-error<?php endif; ?>">
                <label for="input_captcha" class="control-label">验证码</label>
                <input type="text" name="captcha" id="input_captcha" class="form-control" aria-describedby="helpBlock" value="">
                <span id="helpBlock" class="help-block"><?php echo form_error('captcha'); ?></span>
                <span>
                    <img src="/plugins/cool-php-captcha/captcha.php" id="captcha" align="absmiddle" /> 
                    <a href="#" onclick="document.getElementById('captcha').src='/plugins/cool-php-captcha/captcha.php?'+Math.random();document.getElementById('captcha-form').focus();" id="change-image">换一张?</a>
                </span>
                
            </div>

            <input name="login" type="hidden" value="1" />
            <input class="btn btn-default" type="submit" value="登入" />
        </form>
        <p>&nbsp;</p>
    </div>
</div>

<?php $this->load->view('backend/_footer'); ?>
