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

namespace app\common\model;

use think\Db;
use think\Log;
use think\Model;

/**
 * Class BaseModel
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class BaseModel extends Model
{
    // 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = true;
    // 创建时间字段
    protected $createTime = 'create_time';
    // 更新时间字段
    protected $updateTime = 'update_time';

    /**
     * 连接查询
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $join = [];

    /**
     * 是否锁
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var
     */
    protected $islock;

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data
     * @param array $where
     * @return false|int|string
     */
    final protected function setInfo($data = [], $where = [])
    {

        $pk = $this->getPk();

        if (empty($data[$pk])) {

            $this->allowField(true)->save($data, $where);

            return $this->getQuery()->getLastInsID();

        } else {

            is_object($data) && $data = $data->toArray();

            !empty($data['create_time']) && is_string($data['create_time']) && $data['create_time'] = strtotime($data['create_time']);

            $default_where[$pk] = $data[$pk];

            return $this->updateInfo(array_merge($default_where, $where), $data);
        }
    }

    /**
     * 更新数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param array $data
     * @return false|int
     */
    final protected function updateInfo($where = [], $data = [])
    {

        $data['update_time'] = time();

        return $this->allowField(true)->isUpdate(true)->save($data, $where);
    }

    /**
     * 统计数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $stat_type
     * @param string $field
     * @return mixed
     */
    final public function stat($where = [], $stat_type = 'count', $field = 'id')
    {
        return $this->where($where)->$stat_type($field);
    }

    /**
     * 数据增减
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $action_type
     * @param string $field
     * @param string $value
     * @return mixed
     */
    final protected function setIncOrDec($where = [], $action_type = 'setInc', $field = 'amount',$value)
    {
        return $this->where($where)->$action_type($field,$value);
    }

    /**
     * 设置数据列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $data_list
     * @param bool $replace
     * @return array|false
     * @throws \Exception
     */
    final protected function setList($data_list = [], $replace = false)
    {

        $return_data = $this->saveAll($data_list, $replace);

        return $return_data;
    }

    /**
     * 设置某个字段值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $value
     * @return false|int
     */
    final protected function setFieldValue($where = [], $field = '', $value = '')
    {

        return $this->updateInfo($where, [$field => $value]);
    }

    /**
     * 删除数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $is_true
     * @return false|int
     */
    final protected function deleteInfo($where = [], $is_true = false)
    {

        if ($is_true) {

            $return_data = $this->where($where)->delete();

        } else {

            $return_data = $this->setFieldValue($where, 'status', '-1');
        }

        return $return_data;
    }

    /**
     * 获取某个列的数组
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */
    final protected function getColumn($where = [], $field = '', $key = '')
    {
        return Db::name($this->name)->where($where)->cache(true,300)->column($field, $key);
    }

    /**
     * 获取总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     *
     * @return int|string
     * @throws \think\Exception
     */
    final protected function getCount($where = [],$field ='*'){
        return Db::name($this->name)->where($where)->count($field);
    }

    /**
     * 获取某个字段的值
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param null $default
     * @param bool $force
     * @return mixed
     */
    final protected function getValue($where = [], $field = '', $default = null, $force = false)
    {

        return Db::name($this->name)->where($where)->value($field, $default, $force);
    }

    /**
     * 获取单条数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final protected function getInfo($where = [], $field = true)
    {
        $query = !empty($this->join) ? $this->join($this->join) : $this;

        $info = $query->where($where)->field($field)->find();

        $this->join = [];

        return $info;
    }

    /**
     * 获取列表数据
     * 若不需要分页 $paginate 设置为 false
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return false|\PDOStatement|string|\think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final protected function getList($where = [], $field = true, $order = '', $paginate = 0)
    {

        empty($this->join) && !isset($where['status']) && $where['status'] = ['neq', -1];

        if (empty($this->join)) {

            !isset($where['status']) && $where['status'] = ['neq', -1];

            $query = $this;

        } else {

            $query = $this->join($this->join);
        }

        $query = $query->where($where)->order($order)->field($field);

        !empty($this->group) && $query->group($this->group);

        if (false === $paginate) {

            !empty($this->limit) && $query->limit((input('page') - 1) * input('limit'),input('limit'));

            $list = $query->select();

        } else {
            $list_rows = empty($paginate) || !$paginate ? 15 : $paginate;

            $list = $query->paginate(input('list_rows', $list_rows), false, ['query' => request()->param()]);
        }

        $this->join = []; $this->limit = []; $this->group = [];

        return $list;
    }

    /**
     * 原生查询
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $sql
     * @return mixed
     */
    final protected function query($sql = '')
    {

        return Db::query($sql);
    }

    /**
     * 原生执行
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $sql
     * @return int
     */
    final protected function execute($sql = '')
    {

        return Db::execute($sql);
    }

    /**
     * 重写获取器 兼容 模型|逻辑|验证|服务 层实例获取
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $name
     * @return mixed|Model|\think\Validate
     */
    public function __get($name)
    {

        $layer = $this->getLayerPre($name);

        if (false === $layer) {

            return parent::__get($name);
        }

        $model = sr($name, $layer);
        return VALIDATE_LAYER_NAME == $layer ? validate($model) : model($model, $layer);
    }

    /**
     * 获取层前缀
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $name
     * @return bool|mixed
     */
    public function getLayerPre($name)
    {

        $layer = false;

        $layer_array = [MODEL_LAYER_NAME, LOGIC_LAYER_NAME, VALIDATE_LAYER_NAME, SERVICE_LAYER_NAME];

        foreach ($layer_array as $v)
        {
            if (str_prefix($name, $v)) {

                $layer = $v;

                break;
            }
        }

        return $layer;
    }
}