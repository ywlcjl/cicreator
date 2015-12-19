<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 常用辅助函数
 */
if (!function_exists('cg_to_link')) {

    /**
     * 跳转链接
     * @param type $url
     */
    function cg_to_link($url) {
        header("Location:$url");
        exit;
    }

}

if (!function_exists('cg_get_img_path')) {

    /**
     * 获取缩略图文件名
     * @param type $path
     * @param type $size
     * @return type
     */
    function cg_get_img_path($path, $size = 'thumb') {
        $newPath = '';

        if ($size) {
            $newPath = substr($path, 0, -(strlen($path) - strrpos($path, '.'))) . '_' . $size . substr($path, strrpos($path, '.'));
        } else {
            $newPath = $path;
        }

        return $newPath;
    }

}


if (!function_exists('cg_get_file_type')) {

    /**
     * 返回文件类型名
     * @param type $src
     * @return type
     */
    function cg_get_file_type($src) {
        $result = substr($src, strrpos($src, '.') + 1, strlen($src));
        //返回jpg, png
        return $result;
    }

}


if (!function_exists('cg_get_price_format')) {

    /**
     * 获取价格格式
     * @param type $price
     * @return type
     */
    function cg_get_price_format($price) {
        return sprintf('%01.2f', $price);
    }

}

if (!function_exists('cg_substr_utf8')) {

    /**
     * 完整字数的utf8截取
     * @param type $string
     * @param type $start
     * @param type $length
     * @return type
     */
    function cg_substr_utf8($string, $start, $length) {
        $chars = $string;
        //echo $string[0].$string[1].$string[2];   
        $i = 0;
        do {
            if (preg_match("/[0-9a-zA-Z]/", $chars[$i])) {//纯英文   
                $m++;
            } else {
                $n++;
            }//非英文字节,   
            $k = $n / 3 + $m / 2;
            $l = $n / 3 + $m; //最终截取长度；$l = $n/3+$m*2？   
            $i++;
        } while ($k < $length);
        $str1 = mb_substr($string, $start, $l, 'utf-8'); //保证不会出现乱码   
        return $str1;
    }

}

if (!function_exists('cg_curl_post')) {

    /**
     * 模拟post提交
     * @param type $url
     * @param type $vars
     * @return boolean
     */
    function cg_curl_post($url, $vars = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($vars));
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
}

if (!function_exists('cg_clean')) {

    /**
     * 过滤函数
     * @param type $string
     * @return type 
     */
    function cg_clean($string) {
        $string = trim($string);
        //需要指定编码,否则乱码
        $string = htmlspecialchars($string, ENT_QUOTES, "UTF-8");
        //剥掉html标签
//		$string = strip_tags($string);
        return $string;
    }

}

if (!function_exists('cg_object_to_array')) {

    /**
     * stdClass 对象转化成数组
     * @param type $array
     * @return type 
     */
    function cg_object_to_array($array) {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = cg_object_to_array($value);
            }
        }
        return $array;
    }

}

if (!function_exists('cg_get_hump_str')) {
    /**
     * 字符串转驼峰字符串
     * @param type $str
     * @param type $dstr
     * @return type
     */
    function cg_get_hump_str($str, $dstr = '_') {
        $result = '';
        $sTemp = explode($dstr, $str);
        if ($sTemp) {
            foreach ($sTemp as $key=>$value) {
                if ($key > 0) {
                    $result .= ucfirst($value);
                } else {
                    $result .= $value;
                }
            }
        }
        
        return $result;
    }

}

/* End of file MY_common_helper.php */