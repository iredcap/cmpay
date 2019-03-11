<?php

/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */

use app\common\logic\Log as LogicLog;
use think\Db;

// 应用公共文件

/**
 * 检测管理用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_admin_login()
{
    $user = session('admin_auth');
    if (empty($user)) {

        return false;
    } else {
        return session('admin_auth_sign') == data_auth_sign($user) ? $user['id'] : false;
    }
}

/**
 * 检测商户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login()
{
    $user = session('user_auth');
    if (empty($user)) {

        return false;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : false;
    }
}

/**
 * 清除登录 session
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
function clear_admin_login_session()
{
    session('admin_info',      null);
    session('admin_auth',      null);
    session('admin_auth_sign', null);
}

/**
 * 清除登录 session
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
function clear_user_login_session()
{
    session('user_info',      null);
    session('user_auth',      null);
    session('user_auth_sign', null);
}


/**
 * 数据签名认证
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @return string
 */
function data_auth_sign($data)
{

    // 数据类型检测
    if (!is_array($data)) {

        $data = (array)$data;
    }

    // 排序
    ksort($data);

    // url编码并生成query字符串
    $code = http_build_query($data);

    // 生成签名
    $sign = sha1($code);

    return $sign;
}


/**
 * 记录行为日志
 */
function action_log($name = '', $describe = '')
{

    $logLogic = get_sington_object('logLogic', LogicLog::class);

    $logLogic->logAdd($name, $describe);
}


/**
 * 获取单例对象
 */
function get_sington_object($object_name = '', $class = null)
{

    $request = request();

    $request->__isset($object_name) ?: $request->bind($object_name, new $class());

    return $request->__get($object_name);
}

/**
 * 使用上面的函数与系统加密KEY完成字符串加密
 * @param  string $str 要加密的字符串
 * @return string
 */
function data_md5_key($str, $key = 'Iredcap')
{

    if (is_array($str)) {

        ksort($str);

        $data = http_build_query($str);

    } else {

        $data = (string) $str;
    }

    return empty($key) ? data_md5($data,config('secret.data_salt')) : data_md5($data, $key);
}

/**
 * 系统非常规MD5加密方法
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param  string $str 要加密的字符串
 * @param string $key
 * @return string
 */
function data_md5($str, $key = 'Iredcap')
{

    return '' === $str ? '' : md5(sha1($str) . $key);
}


/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0)
{

    // 创建Tree
    $tree = [];

    if (!is_array($list)) {

        return false;
    }

    // 创建基于主键的数组引用
    $refer = [];

    foreach ($list as $key => $data) {

        $refer[$data[$pk]] =& $list[$key];
    }

    foreach ($list as $key => $data) {

        // 判断是否存在parent
        $parentId =  $data[$pid];

        if ($root == $parentId) {

            $tree[] =& $list[$key];

        } else if (isset($refer[$parentId])){

            is_object($refer[$parentId]) && $refer[$parentId] = $refer[$parentId]->toArray();

            $parent =& $refer[$parentId];

            $parent[$child][] =& $list[$key];
        }
    }

    return $tree;
}

/**
 * 分析数组及枚举类型配置值 格式 a:名称1,b:名称2
 * @return array
 */
function parse_config_attr($string)
{

    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));

    if (strpos($string, ':')) {

        $value = [];

        foreach ($array as $val) {

            list($k, $v) = explode(':', $val);

            $value[$k] = $v;
        }

    } else {

        $value = $array;
    }

    return $value;
}


/**
 * 将二维数组数组按某个键提取出来组成新的索引数组
 */
function array_extract($array = [], $key = 'id')
{

    $count = count($array);

    $new_arr = [];

    for($i = 0; $i < $count; $i++) {

        if (!empty($array) && !empty($array[$i][$key])) {

            $new_arr[] = $array[$i][$key];
        }
    }

    return $new_arr;
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
function arr2str($arr, $glue = ',')
{
    return implode($glue, $arr);
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 */
function str2arr($str, $glue = ',')
{
    return explode($glue, preg_replace('/[ ]/', '', $str));
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function arr2obj($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)arr2obj($v);
        }
    }

    return (object)$arr;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function obj2arr($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)obj2arr($v);
        }
    }

    return $obj;
}

/**
 * 字符串替换
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $str
 * @param string $target
 * @param string $content
 * @return mixed
 */
function sr($str = '', $target = '', $content = '')
{

    return str_replace($target, $content, $str);
}

/**
 * 字符串前缀验证
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $str
 * @param $prefix
 * @return bool
 */
function str_prefix($str, $prefix)
{

    return strpos($str, $prefix) === 0 ? true : false;
}

/**
 * 生成支付订单号
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return string
 */
function create_order_no()
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn =
        $yCode[intval(date('Y')) - 2018] . date('YmdHis') . strtoupper(dechex(date('m')))
        . date('d') . sprintf('%02d', rand(0, 999));
    return $orderSn;
}

/**
 * 生成唯一的订单号 20110809111259232312
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return string
 */
function create_general_no() {
    list($usec, $sec) = explode(" ", microtime());
    $usec = substr(str_replace('0.', '', $usec), 0 ,4);
    $str  = rand(10,99);
    return date("YmdHis").$usec.$str;
}

/**
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $url
 * @param $rawData
 * @param string $target
 * @param int $retry
 * @param int $sleep
 * @param int $second
 * @return mixed
 */
function curl_post_raw($url, $rawData, $target = 'FAIL', $retry=6, $sleep = 3 ,$second = 30)
{
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: text'
        )
    );
    //运行curl
    $output = curl_exec($ch);
    while (strpos($output, $target) !== false && $retry--) {
        //检查$targe是否存在
        sleep($sleep); //阻塞3s
        $sleep += 2;
        $output = curl_exec($ch);
    }
    curl_close($ch);
    return $output;
}

/**
 * 获取随机字符
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $length
 * @param $format
 * @return null|string
 */
function getRandChar($length = '4',$format = 'ALL')
{
    switch($format){
        case 'ALL':
            $strPol='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        case 'CHAR':
            $strPol='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 'NUM':
            $strPol='0123456789';
            break;
        default :
            $strPol='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
    }
    $str = null;
    //$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;
    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}

/**
 * 月赋值
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $array
 * @param $key
 * @return array
 */
function get_order_month_stat($array,$key){
    $month = 12;
    $newArr = [];
    for($i = 1; $i <= $month; $i++) {
        $newArr[$i] = 0;
    }
    foreach ($array as $v){
        $newArr[$v['month']] = (float)$v[$key];
    }
    return ($newArr);
}

/**
 * 下划线转驼峰
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $uncamelized_words
 * @param string $separator
 * @return string
 */
function camelize($uncamelized_words,$separator='_'){

    $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}


/**
 * 驼峰命名转下划线命名
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $camelCaps
 * @param string $separator
 * @return string
 */
function uncamelize($camelCaps,$separator='_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**
 * 获取到微秒
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return float
 */
function getMicroTime(){
    list($s1, $s2) = explode(' ', microtime());
     return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

/**
 * url参数转化成数组
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $query
 *
 * @return array
 */
function convertUrlArray($query)
{
    $queryParts = explode('&', $query);

    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }

    return $params;
}


// +---------------------------------------------------------------------+
// | 其他函数
// +---------------------------------------------------------------------+

/**
 * 通过类创建逻辑闭包
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param null $object
 * @param string $method_name
 * @param array $parameter
 *
 * @return Closure
 */
function create_closure($object = null, $method_name = '', $parameter = [])
{

    $func = function() use($object, $method_name, $parameter) {

        return call_user_func_array([$object, $method_name], $parameter);
    };

    return $func;
}

/**
 * 通过闭包控制缓存
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $key
 * @param null $func
 * @param int $time
 *
 * @return mixed
 */
function auto_cache($key = '', $func = '', $time = 3)
{

    $result = cache($key);

    if (empty($result)) {

        $result = $func();

        !empty($result) && cache($key, $result, $time);
    }

    return $result;
}

/**
 * 通过闭包列表控制事务
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param array $list
 *
 * @return bool
 * @throws Exception
 */
function closure_list_exe($list = [])
{

    Db::startTrans();

    try {

        foreach ($list as $closure) {

            $closure();
        }

        Db::commit();

        return true;
    } catch (\Exception $e) {

        Db::rollback();

        throw $e;
    }
}
