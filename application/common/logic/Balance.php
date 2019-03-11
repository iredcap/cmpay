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

namespace app\common\logic;


use think\Db;

class Balance extends BaseLogic
{

    /**
     * 获取资产列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @param $field
     * @param $order
     * @param $paginate
     *
     * @return mixed
     */
    public function getBalanceList($where, $field, $order, $paginate){
        return $this->modelBalance->getList($where, $field, $order, $paginate);
    }


    /**
     * 获取商户资产详情
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     *
     * @return mixed
     */
    public function getBalanceInfo($where = [], $field = true){
        return $this->modelBalance->getInfo($where, $field);
    }

    /**
     * 获取商户资产列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getBalanceCount($where = []){
        return $this->modelBalance->getCount($where);
    }

}