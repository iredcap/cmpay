<?php

/**
 * +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *  | Copyright (c) 2019 知行信息科技. All rights reserved.
 * +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

namespace app\index\logic;

use app\common\logic\BaseLogic;
use enum\CodeEnum;
use enum\UserStatusEnum;
use tool\Activation;
use think\Db;
use think\facade\Log;

class Auth extends BaseLogic
{
    /**
     * 登录操作
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $username 账号
     * @param string $password  密码
     * @return array
     */
    public function dologin($username,$password){

        $validate = $this->validateAuth->check(compact('username','password'));

        if (!$validate) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => $this->validateAuth->getError()];
        }

        $user = $this->logicUser->getUserInfo(['account' => $username]);

        //密码判断 管理员登录使用  data_md5_key
        if (!empty($user['password']) && data_md5($password, $user['reg_salt']) == $user['password']) {
            //激活判断
            if ($user['status'] == UserStatusEnum::WAIT){
                action_log('登录', '商户'. $username . '账号未激活');
                return [ 'code' => CodeEnum::ERROR, 'msg' =>  '账号未激活,<span onclick="page(\'发送激活邮件\',\'/active/sendActive\',this,\'440px\',\'180px\')">点击发送激活邮件</span>'];
            }
            //禁用判断
            if ($user['status'] == UserStatusEnum::DISABLE){
                return [ 'code' => CodeEnum::ERROR, 'msg' =>  '账号禁用'];
            }
            $this->logicUser->setUserValue(['uid' => $user['uid']], 'update_time', time());

            $auth = ['uid' => $user['uid'], 'update_time'  =>  time()];

            session('user_info', $user);
            session('user_auth', $auth);
            session('user_auth_sign', data_auth_sign($auth));

            action_log('登录', '商户'. $username . '登录成功');

            return [ 'code' => CodeEnum::SUCCESS, 'msg' =>  '登录成功'];
        } else {
            $msg = empty($user['uid']) ? '用户账号不存在' : '密码输入错误';
            action_log('登录', '商户'. $username . '登录失败，' . $msg);
            return [ 'code' => CodeEnum::ERROR, 'msg' => $msg];
        }
    }

    /**
     * 用户注册
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data 注册数据
     * @return array
     */
    public function doregister($data){

        $validate = $this->validateRegister->check($data);
        //数据检验
        if (!$validate) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => $this->validateRegister->getError()];
        }

        //TODO 添加数据
        Db::startTrans();
        try{
            $data['reg_salt'] = get_rand_char(32);
            //密码
            $data['password'] = data_md5($data['password'], $data['reg_salt']);
            //基本信息
            $uid = $this->logicUser->setUserInfo($data);
            //账户记录
           // $this->logicUserAccount->setUserAccountInfo(['uid'  => $uid ]);
            //资金记录
            //$this->logicBalance->setBalanceInfo(['uid'  => $uid ]);
            //生成API记录
            $this->logicUserApp->setUserAppInfo(['uid'  => $uid]);

            //加入邮件队列
            $jobData = $this->logicUser->getUserInfo(['uid' => $uid],'uid,account,username');

            //邮件场景
            $jobData['scene']   = 'register';
            $this->pushJobDataToQueue('AutoEmailWork' , $jobData , 'AutoEmailWork');

            action_log('新增', '新增商户。UID:'. $uid);

            Db::commit();

            return ['code' => CodeEnum::SUCCESS, 'msg' => '注册成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return ['code' => CodeEnum::ERROR, 'msg' => config('app_debug') ? $ex->getMessage()
                : '哎呀！注册发生异常了~'];
        }
    }

    /**
     * 数据检测
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $field 字段
     * @param string $value 值
     * @return mixed
     */
    public function checkField($field='',$value=''){
        $user_field = $this->logicUser->getUserValue([ $field => $value], $field);
        if($user_field){
            return [ 'code' => CodeEnum::ERROR, 'msg' => '账户已被使用'];
        }else{
            return [ 'code' => CodeEnum::SUCCESS, 'msg' =>  '账户可用'];
        }
    }

    /**
     * 发送激活邮件
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $account
     * @return array
     */
    public function sendActiveCode($account){
        $user = $this->logicUser->getUserInfo(['account'=>$account]);
        if (!$user){
            return [ 'code' => CodeEnum::ERROR, 'msg' => '注册邮箱不存在'];
        }else{
            if (($user['status'] && $user['is_verify'] ) == UserStatusEnum::ENABLE){
                return [ 'code' => CodeEnum::ERROR, 'msg' => '商户已激活'];
            }
            $user['scene']  = 'register';
            //加入邮件队列
            $this->pushJobDataToQueue('AutoEmailWork' , $user , 'AutoEmailWork');
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '发送成功'];
        }
    }

    /**
     * 商户激活过程
     * 1.获取参数  比对商户是否存在
     * 2.商户存在  验证是否已经激活过了（这注意  激活链接激活成功之后 后续直接跳转登录页面）
     * 3.code校验
     * 4.End
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $code
     * @return array|mixed
     */
    public function activationCode($code){

        //验证code可用性 并返回注册商户数据对象
        $Verification = (new Activation())->VerificationActiveCode($code);
        if (!$Verification){
            return ['code'=> CodeEnum::ERROR,'msg'=>'激活链接失效了，请重新发送'];
        }

        //TODO 验证逻辑

        $user = $this->modelUser->getUser($Verification->uid);
        if (!$user) {
            return ['code' => CodeEnum::ERROR, 'msg' =>'商户不存在！'];
        } else {
            //是否已经激活
            if(($user['status'] == UserStatusEnum::ENABLE && $user['is_verify'] ) == UserStatusEnum::ENABLE){
                return ['code' => CodeEnum::SUCCESS, 'msg'=>'商户已经激活过了 :-)'];
            }else{
                ///生成随机安全码
                $auth_code = get_rand_char(8,true);
                //加入注册成功邮件  发送安全码
                $jobData = $user;
                $jobData ['auth_code'] = $auth_code;
                //邮件场景
                $jobData['scene']   = 'regcallback';
                $this->logicQueue->pushJobDataToQueue('AutoEmailWork' , $jobData , 'AutoEmailWork');
                //数据处理
                $this->logicUser->updateInfo(
                    ['uid'=>$Verification->uid],
                    [
                        'is_verify_phone' => UserStatusEnum::ENABLE,
                        'status' => UserStatusEnum::ENABLE,
                        'auth_code' => data_md5($auth_code)
                    ]);

                return ['code' => CodeEnum::SUCCESS, 'msg'=>'商户激活成功！'];
            }

        }

    }

    /**
     * 注销当前用户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return string
     */
    public function logout()
    {

        clear_user_login_session();

        return url('index/Auth/login');
    }
}