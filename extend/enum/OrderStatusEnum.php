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

namespace enum;

/**
 * Class OrderStatusEnum
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class OrderStatusEnum
{
    // 删除
    const DELETE = -1;

    // 关闭
    const CLOSE = 0;

    // 待支付
    const UNPAID = 1;

    // 已支付
    const PAID  = 2;

}