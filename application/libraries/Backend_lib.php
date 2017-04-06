<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 后台基础功能综合类
 */

class Backend_lib extends Base_lib {

    public function __construct() {
        parent::__construct();
        
        //加载后台必须的模型
        $this->_CI->load->model('admin_model');
        $this->_CI->load->model('admin_permission_model');
        
        //输出调试结果
        //$this->_CI->output->enable_profiler(TRUE);
    }

    /**
     * 检查后台登陆并跳转
     * @return boolean
     */
    public function checkLoginOrJump() {
        if (!$this->checkLogin()) {
            cc_to_link(B_URL . 'login');
        }
    }

    /**
     * 检查adminId session
     * @return boolean 
     */
    public function checkLogin() {
        if (isset($_SESSION['adminId']) && $_SESSION['adminId'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 检查后台用户权限并跳转
     * @param int $permissionId 权限id
     * @return boolean 
     */
    public function checkPermissionOrJump($permission) {
        if (!$this->checkPermission($permission)) {
            $message = '权限不足';
            cc_to_link(B_URL . "home/showMessage/?message=$message");
        }
    }

    /**
     * 检查后台用户权限
     * @param type $permissionId
     * @return boolean 
     */
    public function checkPermission($permission) {
        if ($this->checkLogin() && $permission != null && in_array($permission, $_SESSION['adminPermission'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 分页创建
     * @param type $baseUrl
     * @param type $uriSegment
     * @param type $perPage
     * @param type $totalRows
     */
    public function createPage($baseUrl, $uriSegment, $perPage = 20, $totalRows = 200, $suffix = '') {
        $this->_CI->load->library('pagination');
        $config = array();
        $config['base_url'] = $baseUrl;         //路径 site_url() . '/backend/admin/index';
        $config['uri_segment'] = $uriSegment;   //路由参数
        $config['total_rows'] = $totalRows;     //总数
        $config['per_page'] = $perPage;         //每页显示
        $config['suffix'] = $suffix;            //后缀
        $config['num_links'] = 5;              //显示页数
        $config['use_page_numbers'] = TRUE;     //使用正常的数字
        
        $config['first_link'] = '<span aria-hidden="true">First</span>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        
        $config['last_link'] = '<span aria-hidden="true">Last</span>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        $config['next_link'] = '<span aria-hidden="true">&raquo;</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['prev_link'] = '<span aria-hidden="true">&laquo;</span>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = ' <span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->_CI->pagination->initialize($config);
    }

    /**
     * 信息提示跳转
     * @param type $url
     * @param type $message
     * @param type $second 
     */
    public function showMessage($url, $message, $second = 5) {
        //替换问好为^
        $url = base64_encode($url);

        $jumpUrl = B_URL . "home/showMessage/?url=$url&message=$message&second=$second";
        cc_to_link($jumpUrl);
    }

    /**
     * 获取表单的默认值
     * @param type $setValue 表单提交失败保存值
     * @param type $rowVaule 默认读出的值
     * @param type $defaultValue 保留全部没有的默认值
     * @return type 
     */
    public function getValue($setValue = '', $rowVaule = '', $defaultValue = '') {
        $result = '';

        if ($setValue !== '') {
            $result = $setValue;
        } elseif ($rowVaule !== '') {
            $result = $rowVaule;
        } else {
            $result = $defaultValue;
        }

        return $result;
    }
    
    public function getGetStr() {
        $urlGet = '';
        $gets = $this->_CI->input->get();
        if($gets) {
            $i = 0;
            foreach($gets as $getKey=>$get) {
                if($i) {
                    $urlGet .= "&$getKey=$get";
                } else {
                    $urlGet .= "/?$getKey=$get";
                }
                $i++;
            }
        }
        return $urlGet;
    }


    /**
     * CI辅助错误输出
     * @param type $fieldName
     * @return type
     */
//    public function formError($fieldName) {
//        return form_error($fieldName, '<p class="text-danger">', '</p>');
//    }

}
