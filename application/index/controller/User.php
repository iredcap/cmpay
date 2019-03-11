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


use app\common\library\enum\CodeEnum;
use think\helper\Time;

class User extends Base
{

    /**
     * 商户首页
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){
        $where = ['uid'=> is_login()];
        //资金 资产信息
        $this->assign('wallet', $this->logicBalance->getBalanceInfo($where));

        $this->assign('stat',$this->logicOrders->getWelcomeStat($where));
        //当月时间
        list($start, $end) = Time::month();
        $where['create_time'] = ['between time', [$start,$end]];

        //当月数据统计
        $this->assign('month',$this->logicOrders->getWelcomeStat($where));
        //最新订单  当月时间
        $this->assign('list',$this->logicOrders->getOrderList($where,true, 'create_time desc','5'));

        return $this->fetch();
    }

    /**
     * 通知公告
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $id
     * @return mixed
     */
    public function notice($id){

        $this->assign('notice',$this->logicArticle->getNoticeInfo(['id' => $id]));
        $this->assign('list', $this->logicArticle->getNoticeList([], true, 'create_time desc', 10));
        return $this->fetch();
    }

    /**
     * 订单月统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getOrderStat(){

        $res = $this->logicOrders->getOrdersMonthStat();

        $data = [
            'orders' => get_order_month_stat($res,'total_orders'),
            'fees' => get_order_month_stat($res,'total_amount'),
        ];
        $this->result(CodeEnum::SUCCESS,'',$data);
    }

    /**
     * 商户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function info()
    {
        $this->common();

        return $this->fetch();
    }

    /**
     * 认证信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function auth()
    {
        if($this->request->isPost()){
            if ($this->request->post('i/a')['uid'] == is_login()){
                $this->result($this->logicUser->saveUserAuth($this->request->post('i/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //认证检查
        $this->assign('auth',$this->logicUser->getUserAuthInfo(['uid' =>is_login()]));
        return $this->fetch();
    }

    /**
     * 密码管理
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function password()
    {
        if($this->request->isPost()){
            if ($this->request->post('p/a')['uid'] == is_login()){
                $this->result($this->logicUser->changePwd($this->request->post('p/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //获取商户详细信息
        $this->assign('user',$this->logicUser->getUserInfo(['uid' =>is_login()]));

        return $this->fetch();
    }

    /**
     * 操作日志
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function log()
    {

        $where = ['uid'=> is_login()];
        //组合搜索

        $where['module'] = ['like', 'index'];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $this->assign('list',$this->logicLog->getLogList($where, true, 'create_time desc',10));

        return $this->fetch();
    }

    /**
     * Common
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function common()
    {

        if($this->request->isPost()){
            if ($this->request->post('i/a')['uid'] == is_login()){
                $this->result($this->logicUser->editUser($this->request->post('i/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        //获取商户详细信息
        $this->assign('user',$this->logicUser->getUserInfo(['uid' =>is_login()]));

    }

    /**
     * 上传认证图片  加水印
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function uploadAuth(){

        // 普通上传
        //$this->request->isPost() && $this->result($this->logicFile->picUpload('card','userauth/' . is_login() . DS));
        // Base64
        $this->request->isPost() && $this->result($this->logicFile->saveBase64Image($this->request->post('pic'),'userauth/' . is_login() . '/'));

        $this->result(0,'非法操作，请重试！');

    }

    /**
     * 常见问题
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function faq(){
        return $this->fetch();
    }
}