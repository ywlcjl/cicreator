<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 代码生成
 */
class Create extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('backend_lib');

        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(1);
    }

    /**
     * 首页
     */
    public function index() {
        $data = array();
        if ($this->input->post('post', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('table', '数据表名称', 'required|trim');

            $tableName = $this->input->post('table', TRUE);

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //查询数据
                $dbInformation = 'information_schema';
                $infTable = 'COLUMNS';
                $dbName = $this->db->database;

                //连接数据库
                $dbh = new PDO($this->db->dbdriver . ':host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh->query("SET NAMES 'UTF8'");

                $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE`,`CHARACTER_MAXIMUM_LENGTH`, `COLUMN_TYPE`, `COLUMN_COMMENT` FROM $infTable WHERE `TABLE_SCHEMA`='$dbName' AND `TABLE_NAME`='$tableName'";

                $sth = $dbh->prepare($sql);
                $sth->execute();
                $result = $sth->fetchAll();

                if ($result) {
                    $message = '提交成功';
                    $success = TRUE;
                    $data['result'] = $result;
                    $data['tableName'] = $tableName;
                } else {
                    $message = '表格和字段结果为空.';
                }
            }

            if ($success) {
                //需要生成对应的文件数组
                $files = $this->_getFiles($tableName);

                $data['files'] = $files;
                $data['tableName'] = $tableName;
                $this->load->view('backend/create/next', $data);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/create/index', $data);
            }
        } else {
            //显示表单
            $this->load->view('backend/create/index', $data);
        }
    }

    /**
     * 准备生成代码
     */
    public function next() {
        $data = array();
        if ($this->input->post('post', TRUE) > 0) {
            //执行表单操作
            $this->form_validation->set_rules('table', '数据表名称', 'required|trim');

            $tableName = $this->input->post('table', TRUE);

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //查询数据
                $dbInformation = 'information_schema';
                $infTable = 'COLUMNS';
                $dbName = $this->db->database;

                //连接数据库
                $dbh = new PDO($this->db->dbdriver . ':host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh->query("SET NAMES 'UTF8'");

                $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE`,`CHARACTER_MAXIMUM_LENGTH`, `COLUMN_TYPE`, `COLUMN_COMMENT` FROM $infTable WHERE `TABLE_SCHEMA`='$dbName' AND `TABLE_NAME`='$tableName'";

                $sth = $dbh->prepare($sql);
                $sth->execute();
                $result = $sth->fetchAll();

                if ($result) {
                    $message = '提交成功';
                    $success = TRUE;
                    $data['result'] = $result;
                    $data['tableName'] = $tableName;
                } else {
                    $message = '表格和字段结果为空.';
                }
            }

            if ($success) {
                //需要生成对应的文件数组
                $files = $this->_getFiles($tableName);

                //生成写入model
                $modelStr = $this->_getModelStr($tableName, $result);
                touch($files['model']);
                file_put_contents($files['model'], $modelStr);

                //生成controller
                $controllerStr = $this->_getControllerStr($tableName, $result);
                touch($files['controller']);
                file_put_contents($files['controller'], $controllerStr);

                //生成目录
                $viewIndexDir = APP_DIR . '/views/' . BACKEND . '/' . strtolower($tableName);
                if (!is_dir($viewIndexDir)) {
                    mkdir($viewIndexDir, 0777);
                }
                
                //生成views/index
                $viewIndexStr = $this->_getViewIndexStr($tableName, $result);               
                touch($files['view_index']);
                file_put_contents($files['view_index'], $viewIndexStr);
                
                //生成views/save
                $viewSaveStr = $this->_getViewSaveStr($tableName, $result);
                touch($files['view_save']);
                file_put_contents($files['view_save'], $viewSaveStr);
                
                $data['tableName'] = $tableName;
                $this->load->view('backend/create/complete', $data);
            } else {
                $data['message'] = $message;
                $this->load->view('backend/create/next', $data);
            }
        }
    }

    private function _getFiles($tableName) {
        $files = array(
            'model' => APP_DIR . '/models/' . strtolower($tableName) . '_model.php',
            'controller' => APP_DIR . '/controllers/' . BACKEND . '/' . strtolower($tableName) . '.php',
            'view_index' => APP_DIR . '/views/' . BACKEND . '/' . strtolower($tableName) . '/' . 'index.php',
            'view_save' => APP_DIR . '/views/' . BACKEND . '/' . strtolower($tableName) . '/' . 'save.php',
        );
        return $files;
    }

    private function _getArrays($str) {
        $statuss = array();
        if (stripos($str, '$array$') !== FALSE) {
            $commentStr = substr($str, 7);
            if ($commentStr) {
                $arrayT = explode('|', $commentStr);
                if ($arrayT) {
                    foreach ($arrayT as $value) {
                        $arrayV = explode(':', $value);
                        if ($arrayV) {
                            $statuss[$arrayV[0]] = $arrayV[1];
                        }
                    }
                }
            }
        }

        return $statuss;
    }

    private function _getModelStr($tableName, $columns) {
        $tTableName = ucfirst($tableName);
        $str = <<<model
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * $tableName 模型
 */
class {$tTableName}_model extends Base_model {
    private \$_name='$tableName';

    public function __construct() {
        parent::__construct();
        \$this->tableName = \$this->_name;
    }

model;

        //是否存在定义的状态
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<model
   
    public function get{$firstColumnName}(\$key='') {
        \$data = array(
model;
                        foreach ($statuss as $sKey => $sValue) {
                            $str .= $sKey . " => '" . $sValue . "', ";
                        }

                        $str .= <<<model
);

        if (\$key !== '') {
            return \$data[\$key];
        } else {
            return \$data;
        }
    }
model;
                    }
                }
            }
        }


        //结尾
        $str .= <<<model

}

model;


        return $str;
    }

    /**
     * 生成控制器代码
     * @param type $tableName
     * @param type $columns
     * @return type
     */
    private function _getControllerStr($tableName, $columns) {
        //类名拼接
        $controllerClassName = ucfirst($tableName);

        $modelName = $tableName . '_model';

        $str = <<<sss
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * $tableName 控制器
 */
class $controllerClassName extends CI_Controller {

    public function __construct() {
        parent::__construct();
        \$this->load->library('backend_lib');

        \$this->load->model('$modelName');

sss;

        //是否存在关联id代码
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {
                    //获取关联id选项的的处理
                    $idStr = substr($column['COLUMN_COMMENT'], 4);

                    //生成代码
                    $str .= <<<sss
        \$this->load->model('{$idStr}_model');

sss;
                }
            }
        }

        $str .= <<<sss
        //检查登录
        \$this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        //\$this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        \$data = array();
        \$param = array();
        \$inParams = array();
        \$likeParam = array();


sss;

        //是否存在选项代码
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($column['COLUMN_COMMENT'], 4);

                    //关联id
                    $str .= <<<sss
        \$data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');

sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }

        //处理搜索筛选
        $str .= <<<sss

        //搜索筛选
        \$data['search'] = \$this->input->get('search', TRUE);
        if(\$data['search']) {


sss;

        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (in_array($column['DATA_TYPE'], array('char', 'varchar', 'text'))) {
                    //普通字符串
                    $str .= <<<sss
            \$data['{$column['COLUMN_NAME']}'] = \$this->input->get('{$column['COLUMN_NAME']}', TRUE);
            if(\$data['{$column['COLUMN_NAME']}']) {
                \$likeParam['{$column['COLUMN_NAME']}'] = \$data['{$column['COLUMN_NAME']}'];
            }


sss;
                } elseif (in_array($column['DATA_TYPE'], array('datetime', 'timestamp'))) {
                    //日期时间
                    $str .= <<<sss
            \$data['{$column['COLUMN_NAME']}_start'] = \$this->input->get('{$column['COLUMN_NAME']}_start', TRUE);
            \$data['{$column['COLUMN_NAME']}_end'] = \$this->input->get('{$column['COLUMN_NAME']}_end', TRUE);
            if (\$data['{$column['COLUMN_NAME']}_start'] && \$data['{$column['COLUMN_NAME']}_end']) {
                \$param['{$column['COLUMN_NAME']} >='] = date('Y-m-d', strtotime(\$data['{$column['COLUMN_NAME']}_start']));
                \$param['{$column['COLUMN_NAME']} <'] = date('Y-m-d', strtotime(\$data['{$column['COLUMN_NAME']}_end']));
            }


sss;
                } else if ($column['COLUMN_COMMENT'] == '$max$' && in_array($column['DATA_TYPE'], array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'decimal'))) {
                    //大小于号生成
                    $str .= <<<sss
            \$data['{$column['COLUMN_NAME']}_min'] = \$this->input->get('{$column['COLUMN_NAME']}_min', TRUE);
            if (\$data['{$column['COLUMN_NAME']}_min'] !== '') {
                \$param['{$column['COLUMN_NAME']} >='] = \$data['{$column['COLUMN_NAME']}_min'];
            }
            \$data['{$column['COLUMN_NAME']}_max'] = \$this->input->get('{$column['COLUMN_NAME']}_max', TRUE);
            if (\$data['{$column['COLUMN_NAME']}_max'] !== '') {
                \$param['{$column['COLUMN_NAME']} <'] = \$data['{$column['COLUMN_NAME']}_max'];
            }


sss;
                } else {
                    //普通的数字
                    $str .= <<<sss
            \$data['{$column['COLUMN_NAME']}'] = \$this->input->get('{$column['COLUMN_NAME']}', TRUE);
            if(\$data['{$column['COLUMN_NAME']}'] !== '') {
                \$param['{$column['COLUMN_NAME']}'] = \$data['{$column['COLUMN_NAME']}'];
            }


sss;
                }
            }
        }



        $str .= <<<sss
}

        //自动获取get参数
        \$urlGet = '';
        \$gets = \$this->input->get();
        if (\$gets) {
            \$i = 0;
            foreach (\$gets as \$getKey => \$get) {
                if (\$i) {
                    \$urlGet .= "&\$getKey=\$get";
                } else {
                    \$urlGet .= "/?\$getKey=\$get";
                }
                \$i++;
            }
        }
                
        //排序
        \$orderBy = \$this->input->get('orderBy', TRUE);
        \$orderBySQL = 'id DESC';
        if (\$orderBy == 'idASC') {
            \$orderBySQL = 'id ASC';
        }
        \$data['orderBy'] = \$orderBy;
                
        //分页参数
        \$pageUrl = B_URL.'$tableName/index';  //分页链接
        \$pageUri = 4;   //URL参数位置
        \$pagePer = 20;  //每页数量
        \$suffix = \$urlGet;   //GET参数
        //计算分页起始条目
        \$pageNum = intval(\$this->uri->segment(\$pageUri)) ? intval(\$this->uri->segment(\$pageUri)) : 1;
        \$startRow = (\$pageNum - 1) * \$pagePer;

        //获取数据
        \$result = \$this->{$modelName}->getResult(\$param, \$pagePer, \$startRow, \$orderBySQL, \$inParams, \$likeParam);

        //生成分页链接
        \$total = \$this->{$modelName}->count(\$param, \$inParams, \$likeParam);
        \$this->backend_lib->createPage(\$pageUrl, \$pageUri, \$pagePer, \$total, \$suffix);  //创建分页链接
        //获取联表结果
        if (\$result) {
            foreach (\$result as \$key => \$value) {

            }
        }

        \$data['result'] = \$result;

        \$this->load->view('backend/$tableName/index', \$data);
    }


sss;

        //----------------------- save 方法 ---------------------------
        $str .= <<<sss
    public function save() {
        \$data = array();

sss;
        //处理status
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($column['COLUMN_COMMENT'], 4);

                    //关联id
                    $str .= <<<sss
        \$data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');

sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);
                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }

        $str .= <<<sss

        if (\$this->input->post('save', TRUE) > 0) {

sss;

        //处理表单
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (!in_array($column['COLUMN_NAME'], array('update_time', 'create_time'))) {
                    
                }
                //生成代码
                $str .= <<<sss
            \$this->form_validation->set_rules('{$column['COLUMN_NAME']}', '{$column['COLUMN_NAME']}', 'trim');

sss;
            }
        }

        $str .= <<<sss

        \$param = array(

sss;

        //处理输入过滤
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                //初始时间不用输入
                if ($column['COLUMN_NAME'] == 'update_time') {
                    //对updatetime的特殊处理
                    $str .= <<<sss
            'update_time' => date('Y-m-d H:i:s'),

sss;
                } elseif (!in_array($column['DATA_TYPE'], array('timestamp'))) {
                    //生成代码
                    $str .= <<<sss
            '{$column['COLUMN_NAME']}' => \$this->input->post('{$column['COLUMN_NAME']}', TRUE),

sss;
                }
            }
        }

        //后半部分
        $str .= <<<sss

        );
            \$success = FALSE;
            \$message = '';

            if (\$this->form_validation->run() == FALSE) {
                \$message = '表单填写有误';
            } else {
                //保存记录
                \$save = \$this->{$modelName}->save(\$param);

                if (\$save) {
                    \$message = '保存成功';
                    \$success = TRUE;
                } else {
                    \$message = '保存失败';
                }
            }

            if (\$success) {
                \$this->backend_lib->showMessage(B_URL.'$tableName', \$message);
            } else {
                \$data['message'] = \$message;
                \$this->load->view('backend/$tableName/save', \$data);
            }
        } else {
            //显示记录的表单
            \$id = intval(\$this->input->get('id'));
            if (\$id) {
                \$data['row'] = \$this->{$modelName}->getRow(array('id' => \$id));
            }
            \$this->load->view('backend/$tableName/save', \$data);
        }
    }


sss;

        //----------------------- manage ------------------------
        $str .= <<<sss
    public function manage() {
        \$data = array();
        \$this->form_validation->set_rules('ids[]', 'Ids', 'required');
        \$this->form_validation->set_rules('manageName', '操作名称', 'required');

        \$manageName = \$this->input->post('manageName', TRUE);
        \$ids = \$this->input->post('ids', TRUE);

        \$success = FALSE;
        \$message = '';

        if (\$this->form_validation->run() == FALSE) {
            \$message = '表单填写有误';
        } else {
            if (\$ids != null) {
                if (\$manageName == 'delete') {
                    //删除记录
                    foreach (\$ids as \$key => \$id) {
                        \$param = array(
                            'id' => \$id,
                        );
                        \$this->{$modelName}->delete(\$param);
                    }
                    \$message = '删除成功';

sss;

        //处理状态
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {

                    //关联id
                    $str .= <<<sss
                } elseif (\$manageName == 'set_{$column['COLUMN_NAME']}') {
                    \$setValue = \$this->input->post('set_{$column['COLUMN_NAME']}', TRUE);
                    if (\$setValue !== '') {
                        foreach (\$ids as \$key => \$id) {
                            \$param = array(
                                'id' => \$id,
                                '{$column['COLUMN_NAME']}' => \$setValue,
                            );
                            \$this->{$modelName}->save(\$param);
                        }
                        \$message = '操作成功';
                    } else {
                        \$message = '设置不能为空.';
                    }


sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
                } elseif (\$manageName == 'set_{$column['COLUMN_NAME']}') {
                    \$setValue = \$this->input->post('set_{$column['COLUMN_NAME']}', TRUE);
                    if (\$setValue !== '') {
                        foreach (\$ids as \$key => \$id) {
                            \$param = array(
                                'id' => \$id,
                                '{$column['COLUMN_NAME']}' => \$setValue,
                            );
                            \$this->{$modelName}->save(\$param);
                        }
                        \$message = '操作成功';
                    } else {
                        \$message = '设置不能为空.';
                    }

sss;
                    }
                }
                
            }
        }

        $str .= <<<sss
                }
            }
        }

        \$this->backend_lib->showMessage(B_URL. '$tableName', \$message);
    }
}

sss;


        return $str;
    }
    
    private function _getViewIndexStr($tableName, $columns) {
        //驼峰命名
        $tTableName = cg_get_hump_str($tableName);

                
        $str = <<<sss
<?php \$this->load->view('backend/_header'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        //表单全选
        checkAll();
        
        //表格行效果
        trView();

        //搜索框
        searchForm();

        //加载日期
        $('input.datepicker').Zebra_DatePicker();

        //搜索框隐藏bug修正, 必须放在日期插件加载后隐藏
        <?php if (!\$search) : ?>
            $('#searchForm').css('display', 'none');
        <?php endif; ?>

        //管理操作
        $('#manageName').change(function() {
            var manageName = $(this).val();

sss;
        
        //处理状态
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {

                    //关联id
                    $str .= <<<sss
            if(manageName == 'set_{$column['COLUMN_NAME']}') {
                $('#set_{$column['COLUMN_NAME']}').css('display', '');
            } else {
                $('#set_{$column['COLUMN_NAME']}').css('display', 'none');
            }


sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
            if(manageName == 'set_{$column['COLUMN_NAME']}') {
                $('#set_{$column['COLUMN_NAME']}').css('display', '');
            } else {
                $('#set_{$column['COLUMN_NAME']}').css('display', 'none');
            }

sss;
                    }
                }
                
            }
        }
        
        
        $str .= <<<sss
        });
    });
</script>

<div class="main">
    <div class="mainLeft">
        <?php \$this->load->view('backend/_menu', array('onView' => '$tTableName')); ?>
    </div>
    <div class="mainRight">
        <div class="block1">
            <div class="titleStyle1">{$tableName}列表<span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>{$tableName}/save" class="white">添加$tableName</a></span></div>
            <div class="contentBlock1">
                <form name="form1" id="form1" method="post" action="<?php echo B_URL; ?>$tableName/manage">
                    <table class="listForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="listFormHeader">

sss;
        

        //表格头部
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == 'id') {
                    $str .= <<<sss
                            <th><b>ID</b></th>

sss;
                } else {
                    $str .= <<<sss
                            <td>{$column['COLUMN_NAME']}</td>

sss;
                }
            }
        }
        
        $str .= <<<sss
                            <td>操作</td>
                        </tr>
                        <?php if (\$result != null) : ?>
                            <?php foreach (\$result as \$value) : ?>
                                <tr class="listFormContent">

sss;
        
        //表格中部
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == 'id') {
                    $str .= <<<sss
                                    <th><input type="checkbox" name="ids[]" class="ids" value="<?php echo \$value['id']; ?>" /> <?php echo \$value['id']; ?></th>

sss;
                } elseif($column['COLUMN_COMMENT'] &&  stripos($column['COLUMN_COMMENT'], '$array$') !== FALSE) {
                    //直接代入数组
                    $str .= <<<sss
                                    <td><?php echo \${$column['COLUMN_NAME']}s[\$value['{$column['COLUMN_NAME']}']]; ?></td>

sss;
                } else {
                    $str .= <<<sss
                                    <td><?php echo \$value['{$column['COLUMN_NAME']}']; ?></td>

sss;
                }
            }
        }
        
        $str .= <<<sss
                                    <td>
                                        <a href="<?php echo B_URL; ?>$tableName/save/?id=<?php echo \$value['id']; ?>">编辑</a> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                    <div class="pageLink"><?php echo \$this->pagination->create_links(); ?></div>
                    <div class="listFormManage">
                        <a href="#" id="checkAll">全选</a>
                        <select name="manageName" id="manageName">
                            <option value="">请选择</option>
                            <option value="delete">删除</option>

sss;
        
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {

                    //关联id
                    $str .= <<<sss
                            <option value="set_{$column['COLUMN_NAME']}">设置{$column['COLUMN_NAME']}</option>

sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
                            <option value="set_{$column['COLUMN_NAME']}">设置{$column['COLUMN_NAME']}</option>

sss;
                    }
                }
                
            }
        }
        
        $str .= <<<sss
                        </select>

sss;
        
        //隐藏筛选框
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE) {
                    $idStr = substr($column['COLUMN_COMMENT'], 4);
                    
                    //关联id
                    $str .= <<<sss
                        <select name="set_{$column['COLUMN_NAME']}" id="set_{$column['COLUMN_NAME']}" style="display: none;">
                            <option value="">请选择</option>
                            <?php if (\${$idStr}s != null) : ?>
                                <?php foreach (\${$idStr}s as \$key=>\$value) : ?>
                                    <option value="<?php echo \$value['id']; ?>"><?php echo \$value['id']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

sss;
                } elseif ($column['COLUMN_COMMENT']) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
                        <select name="set_{$column['COLUMN_NAME']}" id="set_{$column['COLUMN_NAME']}" style="display: none;">
                            <option value="">请选择</option>
                            <?php if (\${$column['COLUMN_NAME']}s != null) : ?>
                                <?php foreach (\${$column['COLUMN_NAME']}s as \$key=>\$value) : ?>
                                    <option value="<?php echo \$key; ?>"><?php echo \$value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

sss;
                    }
                }
                
            }
        }
        
        $str .= <<<sss
                        <input type="submit" name="manageButton" id="manageButton"  value="操作" onclick="return confirmAction();" />
                    </div>
                </form>
            </div>

        <div class="blank30"></div>

        <div class="block1">
            <div class="titleStyle1">
                <a href="#" id="searchFormTitle" class="white">搜索筛选+</a><span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>{$tableName}/index" class="white">清空条件</a></span>
            </div>
            <div class="contentBlock1" id="searchForm">
                <form action="<?php echo B_URL; ?>{$tableName}/index/" method="get">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">

sss;
                
        //处理筛选条件
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {

                if($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE){
                    //关联id
                    $idStr = substr($column['COLUMN_COMMENT'], 4);
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <select name="{$column['COLUMN_NAME']}">
                                    <option value="">请选择</option>
                                    <?php if (\${$idStr}s != null) : ?>
                                        <?php foreach (\${$idStr}s as \$value) : ?>
                                            <option value="<?php echo \$value['id']; ?>" <?php if (\${$column['COLUMN_NAME']} === \$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo \$value['id']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td></td>
                        </tr>

sss;
                    
                } elseif ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    if ($statuss) {
                        $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <select name="{$column['COLUMN_NAME']}">
                                    <option value="">请选择</option>
                                    <?php if (\${$column['COLUMN_NAME']}s != null) : ?>
                                        <?php foreach (\${$column['COLUMN_NAME']}s as \$key=>\$value) : ?>
                                            <option value="<?php echo \$key; ?>" <?php if (\${$column['COLUMN_NAME']} === (string)\$key) : ?>selected="selected"<?php endif; ?>><?php echo \$value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td></td>
                        </tr>

sss;
                    }
                
                } elseif (in_array($column['DATA_TYPE'], array('datetime', 'timestamp'))) {
                    //日期时间
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <input name="{$column['COLUMN_NAME']}_start" class="datepicker" type="text" value="<?php echo \${$column['COLUMN_NAME']}_start; ?>" /> - <input class="datepicker" name="{$column['COLUMN_NAME']}_end" type="text" value="<?php echo \${$column['COLUMN_NAME']}_end; ?>" />
                            </td>
                            <td></td>
                        </tr>


sss;
                } else if ($column['COLUMN_COMMENT'] == '$max$' && in_array($column['DATA_TYPE'], array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'decimal'))) {
                    //大小于号生成
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}范围:</th>
                            <td>
                                <input name="{$column['COLUMN_NAME']}_min" type="text" value="<?php echo \${$column['COLUMN_NAME']}_min; ?>" /> - <input name="{$column['COLUMN_NAME']}_max" type="text" value="<?php echo \${$column['COLUMN_NAME']}_max; ?>" />
                            </td>
                            <td></td>
                        </tr>


sss;
                } else {
                    //普通的数字
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <input name="{$column['COLUMN_NAME']}" type="text" value="<?php echo \${$column['COLUMN_NAME']}; ?>" />
                            </td>
                            <td></td>
                        </tr>


sss;
                }
            }
        }
                

        //结尾
        $str .= <<<model
                    </table>
                    <input name="search" type="hidden" value="1" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="搜索" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php \$this->load->view('backend/_footer'); ?>

model;


        return $str;
    
    }
    
    private function _getViewSaveStr($tableName, $columns) {
        //驼峰命名
        $tTableName = cg_get_hump_str($tableName);
        
        $str = <<<model
<?php \$this->load->view('backend/_header'); ?>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
<div class="main">
    <div class="mainLeft">
        <?php \$this->load->view('backend/_menu', array('onView' => '$tTableName')); ?>
    </div>
    <div class="mainRight">
        <div class="block1">
            <div class="titleStyle1">
                $tableName 编辑<span class="titleStyleRight"><span class="titleStyleRightLine">|</span><a href="<?php echo B_URL; ?>$tableName/index" class="white">返回列表</a></span>
            </div>
            <div class="contentBlock1">
                <?php if (\$message) : ?>
                    <div class="errorMessage">
                        <?php echo \$message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo B_URL; ?>$tableName/save" method="post">
                    <table class="inputForm" width="95%" align="center" border="0" cellpadding="0" cellspacing="0">

model;

        //save条件
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {

                if($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$id$') !== FALSE){
                    //关联id
                    $idStr = substr($column['COLUMN_COMMENT'], 4);
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <select name="{$column['COLUMN_NAME']}">
                                    <?php if (\${$idStr}s != null) : ?>
                                        <?php foreach (\${$idStr}s as \$value) : ?>
                                            <option value="<?php echo \$value['id']; ?>" <?php if (\$row['{$column['COLUMN_NAME']}'] === (string)\$value['id'] || set_value('{$column['COLUMN_NAME']}') === (string)\$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo \$value['id']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                            </td>
                            <td></td>
                        </tr>


sss;
                    
                } elseif ($column['COLUMN_COMMENT'] && stripos($column['COLUMN_COMMENT'], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($column['COLUMN_COMMENT']);

                    if ($statuss) {
                        $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <select name="{$column['COLUMN_NAME']}">
                                    <?php if (\${$column['COLUMN_NAME']}s != null) : ?>
                                        <?php foreach (\${$column['COLUMN_NAME']}s as \$key=>\$value) : ?>
                                            <option value="<?php echo \$key; ?>" <?php if (\$row['{$column['COLUMN_NAME']}'] === (string)\$key || set_value('{$column['COLUMN_NAME']}') === (string)\$key) : ?>selected="selected"<?php endif; ?>><?php echo \$value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                            </td>
                            <td></td>
                        </tr>


sss;
                    }
                
                } else if (in_array($column['DATA_TYPE'], array('text'))) {
                    //文本框
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <textarea name="{$column['COLUMN_NAME']}" cols="80" rows="5"><?php echo \$this->backend_lib->getValue(set_value('{$column['COLUMN_NAME']}'), \$row['{$column['COLUMN_NAME']}']); ?></textarea>
                                <?php echo form_error('content'); ?>
                            </td>
                            <td></td>
                        </tr>


sss;
                } else if(!in_array($column['COLUMN_NAME'], array('id', 'create_time', 'update_time'))) {
                    //普通
                    $str .= <<<sss
                        <tr>
                            <th>{$column['COLUMN_NAME']}:</th>
                            <td>
                                <input name="{$column['COLUMN_NAME']}" type="text" value="<?php echo \$this->backend_lib->getValue(set_value('{$column['COLUMN_NAME']}'), \$row['{$column['COLUMN_NAME']}']); ?>" />
                                <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                            </td>
                            <td></td>
                        </tr>


sss;
                }
            }
        }
        

        //结尾
        $str .= <<<model
                    </table>
                    <input name="save" type="hidden" value="1" />
                    <input name="id" type="hidden" value="<?php echo \$this->backend_lib->getValue(set_value('id'), \$row['id']); ?>" />
                    <div class="inputFormSubmit">
                        <input type="submit" name="button"  value="保存" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php \$this->load->view('backend/_footer'); ?>

model;


        return $str;
    }

}