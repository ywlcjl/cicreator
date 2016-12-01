<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 文章模型
 */
class Article_model extends Base_model {
    private $_name='article';
    
    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
    
    public function getStatus($key='') {
        $data = array(
            0 => '待发布',
            1 => '已发布',
            2 => '预发布',
        );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    public function getTop($key='') {
        $data = array(
            0 => '否',
            1 => '是',
        );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }
    
}
