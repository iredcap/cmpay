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


use app\common\library\RsaUtils;

class Api extends Base
{

    /**
     * 接口基本
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){
        $this->apiCommon();
        return $this->fetch();
    }

    /**
     * 可用渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function channel(){
        $channel = $this->logicPay->getCodeList(['status' => '1'], true, 'create_time desc', 10);
        $this->assign('list',$channel);
        return $this->fetch();
    }


    /**
     * API公共
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function apiCommon(){
        if($this->request->isPost()){
            if ($this->request->post('u/a')['uid'] == is_login()){
                $this->result($this->logicApi->editApi($this->request->post('u/a')));
            }else{
                $this->result(0,'非法操作，请重试！');
            }
        }
        $this->assign('api',$this->logicApi->getApiInfo(['uid' => is_login()]));

        $this->assign('rsa',$this->logicConfig->getConfigInfo(['name' => 'rsa_public_key'],'value'));
    }
}