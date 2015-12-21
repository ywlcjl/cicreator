<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 管理员登录
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');
    }

    /**
     * 显示登录页面 
     */
    public function index() {
        $data = array();
        
        $this->load->view('backend/login/index', $data);
    }

    /**
     * 检查登录
     */
    public function signIn() {
        if ($this->input->post('login')) {
            $data = array();
            $this->form_validation->set_rules('username', '用户名', 'required|trim');
            $this->form_validation->set_rules('password', '密码', 'required|trim');
            $this->form_validation->set_rules('captcha', '验证码', 'required|trim');

            $param = array(
                'username' => $this->input->post('username', TRUE),
                'password' => md5($this->input->post('password', TRUE)),
                'status' => 1,
            );

            //验证码
            $captcha = $this->input->post('captcha', TRUE);

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } elseif ($captcha != '' && $captcha!=$_SESSION['captcha']) {
                //检查验证码
                $message = '验证码错误';
                $data['captchaError'] = 1;
            } else {
                //验证账号密码
                $admin = $this->admin_model->getRow($param);

                if ($admin != null) {
                    //登录成功
                    $message = '登录成功';
                    $success = TRUE;
                    
                    //生成Session
                    $_SESSION['adminId'] = $admin['id'];
                    $_SESSION['adminUsername'] = $admin['username'];
                    $_SESSION['adminPermission'] = $admin['admin_permission'] != null ? explode('|', $admin['admin_permission']) : array();
                    
                    unset($_SESSION['captcha']);
                } else {
                    $message = '管理员密码错误';
                }
            }

            if ($success) {
                //更新最后登录时间
                $this->admin_model->save(array('id'=>$admin['id'], 'login_time'=>date('Y-m-d H:i:s')));
                
                cc_to_link(B_URL.'home');
            } else {
                $data['message'] = $message;
                
                $this->load->view('backend/login/index', $data);
            }
        }
    }

    /**
     * 退出后台
     */
    public function logout() {
        unset($_SESSION['adminId']);
        unset($_SESSION['adminUsername']);
        unset($_SESSION['adminPermission']);
        session_destroy();

        cc_to_link(B_URL.'login');
    }

}