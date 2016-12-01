<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 管理员
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');

        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(1);
    }

    /**
     * 管理员首页
     */
    public function index() {
        $data = array();
        $param = array();

        $data['statuss'] = $this->admin_model->getStatus();

        //分页参数
        $pageUrl = B_URL . 'admin/index';  //分页链接
        $pageUri = 4;   //URL参数位置
        $pagePer = 20;  //每页数量
        $suffix = "";   //GET参数
        //计算分页起始条目
        $pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        $startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $data['result'] = $this->admin_model->getResult($param, $pagePer, $startRow, 'id DESC');

        //生成分页链接
        $total = $this->admin_model->count($param);
        $this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        $this->load->view('backend/admin/index', $data);
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
            $this->form_validation->set_rules('username', '用户名', 'required|trim|min_length[3]|max_length[18]');
            $this->form_validation->set_rules('password', '密码', 'trim');
            $this->form_validation->set_rules('status', '状态', 'required|trim');

            $param = array(
                'id' => intval($this->input->post('id')),
                'username' => $this->input->post('username', TRUE),
                'status' => $this->input->post('status', TRUE),
                'update_time' => date('Y-m-d H:i:s'),
            );

            //设置密码
            $password = $this->input->post('password', TRUE);
            if ($password != null) {
                $param['password'] = md5($password);
            }

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {   //表单填写错误
                //检查表单是否有误
                $message = '表单填写有误';
            } elseif ($this->admin_model->getRow(array('username' => $param['username'], 'id !=' => $param['id'])) != NULL) {
                //检查是否有同名
                $message = '用户名已存在';
            } else {
                //保存记录
                $save = $this->admin_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL . 'admin', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/admin/save', $data);
            }
        } else {
            $id = $this->input->get('id', TRUE);
            if ($id) {
                $data['row'] = $this->admin_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/admin/save', $data);
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
                            'is_root !=' => '1',
                        );
                        $this->admin_model->delete($param);
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
                            $this->admin_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                }
            }
        }

        $this->backend_lib->showMessage(B_URL . 'admin', $message);
    }

    public function addPermission() {
        $data = array();

        if ($this->input->post('add', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('id', 'ID', 'required');

            //权限数组
            $permissions = $this->input->post('permission', TRUE);

            //权限字段
            if ($permissions != null) {
                $adminPermission = implode('|', $permissions);
            } else {
                $adminPermission = '';
            }

            $param = array(
                'id' => intval($this->input->post('id')),
                'admin_permission' => $adminPermission,
                'update_time' => date('Y-m-d H:i:s'),
            );

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //表单填写错误
                $message = '表单填写有误';
            } else {
                //保存用户权限
                $save = $this->admin_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            //必须跳转
            $this->backend_lib->showMessage(B_URL . "admin/addPermission/?adminId={$param['id']}", $message);
        } else {
            //显示添加用户权限表单
            $adminId = intval($this->input->get('adminId'));
            if ($adminId) {
                //用户权限
                $data['admin'] = $this->admin_model->getRow(array('id' => $adminId));
                $data['permissionArray'] = array();
                if ($data['admin']['admin_permission'] != null) {
                    $data['permissionArray'] = explode('|', $data['admin']['admin_permission']);  //拆分权限数组
                }

                //权限数组
                $data['permissionList'] = $this->admin_permission_model->getResult(array('status' => 1), '', '', 'id ASC');

                $this->load->view('backend/admin/add_permission', $data);
            } else {
                $this->backend_lib->showMessage(B_URL . 'admin', '无效管理员ID');
            }
        }
    }

}
