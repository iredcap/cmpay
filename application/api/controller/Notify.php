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

namespace app\api\controller;;

use app\api\service\ApiPayment;
use app\common\controller\Common;

class Notify extends Common
{

    /**
     * 同步回调 【不做数据处理 获取商户回调地址返回就行了】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $channel
     *
     */
    public function callback($channel = 'wx_native'){

        //支付分发
        $return_url = ApiPayment::$channel()->callback();

        $this->redirect(!empty($return_url) ? $return_url: config('site.domain'));
    }

    /**
     * 统一异步通知
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $channel
     *
     */
    public function notify($channel = 'wx_native'){

         //支付分发
        $result = ApiPayment::$channel()->notify();

        $this->logicNotify->handle($result);

    }
}