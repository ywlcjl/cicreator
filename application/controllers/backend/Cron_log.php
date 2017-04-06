<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * cron_log 控制器
 */
class Cron_log extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');

        $this->load->model('cron_log_model');
        $this->load->model('admin_model');
        //检查登录
        $this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        $data = array();
        $param = array();
        $orParam = array();

        $data['types'] = $this->cron_log_model->getType();
        $data['admins'] = $this->admin_model->getResult(array(), '', '', 'id DESC');
        $data['statuss'] = $this->cron_log_model->getStatus();

        //搜索筛选
        $data['search'] = $this->input->get('search', TRUE);
        if($data['search']) {

            $data['id'] = $this->input->get('id', TRUE);
            if($data['id'] !== '') {
                $param['id'] = $data['id'];
            }

            $data['type'] = $this->input->get('type', TRUE);
            if($data['type'] !== '') {
                $param['type'] = $data['type'];
            }

            $data['memo'] = $this->input->get('memo', TRUE);
            if($data['memo']) {
                $param['memo like'] = $data['memo'];
            }

            $data['admin_id'] = $this->input->get('admin_id', TRUE);
            if($data['admin_id'] !== '') {
                $param['admin_id'] = $data['admin_id'];
            }

            $data['status'] = $this->input->get('status', TRUE);
            if($data['status'] !== '') {
                $param['status'] = $data['status'];
            }

            $data['update_time_start'] = $this->input->get('update_time_start', TRUE);
            $data['update_time_end'] = $this->input->get('update_time_end', TRUE);
            if ($data['update_time_start'] && $data['update_time_end']) {
                $param['update_time >='] = date('Y-m-d', strtotime($data['update_time_start']));
                $param['update_time <'] = date('Y-m-d', strtotime($data['update_time_end']));
            }

            $data['create_time_start'] = $this->input->get('create_time_start', TRUE);
            $data['create_time_end'] = $this->input->get('create_time_end', TRUE);
            if ($data['create_time_start'] && $data['create_time_end']) {
                $param['create_time >='] = date('Y-m-d', strtotime($data['create_time_start']));
                $param['create_time <'] = date('Y-m-d', strtotime($data['create_time_end']));
            }

        }

        //自动获取get参数
        $urlGet = $this->backend_lib->getGetStr();

        //分页参数
        $pageUrl = B_URL.'cron_log/index';
        $pagePer = 20;
        $suffix = $urlGet;
        
        //分页数据
        $result = $this->cron_log_model->getPage($pageUrl, $pagePer, $suffix, $param, 'id DESC');
        
        $data['result'] = $result;

        $this->load->view('backend/cron_log/index', $data);
    }

    public function save() {
        $data = array();
        $data['types'] = $this->cron_log_model->getType();
        $data['admins'] = $this->admin_model->getResult(array(), '', '', 'id DESC');
        $data['statuss'] = $this->cron_log_model->getStatus();

        if ($this->input->post('save', TRUE) > 0) {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('type', 'type', 'trim');
            $this->form_validation->set_rules('memo', 'memo', 'trim');
            $this->form_validation->set_rules('admin_id', 'admin_id', 'trim');
            $this->form_validation->set_rules('status', 'status', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'type' => $this->input->post('type', TRUE),
            'memo' => $this->input->post('memo', TRUE),
            'admin_id' => $this->input->post('admin_id', TRUE),
            'status' => $this->input->post('status', TRUE),
            'update_time' => date('Y-m-d H:i:s'),

        );
            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
            } else {
                //保存记录
                $save = $this->cron_log_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL.'cron_log', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/cron_log/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->cron_log_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/cron_log/save', $data);
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
                        );
                        $this->cron_log_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_type') {
                    $setValue = $this->input->post('set_type', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'type' => $setValue,
                            );
                            $this->cron_log_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                } elseif ($manageName == 'set_admin_id') {
                    $setValue = $this->input->post('set_admin_id', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'admin_id' => $setValue,
                            );
                            $this->cron_log_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                } elseif ($manageName == 'set_status') {
                    $setValue = $this->input->post('set_status', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'status' => $setValue,
                            );
                            $this->cron_log_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                }
            }
        }

        $this->backend_lib->showMessage(B_URL. 'cron_log', $message);
    }
}
