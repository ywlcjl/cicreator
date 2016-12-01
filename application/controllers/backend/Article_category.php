<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 文章分类
 */
class article_category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');
        $this->load->model('article_category_model');
        $this->load->model('article_model');

        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(2);
    }

    /**
     * 首页
     */
    public function index() {
        $data = array();
        $param = array();

        $data['statuss'] = $this->admin_permission_model->getStatus();
        
        //整理好的分类
        $categorys = $this->article_category_model->getQueueCategory();
        
        if($categorys) {
            foreach($categorys as $key=>$category) {
                $categoryTemp = $this->article_category_model->getRow(array('id'=>$category['parent_id']));
                $categorys[$key]['parentName'] = $categoryTemp['name'] ? $categoryTemp['name'] : 0;
            }
        }
        
        $data['result'] = $categorys;
        
        $this->load->view('backend/article_category/index', $data);
    }

    /**
     * 新增和更新记录
     */
    public function save() {
        $data = array();
        
        $data['categorys'] = $this->article_category_model->getQueueCategory();
        $data['statuss'] = $this->admin_permission_model->getStatus();
        
        if ($this->input->post('save', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('id', 'ID', 'trim');
            $this->form_validation->set_rules('name', '分类名称', 'required|trim');
            $this->form_validation->set_rules('parent_id', '父ID', 'required|trim');
            $this->form_validation->set_rules('hop_link', '跳转链接', 'trim');
            $this->form_validation->set_rules('sort', '排序', 'required|trim');
            $this->form_validation->set_rules('status', '状态', 'required|trim');

            $param = array(
                'id' => $this->input->post('id', TRUE),
                'name' => $this->input->post('name', TRUE),
                'parent_id' => $this->input->post('parent_id', TRUE),
                'hop_link' => $this->input->post('hop_link', TRUE),
                'sort' => $this->input->post('sort', TRUE),
                'status' => $this->input->post('status', TRUE),
                'update_time' => date('Y-m-d H:i:s'),
            );

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } elseif ($param['id'] && $param['parent_id'] == $param['id']) {
                //父ID不能是自己本身
                $message = '父ID不能是自己本身';
            } else {
                //保存记录
                $save = $this->article_category_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL . '/article_category', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/article_category/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->article_category_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/article_category/save', $data);
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
                if ($manageName == 'setStatus') {
                    $status = $this->input->post('setStatus', TRUE);
                    if ($status !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'status' => $status,
                            );
                            $this->article_category_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                }
            }
        }

        $this->backend_lib->showMessage(B_URL . '/article_category', $message);
    }

    public function delete() {
        $data = array();

        $id = $this->input->get('id', TRUE);

        $success = FALSE;
        $message = '';

        if ($id) {
            $row = $this->article_category_model->getRow(array('id' => $id));

            if ($row) {
                $articleNum = $this->article_model->count(array('article_category_id' => $id));
                $childrenNum = $this->article_category_model->count(array('parent_id' => $id));

                if ($articleNum) {
                    $message = '删除失败, 请转移该分类下的文章';
                } elseif ($childrenNum) {
                    $message = '删除失败, 请转移该分类下的子分类';
                } else {
                    //删除记录
                    $delete = $this->article_category_model->delete(array('id'=>$id));

                    if ($delete) {
                        $message = '删除成功';
                        $success = TRUE;
                    } else {
                        $message = '删除失败';
                    }
                }
            } else {
                $message = '无效分类ID';
            }
            
        } else {
            $message = '没有分类ID';
        }

        $this->backend_lib->showMessage(B_URL . '/article_category', $message);
    }

}
