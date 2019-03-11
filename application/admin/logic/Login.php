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

namespace app\admin\logic;


use app\common\library\enum\CodeEnum;

class Login extends BaseAdmin
{

    /**
     * 登录操作
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $username
     * @param $password
     * @param $vercode
     * @return array
     */
    public function dologin($username,$password,$vercode){

         $validate = $this->validateLogin->check(compact('username','password','vercode'));

         if (!$validate) {

             return [ 'code' => CodeEnum::ERROR, 'msg' => $this->validateLogin->getError()];
         }

        $admin = $this->logicAdmin->getAdminInfo(['username' => $username]);

        if (!empty($admin['password']) && data_md5_key($password) == $admin['password']) {

            $this->logicAdmin->setAdminValue(['id' => $admin['id']], 'update_time', time());

            $auth = ['id' => $admin['id'], 'update_time'  =>  time()];

            session('admin_info', $admin);
            session('admin_auth', $auth);
            session('admin_auth_sign', data_auth_sign($auth));

            action_log('登录', '管理员'. $username .'登录成功');

            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '登录成功','data' => ['access_token'=> data_auth_sign($auth)]];

        } else {

            return [  'code' => CodeEnum::ERROR, 'msg' => empty($admin['id']) ? '用户账号不存在' : '密码输入错误'];
        }
    }

    /**
     * 注销当前用户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return array
     */
    public function logout()
    {

        clear_admin_login_session();

        return [ 'code' => CodeEnum::SUCCESS, 'msg' => '注销成功'];
    }

    /**
     * 清理缓存
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return array
     */
    public function clearCache()
    {

        \think\Cache::clear();

        return [ 'code' => CodeEnum::ERROR, 'msg' =>  '清理成功'];
    }
}