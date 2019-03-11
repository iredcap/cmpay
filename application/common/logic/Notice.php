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

use think\Db;
use think\facade\Log;
use enum\CodeEnum;

class Notice extends BaseLogic
{
    /**
     * 获取通知列表
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
    public function getNoticeList($where = [], $field = true, $order = 'create_time desc',$paginate = 15)
    {
        return $this->getList($where, $field, $order,$paginate);
    }

    /**
     * 获取知总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getNoticeCount($where = []){
        return $this->getCount($where);
    }

    /**
     * 通知信息编辑
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data
     * @return array|string
     */
    public function saveNotice($data = [])
    {
        //数据验证
        $validate = $this->validateNotice->check($data);

        if (!$validate) {

            return [ 'code' => CodeEnum::ERROR, 'msg' => $this->validateNotice->getError()];
        }

        Db::startTrans();
        try{
            //数据处理
            if(!empty($data['attachments']))  $data['attachments'] = json_encode(array_values($data['attachments']));

            $this->modelNotice->setInfo($data);

            $action = isset($data['id']) ? '编辑' : '新增';

            action_log($action, $action . '站点通知。标题：'. $data['title']);

            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => $action . '通知成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR, config('app_debug') ? $ex->getMessage() : '未知错误'];
        }

    }

    /**
     * 通知删除
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return array|string
     */
    public function delNotice($where = [])
    {
        Db::startTrans();
        try{
            $this->modelNotice->deleteInfo($where);

            Db::commit();

            action_log('删除', '删除站点通知。ID：'. $where['id']);

            return ['code' => CodeEnum::SUCCESS, 'msg' =>'通知删除成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return ['code' => CodeEnum::ERROR, config('app_debug') ? $ex->getMessage() : '未知错误'];
        }
    }

}
