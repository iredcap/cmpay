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

namespace app\index\controller;

use think\captcha\Captcha;

class Balance extends Base
{
    /**
     * 验证码
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return \think\Response
     */
    public function vercode(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    14,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    /**
     * 资金详情与变动记录
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){
        $where = ['uid' => is_login()];

        //详情
        $this->common($where);

        //变动记录
        $this->assign('list', $this->logicBalanceChange->getBalanceChangeList($where,true, 'id desc', 10));

        return $this->fetch();
    }

    /**
     * 收款账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function account(){
        $where = ['a.uid' => is_login()];
        //详情
        $this->common();

        //组合搜索
        !empty($this->request->get('banker')) && $where['bank_id']
            = ['eq', $this->request->get('banker')];

        //状态
        $where['a.status'] = ['eq', $this->request->get('status','')];
        //收款账户
        $this->assign('list', $this->logicUserAccount->getAccountList($where,'a.*,b.id as b_id,b.name as banker', 'create_time desc'));

        return $this->fetch();
    }

    /**
     * 新增
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addAccount(){

        if($this->request->isPost()){
            if ($this->request->post('b/a')['uid'] == is_login()){
                $this->result($this->logicUserAccount->saveUserAccount($this->request->post('b/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //银行
        $this->assign('banker', $this->logicBanker->getBankerList());

        return $this->fetch();
    }

    /**
     * 新增
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editAccount(){

        if($this->request->isPost()){
            if ($this->request->post('b/a')['uid'] == is_login()){
                $this->result($this->logicUserAccount->saveUserAccount($this->request->post('b/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //银行
        $this->assign('banker', $this->logicBanker->getBankerList());

        //详情信息
        $this->assign('info', $this->logicUserAccount->getAccountInfo(['uid' => is_login(),'id' => $this->request->param('id')]));

        return $this->fetch();
    }

    /**
     * 打款记录
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function paid(){
        $where = ['a.uid' => is_login()];

        //时间搜索  时间戳搜素
        $where['a.create_time'] = $this->parseRequestDate();

        //列表
        $this->assign('list', $this->logicBalanceCash->getOrderCashList($where));

        return $this->fetch();
    }

    /**
     * 自主提现申请
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function apply(){
        $where = ['uid' => is_login()];

        if($this->request->isPost()){
            if ($this->request->post('b/a')['uid'] == is_login()){
                $this->result($this->logicBalanceCash->saveUserCashApply($this->request->post('b/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //详情
        $this->common($where);
        //收款账户
        $this->assign('list', $this->logicUserAccount->getAccountList(['a.uid' => is_login(),'a.status' => 1],'a.*,b.id as b_id,b.name as banker', 'a.create_time desc'));

        return $this->fetch();
    }

    /**
     * Common
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     */
    public function common($where = []){
        //资产信息
        $this->assign('info', $this->logicBalance->getBalanceInfo($where));
        //银行
        $this->assign('banker', $this->logicBanker->getBankerList());

    }
}