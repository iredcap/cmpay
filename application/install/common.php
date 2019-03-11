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

use think\facade\App;
/**
 * 系统环境检测
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return array
 */
function check_env()
{
    $items = array(
        'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'check'),
        'php'     => array('PHP版本', '5.6', '7.0+', PHP_VERSION, 'check'),
        'upload'  => array('附件上传', '不限制', '8M+', '未知', 'check'),
        'gd'      => array('GD库', '2.0', '2.0+', '未知', 'check'),
        'disk'    => array('磁盘空间', '20M', '不限制', '未知', 'check'),
    );

    //PHP环境检测
    if ($items['php'][3] < $items['php'][1]) {
        $items['php'][4] = 'error';
    }

    //附件上传检测
    if (@ini_get('file_uploads')) {
        $items['upload'][3] = ini_get('upload_max_filesize');
    }

    //GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if (empty($tmp['GD Version'])) {
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }

    unset($tmp);

    //磁盘空间检测
    if (function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(App::getRootPath()) / (1024*1024)).'M';
    }

    return $items;
}


/**
 * 目录，文件读写检测
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return array
 */
function check_dirfile()
{
    $items = array(
        array('dir',  '可写', 'check', '/uploads'),
        array('dir',  '可写', 'check', '../resource'),
        array('dir',  '可写', 'check', '../runtime'),
    );

    foreach ($items as &$val) {

        $item =	App::getRootPath() . $val[3];

        if ('dir' == $val[0]) {
            if (!is_writable($item)) {
                if (is_dir($item)) {
                    $val[1] = '可读';
                    $val[2] = 'close';
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'close';
                }
            }
        } else {
            if (file_exists($item)) {
                if (!is_writable($item)) {
                    $val[1] = '不可写';
                    $val[2] = 'close';
                }
            } else {
                if (!is_writable(dirname($item))) {
                    $val[1] = '不存在';
                    $val[2] = 'close';
                }
            }
        }
    }

    return $items;
}


/**
 * 函数检测
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return array
 */
function check_func()
{
    $items = array(
        array('pdo','支持','check','类'),
        array('pdo_mysql','支持','check','模块'),
        array('redis','支持','check','模块'),
        array('openssl_sign','支持','check','函数'),
        array('file_get_contents', '支持', 'check','函数'),
    );

    foreach ($items as &$val) {
        if (('类' == $val[3] && !class_exists($val[0])) || ('模块'==$val[3] &&
            !extension_loaded($val[0])) || ('函数'==$val[3] && !function_exists($val[0]))) {
            $val[1] = '不支持';
            $val[2] = 'close';
        }
    }

    return $items;
}


/**
 * 创建数据表
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $db_object
 * @param string $prefix
 *
 * @return bool
 */
function create_tables($db_object, $prefix = '')
{
    
    $result = true;
    
    //读取SQL文件
    $sql = file_get_contents(__DIR__ . '/data/install.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);
    //替换表前缀
    $orginal = 'cm_';

    $sql = str_replace(" `{$orginal}", " `{$prefix}", $sql);
    
    //开始安装
    foreach ($sql as $value) {

        $value = trim($value);

        if (empty($value)) {  continue; }
        
        if (false === $db_object->execute($value)) {

            $result = false;
        }
    }
    
    return $result;
}


/**
 * 生成系统AUTH_KEY
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @return bool|string
 */
function build_auth_key()
{
   $chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $chars .= '`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
   $chars  = str_shuffle($chars);
   return substr($chars, 0, 40);
}


/**
 * 站点配置
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $db_object
 * @param $prefix
 * @param $site
 *
 * @return bool
 */
function create_config($db_object, $prefix, $site){
   foreach ($site as $k => $v){
       $sql ="UPDATE ". $prefix ."config SET value = '". $v . "' WHERE name = '". $k ."'";
       $db_object->execute($sql);
   }
   return true;
}

/**
 * 创建管理员
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $db_object
 * @param $prefix
 * @param $admin
 *
 * @return mixed
 */
function create_admin($db_object, $prefix, $admin)
{
    $sql = "INSERT INTO `[PREFIX]admin` VALUES " .
           "(1, 0, '[USERNAME]', '[NICKNAME]', '[PASSWORD]', '18888888888', '[EMAIL]',  1 , '[UPDATETIME]', '[CREATETIME]')";

    $password = data_md5_key($admin['password']);

    $time = time();
    
    $sql = str_replace(
        array('[PREFIX]', '[USERNAME]', '[NICKNAME]', '[PASSWORD]', '[EMAIL]', '[UPDATETIME]', '[CREATETIME]'),
        array($prefix, $admin['username'], '超级管理员', $password, $admin['email'], $time, $time),
        $sql);

    //执行sql
    return $db_object->execute($sql);
}

/**
 * 写入配置文件
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $config
 *
 * @return bool
 */
function write_config($config)
{

    //读取数据库配置内容
    $conf = file_get_contents(__DIR__ . '/data/database.tpl');

    //替换配置项
    foreach ($config as $name => $value) {

        $conf = str_replace("[{$name}]", $value, $conf);
    }

    if (file_put_contents(App::getConfigPath() .'database.php', $conf)) {

        // 写入安装锁定文件(只能在最后一步写入锁定文件，因为锁定文件写入后安装模块将无法访问)
        file_put_contents(RES_PATH . 'install.lock',  ' install lock');

        return true;
    }

    return false;
}

/**
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 * @param $dir
 *
 * @return bool
 */
function deleteDir($dir)
{
    if (!$handle = @opendir($dir)) {
        return false;
    }
    while (false !== ($file = readdir($handle))) {
        if ($file !== "." && $file !== "..") {       //排除当前目录与父级目录
            $file = $dir . '/' . $file;
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                @unlink($file);
            }
        }

    }
    return @rmdir($dir);
}