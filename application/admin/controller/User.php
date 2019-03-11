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
use app\common\library\enum\UserStatusEnum;

class User extends BaseAdmin
{

    /**
     * 商户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){

        return $this->fetch();
    }

    /**
     * 商户列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getList(){
        $where = [];

        //组合搜索
        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        !empty($this->request->param('username')) && $where['username']
            = ['like', '%'.$this->request->param('username').'%'];

        !empty($this->request->param('email')) && $where['account']
            = ['like', '%'.$this->request->param('email').'%'];

        $where['status'] = ['eq', $this->request->get('status',UserStatusEnum::ENABLE)];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicUser->getUserList($where, true, 'create_time desc', false);

        $count = $this->logicUser->getUserCount($where);

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
     * 添加商户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function add(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicUser->addUser($this->request->post()));

        return $this->fetch();
    }

    /**
     * 编辑商户基本信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function edit(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicUser->editUser($this->request->post()));
        //获取商户详细信息
        $this->assign('user',$this->logicUser->getUserInfo(['uid' =>$this->request->param('id')]));

        return $this->fetch();
    }

    /**
     * 修改商户状态
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $id
     * @param bool $status
     */
    public function changeStatus($id = 0,$status = false)
    {

        $this->result($this->logicUser->setUserStatus(['uid'=>$id], $status == 1 ? '0': '1'));
    }

    /**
     * 删除商户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function del(){
        // post 是提交数据
        $this->request->isPost() && $this->result(
            $this->logicUser->delUser(
                [
                    'uid' => $this->request->param('id')
                ])
        );
        // get 直接报错
        $this->error([ CodeEnum::ERROR,'未知错误']);
    }

    /**
     * 认证信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function auth(){
        return $this->fetch();
    }

    /**
     * 商户认证信息列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getAuthList(){
        $where = [];

        //组合搜索
        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        $where['status'] = ['eq', $this->request->get('status','1')];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicUser->getUserAuthList($where, true, 'create_time desc', false);

        $count = $this->logicUser->getUserAuthCount($where);

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
     * 认证详细信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function userAuthInfo(){
        // post 是提交数据
        $this->request->isPost() && $this->result($this->logicUser->saveUserAuth($this->request->post()));
        //获取认证详细信息
        $auth = $this->logicUser->getUserAuthInfo(['uid' =>$this->request->param('id')]);
        $auth['card'] = json_decode($auth['card'],true);

        $this->assign('auth',$auth);

        return $this->fetch();
    }

    /**
     * 分润设置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function profit(){
        // post 是提交数据
        if ($this->request->isPost()){
            $data = $this->request->post('r/a');
            foreach ($data as $key => $item) {
                //查
                $profit = $this->logicUser->getUserProfitInfo(['uid' => $item['uid'], 'cnl_id' => $item['cnl_id']]);
                if ($profit) {
                    $data_update[] = [
                        'id' => $profit['id'],
                        'uid' => $item['uid'],
                        'cnl_id' => $item['cnl_id'],
                        'urate' => $item['urate'],
                        'grate' => $item['grate']
                    ];
                } else {
                    $data_update[] = [
                        'uid' => $item['uid'],
                        'cnl_id' => $item['cnl_id'],
                        'urate' => $item['urate'],
                        'grate' => $item['grate']
                    ];
                }

            }
            $this->result($this->logicUser->saveUserProfit($data_update));
        };
        //所有渠道列表
        $channel = $this->logicPay->getAccountList([],true, 'create_time desc',false);

        //获取商户分润详细信息
        $userProfit = $this->logicUser->getUserProfitList(['uid' =>$this->request->param('id')]);
        if ($userProfit) {
            foreach ($userProfit as $item) {
                $_tmpData[$item['cnl_id']] = $item;
            }
        }

        //重组渠道列表
        if ($channel) {
            foreach ($channel as $key => $item) {
                //dump($item);
                $channel[$key]['urate']    = isset($_tmpData[$item['id']]['urate']) ? $_tmpData[$item['id']]['urate'] : $item['urate'];
                $channel[$key]['grate'] = isset($_tmpData[$item['id']]['grate']) ? $_tmpData[$item['id']]['grate'] : $item['grate'];
            }
        }
        $this->assign('list', $channel);;

        return $this->fetch();
    }
}
