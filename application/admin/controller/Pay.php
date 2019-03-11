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

class Pay extends BaseAdmin
{
    /**
     * 支付方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 支付渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function channel(){
        return $this->fetch();
    }

    /**
     * 支付渠道账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function account(){
        $this->assign('cnl_id',$this->request->param('cnl_id'));
        return $this->fetch();
    }

    /**
     * 银行
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function bank(){
        return $this->fetch();
    }

    /**
     * 支付渠道列表
     * @url getChannelList?page=1&limit=10
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getCodeList(){

        $where = [];
        //code
        !empty($this->request->param('code')) && $where['code']
            = ['eq', $this->request->param('code')];
        //name
        !empty($this->request->param('name')) && $where['name']
            = ['like', '%'.$this->request->param('name').'%'];

        $data = $this->logicPay->getCodeList($where, true, 'create_time desc', false);

        $count = $this->logicPay->getCodeCount($where);

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
            ]);
    }

    /**
     * 支付渠道列表
     * @url getChannelList?page=1&limit=10
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getChannelList(){

        $where = [];
        //组合搜索
        !empty($this->request->param('id')) && $where['id']
            = ['eq', $this->request->param('id')];
        //name
        !empty($this->request->param('name')) && $where['name']
            = ['like', '%'.$this->request->param('name').'%'];


        $data = $this->logicPay->getChannelList($where,true, 'create_time desc',false);

        $count = $this->logicPay->getChannelCount($where);

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
            ]);
    }

    /**
     * 支付渠道账户列表
     * @url getChannelList?page=1&limit=10
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getAccountList(){

        $where = [
            'cnl_id' =>  $this->request->param('cnl_id')
        ];
        //组合搜索
        !empty($this->request->param('id')) && $where['id']
            = ['eq', $this->request->param('id')];
        //name
        !empty($this->request->param('name')) && $where['name']
            = ['like', '%'.$this->request->param('name').'%'];


        $data = $this->logicPay->getAccountList($where,true, 'create_time desc',false);

        $count = $this->logicPay->getAccountCount($where);

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
            ]);
    }

    /**
     * 支付渠道列表
     * @url getChannelList?page=1&limit=10
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getBankList(){

        $where = [];
        //组合搜索
        !empty($this->request->param('keywords')) && $where['id|name']
            = ['like', '%'.$this->request->param('keywords').'%'];

        $data = $this->logicBanker->getBankerList($where,true, 'create_time desc',false);

        $count = $this->logicBanker->getBankerCount($where);

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
            ]);
    }

    /**
     * 新增支付渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addChannel()
    {
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveChannelInfo($this->request->post()));
        return $this->fetch();
    }

    /**
     * 新增渠道账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addAccount()
    {
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveAccountInfo($this->request->post()));

        //获取渠道列表
        $channel = $this->logicPay->getChannelList([], true, 'create_time desc',false);
        //获取方式列表
        $codes = $this->logicPay->getCodeList([], true, 'create_time desc',false);

        $this->assign('cnl_id',$this->request->param('cnl_id'));

        $this->assign('channel',$channel);
        $this->assign('codes',$codes);

        return $this->fetch();
    }

    /**
     * 新增支付方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addCode()
    {
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveCodeInfo($this->request->post()));
        //支持渠道列表
        $this->assign('channel',$this->logicPay->getChannelList([],'id,name','id asc'));

        return $this->fetch();
    }

    /**
     * 新增支付银行
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addBank()
    {
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicBank->saveBankInfo($this->request->post()));

        return $this->fetch();
    }

    /**
     * 编辑支付渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editChannel(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveChannelInfo($this->request->post()));
        //获取渠道详细信息
        $channel = $this->logicPay->getChannelInfo(['id' =>$this->request->param('id')]);
        //时间转换
        $channel['timeslot'] = json_decode($channel['timeslot'],true);

        $this->assign('channel',$channel);

        return $this->fetch();
    }

    /**
     * 编辑支付渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editAccount(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveAccountInfo($this->request->post()));
        //获取账户详细信息
        $account = $this->logicPay->getAccountInfo(['id' =>$this->request->param('id')]);
        //时间转换
        $account['timeslot'] = json_decode($account['timeslot'],true);
        //获取方式列表
        $codes = $this->logicPay->getCodeList([], true, 'create_time desc',false);
        //获取渠道列表
        $channels = $this->logicPay->getChannelList([], true, 'create_time desc',false);

        $this->assign('codes',$codes);
        $this->assign('channels',$channels);
        $this->assign('account',$account);

        return $this->fetch();
    }

    /**
     * 编辑支付方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editCode(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicPay->saveCodeInfo($this->request->post()));
        //支持渠道列表
        $this->assign('channel',$this->logicPay->getChannelList([],'id,name','id asc'));
        //获取支付方式详细信息
        $this->assign('code',$this->logicPay->getCodeInfo(['id' =>$this->request->param('id')]));

        return $this->fetch();
    }

    /**
     * 编辑支付银行
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editBank(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicBanker->savelogicBank->saveBankerInfo($this->request->post()));
        //获取支付方式详细信息
        $this->assign('bank',$this->logicBanker->getBankerInfo(['id' =>$this->request->param('id')]));

        return $this->fetch();
    }

    /**
     * 删除支付方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function delCode(){
        // post 是提交数据
        $this->request->isPost() && $this->result(
            $this->logicPay->delCode(
                [
                    'id' => $this->request->param('id')
                ])
        );

        // get 直接报错
        $this->error('未知错误');
    }

    /**
     * 删除渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function delChannel(){
        // post 是提交数据
        $this->request->isPost() && $this->result(
            $this->logicPay->delChannel(
                [
                    'id' => $this->request->param('id')
                ])
        );

        // get 直接报错
        $this->error('未知错误');
    }

    /**
     * 删除渠道账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function delAccount(){
        // post 是提交数据
        $this->request->isPost() && $this->result(
            $this->logicPay->delAccount(
                [
                    'id' => $this->request->param('id')
                ])
        );

        // get 直接报错
        $this->error('未知错误');
    }
}