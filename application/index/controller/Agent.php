<?php


namespace app\index\controller;


use app\common\library\enum\OrderStatusEnum;

class Agent extends Base
{

    /**
     * 获取数据统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function index(){
        $where = ['puid'=> is_login()];
        $this->assign('list', $this->logicUser->getUserList($where, true, 'create_time desc', 10));
        return $this->fetch();
    }

    /**
     * 下级交易明细
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function orders(){
        $where = ['puid' => is_login()];
        //组合搜索
        !empty($this->request->get('trade_no')) && $where['trade_no']
            = ['like', '%'.$this->request->get('trade_no').'%'];

        !empty($this->request->get('channel')) && $where['channel']
            = ['eq', $this->request->get('channel')];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        //状态
        $where['status'] = ['eq', $this->request->get('status',OrderStatusEnum::UNPAID)];

        $this->assign('list', $this->logicOrders->getOrderList($where,true, 'create_time desc', 10));

        $this->assign('code',$this->logicPay->getCodeList([]));

        return $this->fetch();
    }

    /**
     * 下级分润比例
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function profit(){
        return $this->fetch();
    }

    /**
     * 新增下级商户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function addUser(){

        if($this->request->isPost()){
            if ($this->request->post('u/a')['puid'] == is_login()){
                //创建商户
                $this->result($this->logicLogin->doregister($this->request->post('u/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑商户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function editUser(){
        if($this->request->isPost()){
            if ($this->request->post('i/a')['puid'] == is_login()){
                $this->result($this->logicUser->editUser($this->request->post('i/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //读取商户信息
        $this->assign('user',$this->logicUser->getUserInfo([
                'puid' =>is_login(),
                'uid' =>$this->request->param('uid','')
            ]));

        return $this->fetch();
    }
}