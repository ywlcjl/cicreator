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
        $inParams = array();
        $likeParam = array();

        $data['types'] = $this->attach_model->getType();
        $data['articles'] = $this->article_model->getResult(array(), '', '', 'id DESC');

        //搜索筛选
        $data['search'] = $this->input->get('search', TRUE);
        if($data['search']) {

            $data['id'] = $this->input->get('id', TRUE);
            if($data['id'] !== '') {
                $param['id'] = $data['id'];
            }

            $data['name'] = $this->input->get('name', TRUE);
            if($data['name']) {
                $likeParam['name'] = $data['name'];
            }

            $data['orig_name'] = $this->input->get('orig_name', TRUE);
            if($data['orig_name']) {
                $likeParam['orig_name'] = $data['orig_name'];
            }

            $data['path'] = $this->input->get('path', TRUE);
            if($data['path']) {
                $likeParam['path'] = $data['path'];
            }

            $data['type'] = $this->input->get('type', TRUE);
            if($data['type']) {
                $likeParam['type'] = $data['type'];
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
        $urlGet = '';
        $gets = $this->input->get();
        if ($gets) {
            $i = 0;
            foreach ($gets as $getKey => $get) {
                if ($i) {
                    $urlGet .= "&$getKey=$get";
                } else {
                    $urlGet .= "/?$getKey=$get";
                }
                $i++;
            }
        }
                
        //排序
        $orderBy = $this->input->get('orderBy', TRUE);
        $orderBySQL = 'id DESC';
        if ($orderBy == 'idASC') {
            $orderBySQL = 'id ASC';
        }
        $data['orderBy'] = $orderBy;
                
        //分页参数
        $pageUrl = B_URL.'attach/index';  //分页链接
        $pageUri = 4;   //URL参数位置
        $pagePer = 20;  //每页数量
        $suffix = $urlGet;   //GET参数
        //计算分页起始条目
        $pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        $startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->attach_model->getResult($param, $pagePer, $startRow, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->attach_model->count($param, $inParams, $likeParam);
        $this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接
        //获取联表结果
        if ($result) {
            foreach ($result as $key => $value) {

            }
        }

        $data['result'] = $result;

        $this->load->view('backend/attach/index', $data);
    }

    public function save() {
        $data = array();
        $data['types'] = $this->attach_model->getType();
        $data['articles'] = $this->article_model->getResult(array(), '', '', 'id DESC');

        if ($this->input->post('save', TRUE) > 0) {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('name', 'name', 'required|trim');
            $this->form_validation->set_rules('orig_name', 'orig_name', 'required|trim');
            $this->form_validation->set_rules('path', 'path', 'required|trim');
            $this->form_validation->set_rules('type', 'type', 'required|trim');
            $this->form_validation->set_rules('article_id', 'article_id', 'trim');

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
                        
                        $row = $this->attach_model->getRow($param);
                        //删除附件
                        $imgPath = $row['path'];
                        $imgPathThumb = cg_get_img_path($row['path'], 'thumb');
                        if (is_file($imgPath)) {
                            unlink($imgPath);
                        }
                        if (is_file($imgPathThumb)) {
                            unlink($imgPathThumb);
                        }

                        $this->attach_model->delete($param);
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
                            $this->attach_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
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
    
    /**
     * 上传文章附件 
     */
    public function upload() {
        $data = array();
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
                $data['error'] = $this->upload->display_errors();
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

                $data['attachId'] = $attachId;
                $data['uploadData'] = $uploadData;
                $data['picUrl'] = $pathDir . $uploadData['file_name'];
                $data['picUrlThumb'] = $pathDir . cg_get_img_path($uploadData['file_name'], 'thumb');
            }
            $this->load->view('backend/attach/upload', $data);
        } else {
            $this->load->view('backend/attach/upload', $data);
        }
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
                $imgPathThumb = cg_get_img_path($attach['path'], 'thumb');
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
}
