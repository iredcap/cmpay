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

class Config extends BaseLogic
{

    /**
     * 获取配置信息
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
    public function getConfigInfo($where = [], $field = true){
        return $this->getInfo($where, $field);
    }

    /**
     * 获取配置列表
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
    public function getConfigList($where = [], $field = true, $order = '', $paginate = 0){
        return $this->getList($where, $field, $order, $paginate);
    }

    /**
     * 设置配置某个字段的值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 设置字段
     * @param string $value 设置值
     *
     * @return false|int
     */
    public function setConfigValue($where = [], $field = '', $value = '')
    {
        return $this->setFieldValue($where, $field, $value);
    }

    /**
     * 获取配置某个字段的值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where 查询条件
     * @param string $field 查询字段
     * @param null $default 默认值
     *
     * @return mixed
     */
    public function getConfigValue($where = [], $field = '', $default = null)
    {
        return $this->getValue($where, $field, $default);
    }

}