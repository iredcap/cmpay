<?php
/**
 * +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *  | Copyright (c) 2019 知行信息科技. All rights reserved.
 * +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

namespace app\admin\controller;

use enum\LayEnum;

class Log extends BaseAdmin
{

    /**
     * 管理行为日志
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){


        return $this->fetch();
    }

    /**
     * 获取管理日志记录
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getList(){

        $where = [];
        //组合搜索
        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        !empty($this->request->param('module')) && $where['module']
            = ['like', '%'.$this->request->param('module').'%'];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicActionLog->getActionLogList($where, true, 'create_time desc', false);

        $count = $this->logicActionLog->getActionLogCount($where);

        $this->result(!$data->isEmpty() ?
            [
                'code' => LayEnum::SUCCESS,
                'msg'=> '',
                'count'=>$count,
                'data'=>$data
            ] : [
                'code' => LayEnum::ERROR,
                'msg'=> '暂无数据',
                'count'=>$count,
                'data'=>$data
            ]
        );
    }

    /**
     * 日志删除
     */
    public function logDel($id = 0)
    {
        $this->result($this->logicActionLog->logDel(['id' => $id]));
    }

    /**
     * 日志清空
     */
    public function logClean()
    {
        $this->result($this->logicActionLog->logDel(['status' => '1']));
    }

}