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


class System extends BaseAdmin
{

    /**
     * 站点基本信息修改
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function website(){
        $this->common();
        $this->assign('list', $this->logicConfig->getConfigList(['group'=> '0'],true,'sort ace'));
        return $this->fetch();
    }

    /**
     * 邮件修改
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function email(){
        $this->common();
        $this->assign('list', $this->logicConfig->getConfigList(['group'=> '1' ],true,'sort ace'));
        return $this->fetch();
    }

    /**
     * 管理员信息修改
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function profile(){

        //POST 提交修改
        $this->request->isPost() && $this->result($this->logicAdmin->seveAdminInfo($this->request->post()));

        $this->assign('info',$this->logicAdmin->getAdminInfo(['id' =>is_admin_login()]));

        return $this->fetch();
    }

    /**
     * 管理员密码修改
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function changePwd(){

        //POST 提交修改
        $this->request->isPost() && $this->result($this->logicAdmin->changeAdminPwd($this->request->post()));

        return $this->fetch('changepwd');
    }

    /**
     * Common
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    private function common(){
        $this->request->isPost() && $this->result(
            $this->logicConfig->settingSave(
                $this->request->post()
            )
        );
    }
}