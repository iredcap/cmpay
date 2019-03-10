<?php

namespace app\common\db;

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