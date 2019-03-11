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

namespace tool;

use think\db\Where;

class Query extends \think\db\Query
{
    /**
     * @inheritdoc
     */
    public function where($field, $op = null, $condition = null)
    {
        // $where参数转换Where类语法兼容5.0
        if (!$field instanceof Where) {
            // $filed = [0 => ['key', 'exp', 'value'], ...]语法兼容
            if (array_key_exists('0', $field)) {
                $newField = [];
                foreach ($field as $item) {
                    $key = array_shift($item);
                    $newField[$key] = $item;
                }
                $field = $newField;
            }
            $field = new Where($field);
        }
        return parent::where($field, $op, $condition);
    }
}