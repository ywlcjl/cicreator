<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');

        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(1);
    }

    /**
     * 首页
     */
    public function index() {
        $data = array();
        $param = array();
        
        $data['statuss'] = $this->admin_model->getStatus();

        //整理好的分类
        $settings = $this->setting_model->getResult(array(), '', 0, 'id ASC');
        
        $data['result'] = $settings;
        
        $this->load->view('backend/setting/index', $data);
    }

    /**
     * 新增和更新记录
     */
    public function save() {
        $data = array();
        
        $data['statuss'] = $this->admin_model->getStatus();
        
        if ($this->input->post('save', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('id', 'ID', 'trim');
            $this->form_validation->set_rules('key', '键', 'required|trim');
            $this->form_validation->set_rules('value', '值', 'required|trim');
            $this->form_validation->set_rules('status', '状态', 'trim');
            $this->form_validation->set_rules('txt', '描述', 'required|trim');

            $param = array(
                'id' => $this->input->post('id', TRUE),
                'key' => $this->input->post('key', TRUE),
                'value' => $this->input->post('value', TRUE),
                'status' => $this->input->post('status', TRUE),
                'txt' => $this->input->post('txt', TRUE),
                'update_time' => date('Y-m-d H:i:s')
            );

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //保存记录
                $save = $this->setting_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL . 'setting', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/setting/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->setting_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/setting/save', $data);
        }
    }


}
