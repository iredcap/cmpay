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

namespace app\admin\controller;


use app\common\library\enum\CodeEnum;

class Account extends BaseAdmin
{

    /**
     * 账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**getAccountCount
     * 账户列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getList(){
        $where = [];
        //组合搜索
        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        $data = $this->logicUserAccount->getAccountList($where, 'a.*,b.id as b_id,b.name as bank', 'a.create_time desc', false);

        $count = $this->logicUserAccount->getAccountCount($where);

        $this->result($data || !empty($data) ?
            [
                'code' => CodeEnum::SUCCESS,
                'msg'=> '',
                'count'=>$count,
                'data'=>$data
            ] : [
                'code' => CodeEnum::ERROR,
                'msg'=> '暂无数据',
                'count'=>$count,
                'data'=>$data
            ]
        );
    }

    /**
     * 编辑商户账户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function edit(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicUserAccount->saveUserAccount($this->request->post()));
        //获取商户账户信息
        $this->assign('bank',$this->logicBanker->getBankerList());
        $this->assign('account',$this->logicUserAccount->getAccountInfo(['id' =>$this->request->param('id')]));

        return $this->fetch();
    }


    /**
     * 删除商户账户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     */
    public function del(){
        // post 是提交数据
        $this->request->isPost() && $this->result(
            $this->logicUserAccount->delAccount(
                [
                    'id' => $this->request->param('id')
                ])
        );
        // get 直接报错
        $this->error([ CodeEnum::ERROR,'未知错误']);
    }
}