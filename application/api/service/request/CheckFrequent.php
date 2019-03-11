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


namespace app\api\service\request;
use app\common\library\exception\ForbiddenException;
use think\Cache;
use think\Log;
use think\Request;

/**
 * 检验接口访问频率
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class CheckFrequent extends ApiCheck
{
    /**
     * 限定时间段
     *
     * 单位：seconds
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var int
     */
    private $timeScope = 1;

    /**
     * 限定次数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var int
     */
    private $times = 10;

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Request $request
     * @return mixed|void
     * @throws ForbiddenException
     */
    public function doCheck(Request $request)
    {
        $key = 'ClientIp:' . $request->ip();
        $cache = Cache::store('redis');
        $value = $cache->get($key);
        if (!$value) {
            $cache->set($key, 0, $this->timeScope);
        }
        if ($value >= $this->times) {
            Log::error($key . '[ Trigger Restriction And Flow Control.]');
            throw new ForbiddenException([
                'msg' => "Invalid Request.[ Trigger Restriction And Flow Control.]",
                'errorCode' => 100003
            ]);
        }
        $cache->inc($key);
    }
}
