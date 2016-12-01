<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 设置模型
 */
class Setting_model extends Base_model {

    private $_name = 'setting';
    public $_setting = array();  //全局配置数组

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }

    public function getSetting() {
        //处理全局配置数组
        if ($this->_setting == NULL) {
            $settings = $this->getResult(array('status' => 1), '', 0, 'id ASC');
            if ($settings) {
                $result = array();
                foreach ($settings as $setting) {
                    $result[$setting['key']] = $setting['value'];
                }

                if ($result) {
                    $this->_setting = $result;
                }
            }
        }

        return $this->_setting;
    }

    public function setSetting($key, $value) {
        $setting = $this->getRow(array('key' => $key));
        if ($setting) {
            $param = array(
                'id' => $setting['id'],
                'value' => $value,
            );

            $this->save($param);
        }
    }

    public function getStatus($key = '') {
        $data = array(
            0 => '停用',
            1 => '启用',
        );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

}
