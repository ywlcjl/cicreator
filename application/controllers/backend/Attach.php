<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * attach 控制器
 */
class Attach extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('image_lib');
        $this->load->library('upload');
        $this->load->library('backend_lib');

        $this->load->model('attach_model');
        $this->load->model('article_model');
        //检查登录
        $this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(2);
    }
                
    public function index() {
        $data = array();
        $param = array();

        $data['articles'] = $this->article_model->getResult(array(), '', '', 'id DESC');

        //搜索筛选
        $data['search'] = $this->input->get('search', TRUE);
        if($data['search']) {

            $data['id'] = $this->input->get('id', TRUE);
            if($data['id'] !== '') {
                $param['id'] = $data['id'];
            }

            $data['orig_name'] = $this->input->get('orig_name', TRUE);
            if($data['orig_name']) {
                $param['orig_name like'] = $data['orig_name'];
            }

            $data['path'] = $this->input->get('path', TRUE);
            if($data['path']) {
                $param['path'] = $data['path'];
            }

            $data['type'] = $this->input->get('type', TRUE);
            if($data['type']) {
                $param['type'] = $data['type'];
            }

            $data['article_id'] = $this->input->get('article_id', TRUE);
            if($data['article_id'] !== '') {
                $param['article_id'] = $data['article_id'];
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
        $pageUrl = B_URL.'article/index';
        $pagePer = 20;
        $suffix = $urlGet;
        
        //分页数据
        $result = $this->attach_model->getPage($pageUrl, $pagePer, $suffix, $param, 'id DESC');
        
        $data['result'] = $result;

        $this->load->view('backend/attach/index', $data);
    }

    public function save() {
        $data = array();
        $data['articles'] = $this->article_model->getResult(array(), '', '', 'id DESC');

        if ($this->input->post('save', TRUE) > 0) {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('name', 'name', 'trim');
            $this->form_validation->set_rules('orig_name', 'orig_name', 'trim');
            $this->form_validation->set_rules('path', 'path', 'trim');
            $this->form_validation->set_rules('type', 'type', 'trim');
            $this->form_validation->set_rules('article_id', 'article_id', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'name' => $this->input->post('name', TRUE),
            'orig_name' => $this->input->post('orig_name', TRUE),
            'path' => $this->input->post('path', TRUE),
            'type' => $this->input->post('type', TRUE),
            'article_id' => $this->input->post('article_id', TRUE),

        );
            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
            } else {
                //保存记录
                $save = $this->attach_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                } else {
                    $message = '保存失败';
                }
            }

            if ($success) {
                $this->backend_lib->showMessage(B_URL.'attach', $message);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/attach/save', $data);
            }
        } else {
            //显示记录的表单
            $id = intval($this->input->get('id'));
            if ($id) {
                $data['row'] = $this->attach_model->getRow(array('id' => $id));
            }
            $this->load->view('backend/attach/save', $data);
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
                        $this->attach_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_article_id') {
                    $setValue = $this->input->post('set_article_id', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'article_id' => $setValue,
                            );
                            $this->attach_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                }
            }
        }

        $this->backend_lib->showMessage(B_URL. 'attach', $message);
    }
    
    public function ajaxDelete() {
        $data = array();
        $id = $this->input->post('id', TRUE);

        $success = 0;
        $message = '';
        
        if ($id) {
            $attach = $this->attach_model->getRow(array('id'=>$id));
            $delete = $this->attach_model->delete(array('id' => $id));
            if ($delete) {
                //删除附件
                $imgPath = $attach['path'];
                $imgPathThumb = cc_get_img_path($attach['path'], 'thumb');
                if(is_file($imgPath)) {
                    unlink($imgPath);
                }
                if(is_file($imgPathThumb)) {
                    unlink($imgPathThumb);
                }
                
                $success = 1;
                $message = '删除成功';
            } else {
                $message = '删除失败';
            }
        } else {
            $message = 'ID有误';
        }

        $data['success'] = $success;
        $data['message'] = $message;

        echo json_encode($data);
    }
    
    public function ajaxUpload() {
        $data = array();

        $success = 0;
        $message = '';
        $data['result'] = array();
        
        if ($this->input->post('post', TRUE) > 0) {
            //附件目录
            $pathDir = $this->backend_lib->getUploadDir('attach');

            //上传文件类配置
            $config = array();
            $config['upload_path'] = "./$pathDir/";
            $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png';
            $config['max_size'] = '2048';
            $config['max_width'] = '3000';
            $config['max_height'] = '3000';
            $config['remove_spaces'] = TRUE;
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload()) {
                $message = $this->upload->display_errors();
            } else {
            //上传完成的数组
                $uploadData = $this->upload->data();

                //图片路径
                $imgPath = $uploadData['full_path'];

                //获取图片大小
                $imgSize = getimagesize($imgPath);
                //宽度
                $imgWidth = $imgSize[0];
                $imgHeight = $imgSize[1];
                
                $width = $this->backend_lib->getSetting('attach_thumb_width');
                $height = $this->backend_lib->getSetting('attach_thumb_height');
                $quality = $this->backend_lib->getSetting('attach_quality');

                //宽度最小
                if ($imgWidth < $width) {
                    $width = $imgWidth;
                }
                
                //生成缩略图
                $this->backend_lib->createImg($imgPath, $width, $height, '_thumb', $quality);
                
                //写入数据库
                $param = array(
                    'name' => $uploadData['file_name'],
                    'orig_name' => $uploadData['orig_name'],
                    'path' => $pathDir . $uploadData['file_name'],
                    'type' => $uploadData['image_type'],
                );
                $this->attach_model->save($param);

                $attachId = $this->db->insert_id();
                
                if ($attachId) {
                    $success = 1;
                    $message = '上传成功';
                    
                    $data['attachId'] = $attachId;
                    $data['result'] = $uploadData;
                    $data['picUrl'] = $pathDir . $uploadData['file_name'];
                    $data['picUrlThumb'] = $pathDir . cc_get_img_path($uploadData['file_name'], 'thumb');
                } else {
                    $message = '上传失败';
                }
            }
        
        } else {
            $message = 'none';
        }

        $data['success'] = $success;
        $data['message'] = $message;

        echo json_encode($data);
    }
    
}
