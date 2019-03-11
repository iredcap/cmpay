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

namespace app\common\logic;

use app\common\library\enum\CodeEnum;
use think\Db;
use think\Log;

class Pay extends BaseLogic
{

    /**
     * 下单时通过pay_code 获取渠道下的可用商户配置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     * @return mixed
     */
    public function getAllowedAccount($order){
        //1.传入支付方式获取对应渠道cnl_id
        $codeInfo = $this->modelPayCode->getInfo(['code' => $order['channel']], 'id as co_id,cnl_id')->toArray();

        //2.cnl_id获取支持该方式的渠道列表
        $channels = $this->modelPayChannel->getColumn(['id' => ['in', $codeInfo['cnl_id']], 'status' => ['eq','1']],
            'id,name,action,timeslot,return_url,notify_url');

        //3.规则排序选择合适渠道
        /*******************************/
        //TODO 写选择规则  时间、状态、费率 等等
        //规则处理  我先简便写一下
        $channelsMap = [];
        foreach ($channels as $key => $val){
            $timeslot = json_decode($val['timeslot'],true);
            if ( strtotime($timeslot['start']) < time() && time() < strtotime($timeslot['end']) ){
                $channelsMap[$key] = $val;
            }
        }

        //判断可用
        if (empty($channelsMap)){
            return ['errorCode' => '400006','msg' => 'Route Payment Error. [No available channels]'];
        }
        $channel =  $channelsMap[array_rand($channelsMap)];

        /*******************************/
        //3.获取该渠道下可用账户
        $accounts = $this->modelPayAccount->getColumn(['cnl_id' => ['eq',$channel['id']], 'status' => ['eq','1']],
            'id,co_id,name,single,daily,timeslot,param');

        //4.规则取出可用账户
        /*******************************/
        //TODO 写选择规则  时间、状态、费率 等等
        //规则处理  我先简便写一下
        $accountsMap = [];
        foreach ($accounts as $key => $val){
            $timeslot = json_decode($val['timeslot'],true);
            if ( in_array($codeInfo['co_id'], str2arr($val['co_id'])) && strtotime($timeslot['start']) < time() && time() < strtotime($timeslot['end']) ){
                $accountsMap[$key] = $val;
            }
        }

        //判断可用
        if (empty($accountsMap)){
            return ['errorCode' => '400008','msg' => 'Route Payment Error. [No available merchants account.]'];
        }

        $account =  $accountsMap[array_rand($accountsMap)];
        $accountConf = json_decode($account['param'],true);
        //判断配置是否正确
        if (is_null($accountConf)){
            return ['errorCode' => '400008','msg' => 'Route Payment Error. [Payment account was misconfigured.]'];
        }
        //配置合并
        $configMap = array_merge($channel, $accountConf);

        //添加订单支付通道ID
        $this->logicOrders->setOrderValue(['trade_no' => $order['trade_no']], 'cnl_id', $account['id']);
        /*******************************/
        return [
            'channel' => $configMap['action'],
            'action' => $order['channel'],
            'config' =>  $configMap
        ];

    }

    /**
     * 获取所有支持的支付方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function getAppCodeMap(){
        return $this->modelPayCode->getColumn(['status'=>1], 'id,code', $key = 'id');
    }

    /**
     * 获取支付方式列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param $field
     * @param string $order
     * @return mixed
     */
    public function getCodeList($where = [], $field = true, $order = 'create_time desc',$paginate = 15){
        return $this->modelPayCode->getList($where,$field, $order, $paginate);
    }

    /**
     * 获取支付方式总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getCodeCount($where = []){
        return $this->modelPayCode->getCount($where);
    }

    /**
     * 获取渠道列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param $field
     * @param string $order
     * @return mixed
     */
    public function getChannelList($where = [], $field = true, $order = 'create_time desc',$paginate = 15){
        return $this->modelPayChannel->getList($where,$field, $order, $paginate);
    }

    /**
     * 获取渠道总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getChannelCount($where = []){
        return $this->modelPayChannel->getCount($where);
    }

    /**
     * 获取渠道账户列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param $field
     * @param string $order
     * @return mixed
     */
    public function getAccountList($where = [], $field = true, $order = 'create_time desc',$paginate = 15){
        return $this->modelPayAccount->getList($where,$field, $order, $paginate);
    }

    /**
     * 获取渠道账户总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getAccountCount($where = []){
        return $this->modelPayAccount->getCount($where);
    }

    /**
     * 获取渠道信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @return mixed
     */
    public function getChannelInfo($where = [], $field = true)
    {
        return $this->modelPayChannel->getInfo($where, $field);
    }

    /**
     * 获取渠道账户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @return mixed
     */
    public function getAccountInfo($where = [], $field = true)
    {
        return $this->modelPayAccount->getInfo($where, $field);
    }

    /**
     * 获取支付方式信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @return mixed
     */
    public function getCodeInfo($where = [], $field = true)
    {
        return $this->modelPayCode->getInfo($where, $field);
    }

    /**
     * 添加一个渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array|string
     */
    public function saveChannelInfo($data){
        //TODO 数据验证
        $validate = $this->validatePayChannel->check($data);

        if (!$validate) {
            return [  'code' => CodeEnum::ERROR,  'msg' => $this->validatePayChannel->getError()];
        }

        //TODO 添加数据
        Db::startTrans();
        try{

            //时间存储
            $data['timeslot'] = json_encode($data['timeslot']);

            $this->modelPayChannel->setInfo($data);

            $action = isset($data['id']) ? '编辑' : '新增';

            action_log($action,  '支付渠道' . $data['name'] );

            Db::commit();
            return ['code' =>  CodeEnum::SUCCESS,  'msg' => $action . '渠道成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '未知错误'];
        }

    }


    /**
     * 添加一个渠道账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array|string
     */
    public function saveAccountInfo($data){
        //TODO 数据验证
        $validate = $this->validatePayAccount->check($data);

        if (!$validate) {
            return [  'code' => CodeEnum::ERROR,  'msg' => $this->validatePayAccount->getError()];
        }

        //TODO 添加数据
        Db::startTrans();
        try{

            //时间存储
            $data['timeslot'] = json_encode($data['timeslot']);
            //方式存储
            $data['co_id'] =  isset($data['co_id']) ? arr2str($data['co_id']) : '';

            $this->modelPayAccount->setInfo($data);

            $action = isset($data['id']) ? '编辑' : '新增';

            action_log($action,  '支付渠道账户,' . $data['name'] );

            Db::commit();
            return ['code' =>  CodeEnum::SUCCESS,  'msg' => $action . '渠道账户成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '未知错误'];
        }

    }

    /**
     * 添加一个方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array|string
     */
    public function saveCodeInfo($data){
        //TODO 数据验证
        $validate = $this->validatePayCode->check($data);

        if (!$validate) {
            return [ 'code' => CodeEnum::ERROR,  'msg' => $this->validatePayCode->getError()];
        }
        //TODO 添加数据
        Db::startTrans();
        try{

            $data['cnl_id'] =  isset($data['cnl_id']) ? arr2str($data['cnl_id']) : '';

            $this->modelPayCode->setInfo($data);

            $action = isset($data['id']) ? '编辑' : '新增';

            action_log($action,  '支付方式,data:' . http_build_query($data) );

            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => $action . '方式成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '未知错误'];
        }

    }


    /**
     * 改变渠道可用性
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @param int $value
     * @return array
     */
    public function setChannelStatus($where,$value = 0){
        Db::startTrans();
        try{
            $this->modelPayChannel->setFieldValue($where, $field = 'status', $value);
            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '修改状态成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '未知错误'];
        }
    }

    /**
     * 删除一个方式
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return array
     */
    public function delCode($where){
        Db::startTrans();
        try{
            $this->modelPayCode->deleteInfo($where);
            action_log('删除', '删除支付方式，ID：'. $where['id']);
            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '删除方式成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '删除支付方式失败'];
        }
    }

    /**
     * 删除一个渠道
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return array
     */
    public function delChannel($where){
        Db::startTrans();
        try{
            $this->modelPayChannel->deleteInfo($where);
            action_log('删除', '删除支付渠道，ID：'. $where['id']);
            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '删除渠道成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '删除渠道失败'];
        }
    }
    /**
     * 删除一个渠道账户
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return array
     */
    public function delAccount($where){
        Db::startTrans();
        try{
            $this->modelPayAccount->deleteInfo($where);
            action_log('删除', '删除支付渠道账户，ID：'. $where['id']);
            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS, 'msg' => '删除渠道账户成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR,  'msg' => config('app_debug') ? $ex->getMessage() : '删除渠道账户失败'];
        }
    }
}