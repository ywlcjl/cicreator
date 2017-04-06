<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * cron_log 模型
 */
class Cron_log_model extends Base_model {
    private $_name='cron_log';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
   
    public function getType($key='') {
        $data = array(0 => '未分类', 1 => '默认', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }   
    public function getStatus($key='') {
        $data = array(0 => '待审核', 1 => '已启用', 2 => '已作废', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }
}
