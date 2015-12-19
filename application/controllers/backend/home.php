<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 后台控制类
 */
class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');
        
        //检查登录情况
        $this->backend_lib->checkLoginOrJump();
    }

    /**
     * 后台首页
     */
    public function index() {
        $data = array();
        
        $this->load->view('backend/home/index', $data);
    }

    /**
     * 信息提示跳转显示
     */
    public function showMessage() {
        $data = array();
        
        $url = $this->input->get('url', TRUE);
        $url = base64_decode($url);
        $message = $this->input->get('message', TRUE);
        $second = $this->input->get('second', TRUE);

        $data['url'] = $url ? $url : B_URL.'home';
        $data['message'] = $message ? $message : '无效信息提示';
        $data['second'] = $second ? $second : 5;
        
        $this->load->view('backend/_show_message', $data);
    }
    
    /**
     * 特殊设置
     */
    public function maintain() {
        $this->backend_lib->checkPermissionOrJump(1);
        $data = array();

        $this->load->view('backend/home/maintain', $data);
    }
    
    /*
     * 清除缓存
     */
    public function clearCache() {
        $this->load->driver('cache', array('adapter' => 'file'));
        if (!$this->cache->clean()) {
            echo 'Clean cache fail.';
        } else {
            echo 'Clean finsh.';
        }
    }

}
