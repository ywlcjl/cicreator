<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * attach 模型
 */
class Attach_model extends Base_model {
    private $_name='attach';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }

}
