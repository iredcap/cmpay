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
namespace app\common\validate;

use app\common\library\exception\ParameterException;
use app\common\library\exception\UserException;
use app\common\service\Code;
use app\common\model\User as UserModel;
use think\Log;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * API数据校验  表单请用元生check
     *
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return array|bool
     * @throws ParameterException
     */
    public function goCheck()
    {
        //必须设置contetn-type:application/json
        $params = Request::instance()->param();
        if (!$this->check($params)) {
            throw new ParameterException([
                    'msg'   => is_array($this->error)
                        ? implode(';', $this->error) : $this->error
                ]);
        }
        return true;
    }

    /**
     * Not Empty
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $value
     * @param string $rule
     * @param array $data
     * @param string $field
     * @return bool|string
     */
    protected function isNotEmpty($value, $rule='', $data= [], $field='')
    {
        if (empty($value) && is_null($value) && isset($value)) {
            return 'Create Order Error:['.$value.' does not exist.]';
        } else {
            return true;
        }
    }

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $value
     * @param string $rule
     * @param  array $data
     * @param string $field
     * @return bool|string
     */
    protected function isPositiveInteger($value, $rule='', $data=[], $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . ' Must be a positive integer.';
    }

    /**
     * 验证码检查
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $value
     * @param string $rule
     * @param array $data
     * @param string $field
     * @return bool
     * @throws \think\Exception
     */
    protected function checkCode($value, $rule='', $data = [], $field=''){
        $phone = !empty($data['phone']) ? $data['phone']
            : session('user_info')['phone'];
        if ( Code::valid($phone, $value) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用户状态检测
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $value
     * @param string $rule
     * @param array $data
     * @param string $field
     * @return bool
     * @throws UserException
     * @throws \think\exception\DbException
     */
    protected function checkId($value, $rule='', $data = [], $field='')
    {
        //用户是否存在
        $User =(new UserModel())->getUser($value);
        if(!$User){
            if ($User && $User['status'] !== 1){
                throw new UserException([
                    'msg'   =>  "Create Order Error:[Merchants not allowed.]",
                    'errCode'=> '600002'
                ]);
            }
            throw new UserException([
                'msg'   =>  "Create Order Error:[Merchant Does Not Exist.]",
                'errCode'=> '600002'
            ]);
        }
        return true;
    }
}