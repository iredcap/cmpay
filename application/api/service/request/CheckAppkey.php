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

namespace app\api\service\request;

use app\common\exception\ParameterException;
use tool\HttpHeader;
use think\Request;

/**
 * 检验app授权key
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class CheckAppkey extends ApiCheck
{
    /**
     * 校验app key
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Request $request
     * @return mixed|void
     * @throws ParameterException
     */
    public function doCheck(Request $request)
    {

        // 获取app key Map
        $appKeyMap = (array)$this->logicUserApp->getAppKeyMap();
        if (!in_array(self::get(HttpHeader::X_CA_AUTH),$appKeyMap)) {
            throw new ParameterException([
                'msg'=>'Invalid Request.[ Auth Key No permission or nexistencet.]',
                'errorCode'=> 400003
            ]);
        }

        //支付方式判断
        $appCodeMap = (array)$this->logicPay->getAppCodeMap();
        if (empty(self::get('payload')['channel']) ?: !in_array(self::get('payload')['channel'],$appCodeMap)) {
            throw new ParameterException([
                'msg'=>'Invalid Request.[ Payment Code Does Not Allowed.]',
                'errorCode'=> 400003
            ]);
        }

    }
}
