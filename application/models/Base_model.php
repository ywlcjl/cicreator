<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 基础模型类
 */
class Base_model extends CI_Model {

    protected $tableName = '';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取全部结果
     * @param type $param
     * @param type $limit
     * @param type $start
     * @param type $orderBy
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function getResult($param = array(), $limit = 0, $start = 0, $orderBy = 'id DESC', $inParams = array(), $likeParam=array(), $orParams=array()) {
        $this->db->from($this->tableName);
        $this->db->where($param);
        $this->db->order_by($orderBy);
        
        //where_in数组
        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }
        
        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        //limit组合
        if($limit && $start) {
            $this->db->limit($limit, $start);
        } elseif($limit) {
            $this->db->limit($limit);
        }
        
        //获取结果
        $query = $this->db->get();
        $result = $query->result_array();
        
        //echo $this->db->last_query();
        return $result;
    }

    /**
     * 获取单行结果
     * @param type $param
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function getRow($param = array(), $inParams = array(), $likeParam=array(), $orParams=array()) {
        //where_in数组
        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }
        
        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        $query = $this->db->get_where($this->tableName, $param);
        $row = $query->row_array();
        
        return $row;
    }

    /**
     * 新增或更新记录
     * @param type $data
     * @return boolean
     */
    public function save($data) {
        $id = $data['id'];

        //删除id键值
        if($id) {
            unset($data['id']);
        }

        if ($id > 0) {
            //更新记录
            $this->db->update($this->tableName, $data, "id = $id");
        } else {
            //新增记录
            $this->db->insert($this->tableName, $data);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 多条件更新记录
     * @param array $data 需要更新数组
     * @param array $param 更新条件
     * @return boolean 
     */
    public function update($data, $param) {
        //更新记录
        $this->db->update($this->tableName, $data, $param);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 删除记录
     * @param array $param
     * @return boolean
     */
    public function delete($param) {
        if (is_array($param) && $param != null) {
            $this->db->delete($this->tableName, $param);
        }

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 统计记录数
     * @param type $param
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function count($param = array(), $inParams = array(), $likeParam=array(), $orParams=array()) {
        if ($param != null) {
            $this->db->where($param);
        }

        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }

        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        $this->db->from($this->tableName);
        $count = $this->db->count_all_results();
        
        return $count;
    }
    
    /**
     * 求和记录
     * @param type $field
     * @param type $param
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function sum($field, $param = array(), $inParams = array(), $likeParam=array(), $orParams=array()) {
        $this->db->select_sum($field);
        
        if ($param != null) {
            $this->db->where($param);
        }

        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }

        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $row = $query->row_array();
        
        $sum = $row[$field];
        
        return $sum;
    }

    /**
     * 求平均数
     * @param type $field
     * @param type $param
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function avg($field, $param = array(), $inParams = array(), $likeParam=array(), $orParams=array()) {
        $this->db->select_avg($field);
        
        if ($param != null) {
            $this->db->where($param);
        }

        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }

        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $row = $query->row_array();
        
        $avg = $row[$field];
        
        return $avg;
    }
    
    /**
     * 求最大值结果
     * @param type $field
     * @param type $param
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function max($field, $param = array(), $inParams = array(), $likeParam=array(), $orParams=array()) {
        $this->db->select_max($field);
        
        if ($param != null) {
            $this->db->where($param);
        }

        if ($inParams != null) {
            foreach ($inParams as $inParamKey => $inParam) {
                $this->db->where_in($inParamKey, $inParam);
            }
        }

        //like数组
        if($likeParam != null) {
            $this->db->like($likeParam);
        }
        
        //or数组
        if ($orParams != NULL) {
            foreach ($orParams as $orParamKey => $orParam) {
                $this->db->or_where($orParamKey, $orParam);
            }
        }
        
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $row = $query->row_array();
        
        $max = $row[$field];
        
        return $max;
    }
    
    /**
     * 字段数量叠加更新
     * @param string $field
     * @param int $id
     * @return boolean 
     */
    public function updateCount($field, $id) {
        $sql = "UPDATE {$this->tableName} SET `$field`=`$field`+1 WHERE id=$id";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
