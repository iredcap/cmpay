<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------


// 应用公共文件
use think\Db;
use app\common\logic\ActionLog;

// +---------------------------------------------------------------------+
// | 用户函数
// +---------------------------------------------------------------------+

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
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $name
 * @param string $describe
 *
 */
function action_log($name = '', $describe = '')
{

    $logLogic = get_sington_object('logLogic', ActionLog::class);

    $logLogic->logAdd($name, $describe);
}

/**
 * 获取单例对象
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $object_name
 * @param null $class
 *
 * @return object
 */
function get_sington_object($object_name = '', $class = null)
{
    $container = app();

    $container->has($object_name) ?: $container->bindTo($object_name, $class);

    return $container->make($object_name);
}


// +---------------------------------------------------------------------+
// | 数组函数
// +---------------------------------------------------------------------+

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
function arr_to_str($arr, $glue = ',')
{
    return implode($glue, $arr);
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function arr_to_obj($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)arr_to_obj($v);
        }
    }

    return (object)$arr;
}

// +---------------------------------------------------------------------+
// | 字符串函数
// +---------------------------------------------------------------------+


/**
 * 获取随机字符
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param string $length 长度
 * @param bool $num 获取数字
 * @return null|string
 */
function get_rand_char($length = '32', $num = false)
{
    $str = null;
    $strPol = !$num ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'
        : "0123456789";
    $max = strlen($strPol) - 1;
    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
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
 * 使用上面的函数与系统加密KEY完成字符串加密
 * @param  string $str 要加密的字符串
 * @param  string $salt 加密盐
 * @return string
 */
function data_md5_key($str, $salt = '')
{

    if (is_array($str)) {

        ksort($str);

        $data = http_build_query($str);

    } else {

        $data = (string) $str;
    }

    return empty($salt) ? data_md5($data,config('secret.data_salt')) : data_md5($data, $salt);
}

/**
 * 系统非常规MD5加密方法
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param  string $str 要加密的字符串
 * @param string $key 加密KEY
 * @return string
 */
function data_md5($str, $key = '')
{

    return '' === $str ? '' : md5(sha1($str) . $key);
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
 * 月赋值
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $array
 * @param $key
 * @return array
 */
function get_order_month_stat($array,$key){
    $monthArr = [];
    for($i = 0; $i < 12; $i++) {
        $monthArr[] =  0;
    }

    foreach ($array as $v){
        $monthArr[$v['month'] - 1] = (integer)$v[$key];
    }
    ksort($monthArr);

    return $monthArr;
}