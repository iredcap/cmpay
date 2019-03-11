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


namespace app\common\library\enum;

/**
 * Class UserStatusEnum
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class UserStatusEnum
{
    // 删除
    const DELETE = -1;

    // 等待激活
    const WAIT = 0;

    // 可用
    const ENABLE = 1;

    // 禁用
    const DISABLE = 2;
}