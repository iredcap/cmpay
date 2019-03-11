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

namespace app\admin\logic;

use enum\CodeEnum;
use app\common\logic\BaseLogic;

class BaseAdmin extends BaseLogic
{
    /**
     * 权限检测
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $url
     * @param array $url_list
     * @param array $allow_url_list
     * @return array
     */
    public function authCheck($url = '', $url_list = [],$allow_url_list =[])
    {
        if (is_admin_login() == 1) {

            return [CodeEnum::SUCCESS, '权限检查通过'];
        }

        if (!empty($allow_url_list)) {

            foreach ($allow_url_list as $v) {

                if (strpos($url, strtolower($v)) !== false) {

                    return [CodeEnum::SUCCESS, '权限检查通过'];
                }
            }
        }
        $result = in_array($url, array_map("strtolower", $url_list)) ? true : false;

        !('index/index' == $url && !$result) ?: clear_admin_login_session();

        return $result ? [CodeEnum::SUCCESS, '权限检查通过'] : [CodeEnum::ERROR, '未授权操作'];
    }

    /**
     * 获取过滤后的菜单树
     */
    public function getMenuTree($menu_list = [], $url_list = [])
    {

        foreach ($menu_list as $key => $menu_info) {

            list($status, $message) = $this->authCheck(strtolower($menu_info['url']), $url_list);

            [$message];

            if ((!is_admin_login() == 1 && CodeEnum::ERROR == $status) || !empty($menu_info['is_hide'])) {

                unset($menu_list[$key]);
            }
        }

        return $this->getListTree($menu_list);
    }

    /**
     * 获取列表树结构
     */
    public function getListTree($list = [])
    {

        if (is_object($list)) {

            $list = $list->toArray();
        }

        return list_to_tree(array_values($list), 'id', 'pid', 'child');
    }

    /**
     * 通过完整URL获取检查标准URL
     */
    public function getCheckUrl($full_url = '')
    {

        $temp_url = sr($full_url, URL_ROOT);

        $url_array_tmp = explode(SYS_DS_PROS, $temp_url);

        $return_url = $url_array_tmp[1] . SYS_DS_PROS . $url_array_tmp[2];

        $index = strpos($return_url, '.');

        $index !== false && $return_url = substr($return_url, DATA_DISABLE, $index);

        return $return_url;
    }

    /**
     * 过滤页面内容
     */
    public function filter($content = '', $url_list = [])
    {

        $results = [];

        preg_match_all('/<ob_link>.*?<\/ob_link>/', $content, $results);

        foreach ($results[0] as $a)
        {

            $match_results = [];

            preg_match_all('/href="(.+?)"|url="(.+?)"/', $a, $match_results);

            $full_url = '';

            if (empty($match_results[1][0]) && empty($match_results[2][0])) {

                continue;
            } elseif (!empty($match_results[1][0])) {

                $full_url = $match_results[1][0];
            } else {

                $full_url = $match_results[2][0];
            }

            if (!empty($full_url)) {

                $result = $this->authCheck($this->getCheckUrl($full_url), $url_list);

                $result[0] != CodeEnum::SUCCESS && $content = sr($content, $a);
            }
        }

        return $content;
    }

    /**
     * 获取首页数据
     */
    public function getIndexData()
    {

        $query = new \think\db\Query();

        $system_info_mysql = $query->query("select version() as v;");

        // 系统信息
        $data['think_version']  = App::version();
        $data['os']             = PHP_OS;
        $data['software']       = $_SERVER['SERVER_SOFTWARE'];
        $data['mysql_version']  = $system_info_mysql[0]['v'];
        $data['upload_max']     = ini_get('upload_max_filesize');
        $data['php_version']    = PHP_VERSION;

        // 产品信息
        $data['product_name']   = 'CaoMao - 开源聚合支付系统';
        $data['author']         = 'IredCap Inc.';
        $data['website']        = 'www.iredcap.cn';

        return $data;
    }

    /**
     * 数据排序设置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param null $model
     * @param null $param
     * @return array
     */
    public function setSort($model = null, $param = null)
    {

        $model_str = MODEL_LAYER_NAME . $model;

        $obj = $this->$model_str;

        $result = $obj->setFieldValue(['id' => (int)$param['id']], 'sort', (int)$param['value']);

        return $result ? [CodeEnum::SUCCESS, '操作成功'] : [CodeEnum::ERROR, $obj->getError()];
    }

}