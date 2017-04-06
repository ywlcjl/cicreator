<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 文章控制器
 */
class Article extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');
        $this->load->model('article_category_model');
        $this->load->model('article_model');
        $this->load->model('attach_model');

        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(2);
    }

    /**
     * 内容管理首页
     */
    public function index() {
        $data = array();
        $param = array();
        
        $data['categorys'] = $this->article_category_model->getQueueCategory();
        $data['admins'] = $this->admin_model->getResult(array());
        $data['statuss'] = $this->article_model->getStatus();
        $data['tops'] = $this->article_model->getTop();
        
        //搜索筛选
        $data['search'] = $this->input->get('search', TRUE);
        if($data['search']) {
            $data['id'] = $this->input->get('id', TRUE);
            if($data['id']) {
                $param['id'] = $data['id'];
            }
            
            $data['title'] = $this->input->get('title', TRUE);
            if($data['title']) {
                $param['title like'] = $data['title'];
            }
            
            $data['author'] = $this->input->get('author', TRUE);
            if($data['author']) {
                $param['author'] = $data['author'];
            }
            
            $data['top'] = $this->input->get('top', TRUE);
            if($data['top'] !== '') {
                $param['top'] = $data['top'];
            }
            
            $data['article_category_id'] = $this->input->get('article_category_id', TRUE);
            if($data['article_category_id']) {
                $param['article_category_id'] = $data['article_category_id'];
            }
            
            $data['adminId'] = $this->input->get('adminId', TRUE);
            if($data['adminId']) {
                $param['admin_id'] = $data['adminId'];
            }
            
            $data['status'] = $this->input->get('status', TRUE);
            if($data['status'] !== '') {
                $param['status'] = $data['status'];
            }
            
            $data['postTimeStart'] = $this->input->get('postTimeStart', TRUE);
            $data['postTimeEnd'] = $this->input->get('postTimeEnd', TRUE);
            if($data['postTimeStart'] && $data['postTimeEnd']) {
                $param['post_time >='] = date('Y-m-d', strtotime($data['postTimeStart']));
                $param['post_time <'] = date('Y-m-d', strtotime($data['postTimeEnd']));
            }
            
            $data['createTimeStart'] = $this->input->get('createTimeStart', TRUE);
            $data['createTimeEnd'] = $this->input->get('createTimeEnd', TRUE);
            if($data['createTimeStart'] && $data['createTimeEnd']) {
                $param['create_time >='] = date('Y-m-d', strtotime($data['createTimeStart']));
                $param['create_time <'] = date('Y-m-d', strtotime($data['createTimeEnd']));
            }
            
        }
        
        //自动获取get参数
        $urlGet = $this->backend_lib->getGetStr();
        
        //分页参数
        $pageUrl = B_URL.'article/index';
        $pagePer = 20;
        $suffix = $urlGet;
        
        //分页数据
        $result = $this->article_model->getPage($pageUrl, $pagePer, $suffix, $param, 'id DESC');
        
        //获取联表结果
        if($result) {
            foreach($result as $key=>$value) {
                $category = $this->article_category_model->getRow(array('id'=>$value['article_category_id']));
                $result[$key]['categoryName'] = $category ? $category['name'] : '未分类';
                
                $admin = $this->admin_model->getRow(array('id'=>$value['admin_id']));
                $result[$key]['adminName'] = $admin ? $admin['username'] : '暂无';
            }
        }
        
        $data['result'] = $result;
        
        $this->load->view('backend/article/index', $data);
    }

    /**
     * 新增和更新记录
     */
    public function save() {
        $data = array();
        $data['categorys'] = $this->article_category_model->getQueueCategory();
        $data['statuss'] = $this->article_model->getStatus();
        $data['tops'] = $this->article_model->getTop();
        
        if ($this->input->post('save', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('id', 'ID', 'trim');
            $this->form_validation->set_rules('title', '标题', 'required|trim');
            $this->form_validation->set_rules('author', '作者', 'trim');
            $this->form_validation->set_rules('source', '来源', 'trim');
            $this->form_validation->set_rules('cover_pic', '封面图', 'trim');
            $this->form_validation->set_rules('desc_txt', '描述', 'trim');
            $this->form_validation->set_rules('content', '内容', 'required|trim');
            $this->form_validation->set_rules('hop_link', '外部链接', 'trim');
            $this->form_validation->set_rules('top', '推荐', 'required|trim');
            $this->form_validation->set_rules('article_category_id', '分类', 'required|trim');
            $this->form_validation->set_rules('post_time', '发表时间', 'trim');
            $this->form_validation->set_rules('status', '状态', 'required|trim');
            
            $param = array(
                'id' => $this->input->post('id', TRUE),
                'title' => $this->input->post('title', TRUE),
                'author' => $this->input->post('author', TRUE),
                'source' => $this->input->post('source', TRUE),
                'cover_pic' => $this->input->post('cover_pic', TRUE),
                'desc_txt' => cc_clean($this->input->post('desc_txt')),
                'content' => cc_clean($this->input->post('content')),
                'hop_link' => $this->input->post('hop_link', TRUE),
                'top' => $this->input->post('top', TRUE),
                'article_category_id' => $this->input->post('article_category_id', TRUE),
                'status' => $this->input->post('status', TRUE),
                'update_time' => date('Y-m-d H:i:s'),
            );
            
            if(!$param['id']) {
                $param['admin_id'] = $_SESSION['adminId'];
            }
            
            //更新发布时间
            if($this->input->post('postTime')) {
                $param['post_time'] = date('Y-m-d H:i:s');
            } else {
                $param['post_time'] = $this->input->post('post_time', TRUE);
            }
            
            //文章附件
            $attachIds = $this->input->post('attachId', TRUE);

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //保存记录
                $save = $this->article_model->save($param);

                if ($save) {
                    $insertId = $this->db->insert_id();
                    
                    $articleId = $param['id'] ? $param['id'] : $insertId;
                    
                    //处理附件
                    if($articleId && $attachIds && is_array($attachIds)) {
                        foreach($attachIds as $attachId) {
                            $this->attach_model->save(array('id'=>$attachId, 'article_id'=>$articleId));
                        }
                    }
                    
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL . 'article', $message);
            } else {
                //保留已上传的附件
                $attachs = array();
                //已存在的附件
                $attachsHad = array();
                if($param['id']) {
                    $attachsHad = $this->attach_model->getResult(array('article_id' => $param['id']));
                }
                //如果不为空则添加到附件数组
                if($attachsHad) {
                    $attachs = $attachsHad;
                }
                
                //已提交的附件
                if($attachIds && is_array($attachIds)) {
                    foreach($attachIds as $attachId) {
                        $attach = $this->attach_model->getRow(array('id'=>$attachId));
                        if($attach) {
                            //需要生成表单,作为二次提交
                            $attach['setInput'] = 1;
                            $attachs[] = $attach;
                        }
                    }
                }
                $data['attachs'] = $attachs;
                
                $data['message'] = $message;
                $this->load->view('backend/article/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->article_model->getRow(array('id' => $id));
                $data['attachs'] = $this->attach_model->getResult(array('article_id' => $id));
            }
            $this->load->view('backend/article/save', $data);
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
                        $this->article_model->delete($param);
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
                            $this->article_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                } elseif ($manageName == 'setTop') {
                    $top = $this->input->post('setTop', TRUE);
                    if ($top !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'top' => $top,
                            );
                            $this->article_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                
                } elseif ($manageName == 'setCategoryId') {
                    $setCategoryId = intval($this->input->post('setCategoryId'));
                    
                    foreach ($ids as $key => $id) {
                        $param = array(
                            'id' => $id,
                            'article_category_id' => $setCategoryId,
                        );
                        $this->article_model->save($param);
                    }
                    $message = '操作成功';
                }
            }
        }

        $this->backend_lib->showMessage(B_URL . 'article', $message);
    }

}
