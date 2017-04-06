<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 权限
 */
class Admin_permission extends CI_Controller {

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
        $this->backend_lib->cronLog(1, 'ddd');
        $data['statuss'] = $this->admin_permission_model->getStatus();
        
        //分页参数
        $pageUrl = B_URL.'admin_permission/index';
        $pagePer = 20;
        $suffix = "";
        
        //分页数据
        $data['result'] = $this->admin_permission_model->getPage($pageUrl, $pagePer, $suffix, $param, 'id DESC');
        
        $this->load->view('backend/admin_permission/index', $data);
    }

    /**
     * 新增和更新记录
     */
    public function save() {
        $data = array();
        
        $data['statuss'] = $this->admin_permission_model->getStatus();
        
        if ($this->input->post('save', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('id', 'ID', 'trim');
            $this->form_validation->set_rules('name', '权限名称', 'required|trim');
            $this->form_validation->set_rules('desc_txt', '描述', 'required|trim');
            $this->form_validation->set_rules('status', '状态', 'required|trim');

            $param = array(
                'id' => $this->input->post('id', TRUE),
                'name' => $this->input->post('name', TRUE),
                'desc_txt' => $this->input->post('desc_txt', TRUE),
                'status' => $this->input->post('status', TRUE),
                'update_time' => date('Y-m-d H:i:s'),
            );

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //保存记录
                $save = $this->admin_permission_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL.'admin_permission', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/admin_permission/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->admin_permission_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/admin_permission/save', $data);
        }
    }

    public function manage() {
        $data = array();
        $this->form_validation->set_rules('ids[]', 'Ids', 'required');
        $this->form_validation->set_rules('manageName', '操作名称', 'required');

        $manageName = $this->input->post('manageName', TRUE);
        $ids = $this->input->post('ids', TRUE);

        $success = FALSE;
        $message = '';

        if ($this->form_validation->run() == FALSE) {
            $message = '表单填写有误';
        } else {
            if ($ids != null) {
                if ($manageName == 'delete') {
                    //删除记录
                    foreach ($ids as $key => $id) {
                        $param = array(
                            'id' => $id,
                            'is_root !=' => 1,
                        );
                        $this->admin_permission_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'setStatus') {
                    $status = $this->input->post('setStatus', TRUE);
                    if ($status !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'status' => $status,
                            );
                            $this->admin_permission_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                }
            }
        }

        $this->backend_lib->showMessage(B_URL. 'admin_permission', $message);
    }

}
