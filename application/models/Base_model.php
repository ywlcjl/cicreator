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
     * @param type $param 支持in not_in like not_like
     * @param type $limit
     * @param type $start
     * @param type $orderBy
     * @param type $orParams
     * @return type
     */
    public function getResult($param = array(), $limit = 0, $start = 0, $orderBy = 'id DESC', $orParam=array()) {
        $this->db->from($this->tableName);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
        $this->db->order_by($orderBy);
        
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
     * @param type $param 支持in not_in like not_like
     * @param type $orParam
     * @return type
     */
    public function getRow($param = array(), $orParam=array()) {
        $this->db->from($this->tableName);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
        $query = $this->db->get();
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
        if (is_array($param) && $param != null) {
            $this->db->update($this->tableName, $data, $param);
        }
        
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
     * @param type $orParam
     * @return type
     */
    public function count($param = array(), $orParam = array()) {
        $this->db->from($this->tableName);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
        $count = $this->db->count_all_results();
        
        return $count;
    }
    
    /**
     * 求和
     * @param type $field
     * @param type $param
     * @param type $orParam
     * @return type
     */
    public function sum($field, $param = array(), $orParam = array()) {
        $this->db->select_sum($field);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
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
    public function avg($field, $param = array(), $orParam=array()) {
        $this->db->select_avg($field);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
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
     * @param type $orParam
     * @return type
     */
    public function max($field, $param = array(), $orParam=array()) {
        $this->db->select_max($field);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $row = $query->row_array();
        
        $max = $row[$field];
        
        return $max;
    }
    
    /**
     * 获取最小
     * @param type $field
     * @param type $param
     * @param type $orParam
     * @return type
     */
    public function min($field, $param = array(), $orParam=array()) {
        $this->db->select_min($field);
        
        //高级查询条件处理
        $this->advWhere($param);
        $this->advOrWhere($orParam);
        
        $this->db->from($this->tableName);
        $query = $this->db->get();
        $row = $query->row_array();
        
        $min = $row[$field];
        
        return $min;
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
    
    /**
     * 获取sql分页处理
     * @param type $sql
     * @param type $per
     * @param type $suffix
     * @return type
     */
    public function getPageSql($sql, $url, $pagePer=20, $suffix='', $pageStyle='pageDefault') {
        $result = array();
        //分页参数
        $pageUrl = $url;

        $urlArray = explode('/', $url);
        $pageUri = 1;
        foreach($urlArray as $key=>$value) {
            if ($value) {
                $pageUri++;
            }
        }
        
        //计算分页起始条目
        $nowPageUri = intval($this->uri->segment($pageUri));
        $pageNum = $nowPageUri ? $nowPageUri : 1;
        $startRow = ($pageNum - 1) * $pagePer;
        
        //使用正则替换SQL, 生成count(*)统计查询分页总数
        $countSql = $sql;
        
        $pattern = '/^SELECT.*FROM/i';
        $queryCount = preg_replace($pattern,'SELECT COUNT(*) AS total FROM', $countSql); 

        //返回数组
        $query = $this->db->query($queryCount);
        $row = $query->row_array();
        $total = $row['total'];
        
        //获取数据
        if ($total > 0) {            
            $sql .= " LIMIT $startRow, $pagePer";
            $query = $this->db->query($sql);
            $result = $query->result_array();
        }
        
        //自定义分页处理方法, 默认为pageDefault方法, 可以根据实际情况添加不同的样式方法
        $this->$pageStyle($pageUrl, $pageUri, $pagePer, $total, $suffix);
        
        return $result;
    }
    
    public function getPage($url, $pagePer = 20, $suffix = '', $param = array(), $orderBy = 'id DESC', $orParam=array(), $pageStyle='pageDefault') {
        //分页参数
        $pageUrl = $url;
        
        $urlArray = explode('/', $url);
        $pageUri = 1;
        foreach($urlArray as $key=>$value) {
            if ($value) {
                $pageUri++;
            }
        }
        
        //计算分页起始条目
        $nowPageUri = intval($this->uri->segment($pageUri));
        $pageNum = $nowPageUri ? $nowPageUri : 1;
        $startRow = ($pageNum - 1) * $pagePer;

        $total = $this->count($param, $orParam);

        //获取数据
        $result = $this->getResult($param, $pagePer, $startRow, $orderBy, $orParam);

        //自定义分页处理方法, 默认为pageDefault方法, 可以根据实际情况添加不同的样式方法
        $this->$pageStyle($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接
        
        return $result;
    }

    protected function pageDefault($baseUrl, $uriSegment, $perPage = 20, $totalRows = 200, $suffix = '') {
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = $baseUrl;         //路径 site_url() . '/backend/admin/index';
        $config['uri_segment'] = $uriSegment;   //路由参数
        $config['total_rows'] = $totalRows;     //总数
        $config['per_page'] = $perPage;         //每页显示
        $config['suffix'] = $suffix;            //后缀
        $config['num_links'] = 5;               //显示页数
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

        $this->pagination->initialize($config);
    }

        /**
     * 高级db查询器条件生成
     * @param type $params
     */
    protected function advWhere($params = array()) {
        $magicArray = array('in', 'not_in', 'like', 'not_like');
        
        if ($params && is_array($params)) {
            $paramCommon = array();
            foreach($params as $key=>$value) {
                $keyArray = explode(' ', $key);
                $keyField = $keyArray[0];
                //关键字
                $keyName = $keyArray[1];
                if ($keyName && in_array($keyName, $magicArray)) {
                    //存在判断符号
                    if ($keyName == 'in') {
                        $this->db->where_in($keyField, $value);
                    } else if($keyName == 'not_in') {
                        $this->db->where_not_in($keyField, $value);
                    } else if($keyName == 'like') {
                        //like %value% 特殊处理
                        $symbolLeft = mb_substr($value, 0, 1);
                        $symbolRight = mb_substr($value, -1, 1);
                        if ($symbolLeft == '%' && $symbolRight != '%') {
                            $likeStr = mb_substr($value, 1);
                            $this->db->like($keyField, $likeStr, 'before');
                        } else if($symbolLeft != '%' && $symbolRight == '%') {
                            $likeStr = mb_substr($value, 0, -1);
                            $this->db->like($keyField, $likeStr, 'after');
                        } else {
                            $this->db->like($keyField, $value, 'both');
                        }
                    } else if ($keyName == 'not_like') {
                        $symbolLeft = mb_substr($value, 0, 1);
                        $symbolRight = mb_substr($value, -1, 1);
                        if ($symbolLeft == '%' && $symbolRight != '%') {
                            $likeStr = mb_substr($value, 1);
                            $this->db->not_like($keyField, $likeStr, 'before');
                        } else if($symbolLeft != '%' && $symbolRight == '%') {
                            $likeStr = mb_substr($value, 0, -1);
                            $this->db->not_like($keyField, $likeStr, 'after');
                        } else {
                            $this->db->not_like($keyField, $value, 'both');
                        }
                    }
                } else {
                    $paramCommon[$key] = $value;
                }
            }
            
            //放入where的数据
            if ($paramCommon) {
                $this->db->where($paramCommon);
            }
            
        }
    }
    
    /**
     * 高级db查询器条件生成 OR
     * @param type $orParams
     */
    protected function advOrWhere($orParams = array()) {
        $magicArray = array('in', 'not_in', 'like', 'not_like');
        
        if ($orParams && is_array($orParams)) {
            $paramCommon = array();
            foreach($orParams as $key=>$value) {
                $keyArray = explode(' ', $key);
                $keyField = $keyArray[0];
                //关键字
                $keyName = $keyArray[1];
                if ($keyName && in_array($keyName, $magicArray)) {
                    //存在判断符号
                    if ($keyName == 'in') {
                        $this->db->or_where_in($keyField, $value);
                    } else if($keyName == 'not_in') {
                        $this->db->or_where_not_in($keyField, $value);
                    } else if($keyName == 'like') {
                        //like %value% 特殊处理
                        $symbolLeft = mb_substr($value, 0, 1);
                        $symbolRight = mb_substr($value, -1, 1);
                        if ($symbolLeft == '%' && $symbolRight != '%') {
                            $likeStr = mb_substr($value, 1);
                            $this->db->or_like($keyField, $likeStr, 'before');
                        } else if($symbolLeft != '%' && $symbolRight == '%') {
                            $likeStr = mb_substr($value, 0, -1);
                            $this->db->or_like($keyField, $likeStr, 'after');
                        } else {
                            $this->db->or_like($keyField, $value, 'both');
                        }
                    } else if ($keyName == 'not_like') {
                        $symbolLeft = mb_substr($value, 0, 1);
                        $symbolRight = mb_substr($value, -1, 1);
                        if ($symbolLeft == '%' && $symbolRight != '%') {
                            $likeStr = mb_substr($value, 1);
                            $this->db->or_not_like($keyField, $likeStr, 'before');
                        } else if($symbolLeft != '%' && $symbolRight == '%') {
                            $likeStr = mb_substr($value, 0, -1);
                            $this->db->or_not_like($keyField, $likeStr, 'after');
                        } else {
                            $this->db->or_not_like($keyField, $value, 'both');
                        }
                    }
                } else {
                    $paramCommon[$key] = $value;
                }
            }
            
            //放入where的数据
            if ($paramCommon) {
                $this->db->or_where($paramCommon);
            }
            
        }
    }

}
