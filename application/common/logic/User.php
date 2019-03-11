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

class User extends BaseLogic
{
    /**
     * 获取商户列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string|bool $field 查询字段
     * @param string $order 排序
     * @param int|bool $paginate 分页
     *
     * @return false|\PDOStatement|string|\think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserList($where = [], $field = '*', $order = '', $paginate = 20)
    {
        return $this->getList($where, $field, $order, $paginate);
    }


    /**
     * 获取商户总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     *
     * @return float|string
     */
    public function getUserCount($where = []){
        return $this->getCount($where);
    }

    /**
     * 获取商户信息详情
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
    public function getUserInfo($where = [], $field = true)
    {
        return $this->getInfo($where, $field);
    }

    /**
     * 存储商户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data
     * @param array $where
     *
     * @return false|int|string
     */
    public function setUserInfo($data = [], $where = []){
        return $this->setInfo($data, $where);
    }

    /**
     * 设置商户某个字段的值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 设置字段
     * @param string $value 设置值
     *
     * @return false|int
     */
    public function setUserValue($where = [], $field = '', $value = '')
    {
        return $this->setFieldValue($where, $field, $value);
    }

    /**
     * 获取商户某个字段的值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 查询字段
     * @param null $default 默认值
     *
     * @return mixed
     */
    public function getUserValue($where = [], $field = '', $default = null)
    {
        return $this->getValue($where, $field, $default);
    }

}