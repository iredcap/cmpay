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

/**
 * Author: 勇敢的小笨羊
 * Github: https://github.com/SingleSheep
 */

namespace app\api\service\request;
use app\common\library\exception\ParameterException;
use app\common\library\HttpHeader;
use Log;
use think\Request;


/**
 * 检验网关公共必传参数
 *
 * check Gateway's common arguments
 */
class CheckArguments extends ApiCheck
{
    /**
     * 网关参数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    private $commonArgus = [
        // 授权API KEY
        'x-ca-auth',
        // 数据签名
        'x-ca-signature',
        // 32位随机字符串
        'x-ca-noncestr',
        // 请求时间戳
        'x-ca-timestamp',
        // 请求网关
        'x-ca-resturl'
    ];


    /**
     * 校验公共头参数
     *
     * check Gateway's common arguments
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Request $request
     * @return mixed|void
     * @throws ParameterException
     */
    public function doCheck(Request $request)
    {

        // 创建上下文
        self::createContext();
        self::set(HttpHeader::X_CA_AUTH,$request->header('x-ca-auth'));
        self::set('payload', $request->param());

        // 获取所有参数
        $header = $request->header();
        foreach ($this->commonArgus as $v) {
            if (! isset($header[$v]) || empty($header[$v])) {
                throw new ParameterException([
                    'msg'=>"Invalid Request.[ Request header [{$v}] Failure.]",
                    'errorCode'=>400000
                    ]);
            }
        }
    }
}
