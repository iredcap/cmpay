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


use app\common\library\enum\CodeEnum;
use think\Db;
use think\Log;

class BalanceCash extends BaseLogic
{

    /**
     * 获取订单信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool|string $field
     *
     * @return mixed
     */
    public function getOrderCashInfo($where = [], $field = 'a.*,u.account,b.name as method'){
        $this->modelBalanceCash->alias('a');

        $join = [
            ['user_account u', 'a.account = u.id'],
            ['banker b', 'u.bank_id = b.id']
        ];

        $this->modelBalanceCash->join = $join;
        return $this->modelBalanceCash->getInfo($where, $field);
    }

    /**
     * 获取打款列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     */
    public function getOrderCashList($where = [], $field = 'a.*,u.account,b.name as method', $order = 'a.create_time desc', $paginate = 15)
    {
        $this->modelBalanceCash->alias('a');

        $join = [
            ['user_account u', 'a.account = u.id'],
            ['banker b', 'u.bank_id = b.id']
        ];

        $this->modelBalanceCash->join = $join;

        return $this->modelBalanceCash->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取打款列表总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return mixed
     */
    public function getOrderCashCount($where = []){
        return $this->modelBalanceCash->getCount($where);
    }

    /**
     * 新增提现申请记录
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     *
     * @return array
     */
    public function saveUserCashApply($data){
        //TODO 数据验证
        $validate = $this->validateBalance->check($data);

        if (!$validate) {
            return ['code' => CodeEnum::ERROR, 'msg' => $this->validateBalance->getError()];
        }
        //TODO 添加数据
        Db::startTrans();
        try{
            $data['cash_no'] = create_order_no();
            //提现
            $this->modelBalanceCash->setInfo($data);
            //资金变动 - 资金记录
           $this->logicBalanceChange->creatBalanceChange($data['uid'],$data['amount'],'提现扣减可用金额', 'enable', true);

            Db::commit();

            action_log('新增', '个人提交提现申请'. $data['remarks']);

            return ['code' => CodeEnum::SUCCESS, 'msg' => '新增提现申请成功'];
        }catch (\Exception $ex){
            Log::error("新增提现申请出现错误 : " . $ex->getMessage());
            Db::rollback();
            return ['code' => CodeEnum::ERROR, 'msg' => config('app_debug') ? $ex->getMessage()
                : '新增提现申请出现错误' ];
        }
    }


    /**
     * 推送打款队列
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     *
     * @return array
     */
    public function pushBalanceCash($where = []){
        //订单
        $order = $this->getOrderCashInfo($where);
        //加入队列
        $result = $this->logicQueue->pushJobDataToQueue('AutoBalanceCash' , $order , 'AutoBalanceCash');
        if ($result){
            $returnmsg = [ 'code' =>  CodeEnum::SUCCESS, 'msg'  => '推送打款队列成功'];
        }else{
            $returnmsg = [ 'code' =>  CodeEnum::ERROR, 'msg'  => '推送打款队列失败'];
        }
        action_log('推送','推送提现订单打款，单号：' . $order['cash_no']);
        return $returnmsg;
    }

    /**
     * 驳回提现
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     *
     * @return array
     */
    public function rebutBalanceCash($where = []){
        //订单
        $order = $this->getOrderCashInfo($where);
        if ($order['status'] == 0) return ['code' =>  CodeEnum::SUCCESS, 'msg'  => '已经操作过了'];
        Db::startTrans();
        try{

            $this->modelBalanceCash->setFieldValue(['id' => $order['id']], 'status', CodeEnum::ERROR);

            //资金变动 - 资金记录
            $this->logicBalanceChange->creatBalanceChange($order['uid'],$order['amount'],"提现驳回可用金额", 'enable', false);

            Db::commit();

            action_log('驳回', '个人提交提现申请，单号：' . $order['cash_no']);

            return  ['code' =>  CodeEnum::SUCCESS, 'msg'  => '已经驳回'];
        }catch (\Exception $ex){
            Log::error("新增提现申请出现错误 : " . $ex->getMessage());
            Db::rollback();
            return ['code' => CodeEnum::ERROR, 'msg' => config('app_debug') ? $ex->getMessage()
                : '驳回异常' ];
        }
    }

    /**
     * 设置某个字段参数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $value
     *
     */
    public function setCashValue($where = [], $field = 'cash_no', $value = ''){
        $this->modelBalanceCash->setFieldValue($where, $field, $value);
    }
}