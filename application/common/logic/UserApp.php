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


class UserApp extends BaseLogic
{

    /**
     * 获取商户应用详情
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param bool $field 查询字段
     *
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserAppInfo($where = [], $field = true)
    {
        return $this->getInfo($where, $field);
    }

    /**
     * 存储商户应用信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data
     * @param array $where
     *
     * @return false|int|string
     */
    public function setUserAppInfo($data = [], $where = []){
        return $this->setInfo($data, $where);
    }

    /**
     * 获取所有支持的商户请求识标
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function getAppKeyMap(){
        return $this->getColumn([], 'id,key', $key = 'id');
    }

    /**
     * 获取所有支持的商户请求识标
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return array
     */
    public function getAllowedIpMap(){

        $allowedIpMap =  $this->getColumn([], 'id,auth_ips');
        $checkAllowedIpMap = [];
        foreach ($allowedIpMap as $v) {
            $allowedIp = explode(',',$v);
            for ($i=0;$i< count($allowedIp);$i++){
                $checkAllowedIpMap[] = $allowedIp[$i];
            }
        }
        return $checkAllowedIpMap;
    }


    /**
     * 设置应用某个字段信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 查询字段
     * @param string $value 设置值
     *
     * @return false|int
     */
    public function setUserAppValue($where = [], $field = '', $value = '')
    {
        return $this->setFieldValue($where, $field, $value);
    }

    /**
     * 获取应用某个字段信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 查询字段
     * @param null $default 默认值
     *
     * @return mixed
     */
    public function getUserAppValue($where = [], $field = '', $default = null)
    {
        return $this->getValue($where, $field, $default);
    }
}