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

namespace app\install\logic;

use app\common\library\enum\CodeEnum;
use app\common\logic\BaseLogic;
use think\Db;
use think\Log;

/**
 * 安装逻辑
 */
class Install extends BaseLogic
{

    /**
     * 检查站点安装数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param null $site
     * @param null $admin
     *
     * @return bool|array
     */
    public function checkSiteConfig($site = null, $admin = null)
    {

        // 检测管理员信息
        if (!is_array($admin) || empty($admin['username']) || empty($admin['email']) || empty($admin['password'])) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => '请填写完整管理员信息'];

        } else if ($admin['password'] != $admin['repassword']) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => '确认密码和密码不一致'];
        }
        session('site', $site);session('admin', $admin);

        return true;
    }


    /**
     * 检查数据库安装数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param null $db
     *
     * @return bool|array
     */
    public function checkDbConfig($db = null)
    {
        // 检测数据库配置
        if (!is_array($db) || empty($db['hostname']) ||  empty($db['hostport']) || empty($db['database']) || empty($db['username'])) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => '请填写完整的数据库配置'];
        }
        session('db', $db);
        return true;
    }

    /**
     * 开始安装
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return bool|array
     */
    public function install()
    {

        $site = session('site');
        $admin = session('admin');
        $db = session('db');
        try{
            //创建数据库
            $dbname = $db['database'];

            $database_name = $db['database'];

            unset($db['database']);

            $db_connect = Db::connect($db);

            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";

            if (!$db_connect->execute($sql)) {
                return [ 'code' => CodeEnum::ERROR, 'msg' => '创建数据库失败'];
            }

            //创建数据表
            $db['database'] = $database_name;

            $db_object = Db::connect($db);
            if (!create_tables($db_object, $db['prefix'])) {

                return [ 'code' => CodeEnum::ERROR, 'msg' => '创建数据表失败'];
            }
            //写入数据库站点配置
            if (!create_config($db_object, $db['prefix'], $site)) {
                return [ 'code' => CodeEnum::ERROR, 'msg' => '写入数据库站点配置失败'];
            }

            //注册超级帐号
            if (!create_admin($db_object, $db['prefix'], $admin)) {
                return [ 'code' => CodeEnum::ERROR, 'msg' => '注册超级管理员失败'];
            }

            //创建配置文件
            if (!write_config($db)) {
                return [ 'code' => CodeEnum::ERROR, 'msg' => '创建配置文件失败'];
            }
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '安装成功'];
        }catch (\Exception $e){
            Log::error('安装出现问题：' . $e->getMessage());
            return [ 'code' => CodeEnum::ERROR, 'msg' => $e->getMessage()];
        }


    }
}
