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
