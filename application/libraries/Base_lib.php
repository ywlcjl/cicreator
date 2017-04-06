<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base_lib {

    protected $_CI;

    public function __construct() {
        $this->_CI = & get_instance();
    }

    public function createImg($src, $width = 200, $height = 600, $thumb = '', $quality = 90) {
        $this->_CI->load->library('image_lib');
        $this->_CI->image_lib->clear();

        //缩略图片
        $config = array();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $src;
        $config['create_thumb'] = $thumb != '' ? TRUE : FALSE;
        $config['thumb_marker'] = $thumb;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['quality'] = $quality;
        $this->_CI->image_lib->initialize($config);
        $this->_CI->image_lib->resize();
        $this->_CI->image_lib->clear();
    }


    /**
     * 返回数组key=>name
     */
    public function getKeyToName($array, $dkey='id', $dvalue='name') {
        $result = array();
        if ($array != NULL && is_array($array)) {
            foreach ($array as $value) {
                $result[$value[$dkey]] = $value[$dvalue];
            }
        }

        return $result;
    }

    /**
     * 获取上传目录
     * @param type $dirName
     * @return type
     */
    public function getUploadDir($dirName) {
        $uploadDir = 'uploads';
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $path = "$uploadDir/$dirName/$year/$month/$day/";
        $pathYear = "$uploadDir/$dirName/$year/";
        $pathMonth = "$uploadDir/$dirName/$year/$month/";

        if (!is_dir($pathYear)) {
            mkdir($pathYear, 0777);
        }

        if (!is_dir($pathMonth)) {
            mkdir($pathMonth, 0777);
        }

        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        return $path;
    }
    
    /**
     * 获取设置
     * @param type $key
     * @return type
     */
    public function getSetting($key='') {
        $this->_CI->load->model('setting_model');
        
        $settings = $this->_CI->setting_model->getSetting();
        
        $result = '';
        
        if ($key) {
            $result = $settings[$key];
        } else  {
            $result = $settings;
        }
        
        return $result;
    }
    
    /*
     * 系统日志
     */
    public function cronLog($type, $log) {
        $this->_CI->load->model('cron_log_model');
        
        $param = array(
            'type' => $type,
            'memo' => $log,
            'status' => 1,
            'admin_id' => $_SESSION['adminId'],
        );
        
        $this->_CI->cron_log_model->save($param);
        
    }
    
}
