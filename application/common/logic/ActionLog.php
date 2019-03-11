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

namespace app\common\logic;


use enum\CodeEnum;

class ActionLog extends BaseLogic
{

    /**
     * 增加一个操作日志
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $action 动作
     * @param string $describe
     *
     * @return array
     */
    public function logAdd($action = '', $describe = '')
    {
        $request = request();$module = $request->module();

        if ($module == 'admin'){
            $uid = session('admin_info')['id'];
        }else if ($module == 'index'){
            $uid = empty(session('user_info'))?'999999':session('user_info')['uid'];
        }

        $data['uid']       = $uid;
        $data['module']    = $module;
        $data['ip']        = $request->ip();
        $data['url']       = $request->url();
        $data['action']    = $action;
        $data['describe']  = $describe;

        $res = $this->setInfo($data);

        return $res || !empty($res) ? ['code' => CodeEnum::SUCCESS, 'msg' =>'日志添加成功', '']
            : ['code' => CodeEnum::ERROR, 'msg' => '加入操作日志失败'];
    }

    /**
     * 获取日志总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return mixed
     */
    public function getActionLogCount($where = []){
        return $this->getCount($where);
    }

    /**
     * 日志列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     *
     * @return false|\PDOStatement|string|\think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getActionLogList($where = [] , $field = true, $order = 'create_time desc',$paginate = 0){
        $this->limit = !$paginate;
        return $this->getList($where, $field, $order,$paginate);
    }

    /**
     * 删除操作日志
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     *
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function logDel($where = [])
    {
        return $this->deleteInfo($where) ? ['code' => CodeEnum::SUCCESS, 'msg' =>'日志删除成功', '']
            : ['code' => CodeEnum::ERROR, 'msg' => '删除操作日志失败'];
    }
}