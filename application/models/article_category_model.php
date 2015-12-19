<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Article_category_model extends Base_model {
    private $_name='article_category';
    
    private $_queueCategory = array();

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
    
    public function setQueueCategory($parentId=0, $symbol='', $mark='--') {
        $categorys = $this->getResult(array('parent_id'=>$parentId), '', '', 'sort');
        
        if($parentId) {
            $symbol .= $mark;
        }
        
        if($categorys != null) {
            foreach($categorys as $key=>$category) {
                $category['name'] = $symbol.$category['name'];
                $this->_queueCategory[] = $category;
                
                $this->setQueueCategory($category['id'], $symbol);
            }
        }
    }
    
    public function getQueueCategory($parentId=0, $symbol='', $mark='--') {
        if($this->_queueCategory == null) {
            $this->setQueueCategory($parentId, $symbol, $mark);
        }
        
        return $this->_queueCategory;
    }
    
    public function getStatus($key='') {
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
