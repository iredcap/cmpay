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

namespace app\admin\controller;

use app\common\controller\Common;
use app\common\library\enum\CodeEnum;
use think\App;

class BaseAdmin extends Common
{
    // 授权过的菜单列表
    protected $authMenuList     =   [];

    // 授权过的菜单url列表
    protected $authMenuUrlList  =   [];

    // 授权过的菜单树
    protected $authMenuTree     =   [];

    protected $allow_url_list   =   [
        'index',
        'login'
    ];

    // 菜单视图
    protected $menuView         =   '';

    public function __construct(App $app = null)
    {
        parent::__construct($app);

        // 初始化后台模块信息
        $this->initAdminInfo();

    }

    /**
     * 初始化后台模块信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    final private function initAdminInfo()
    {

        // 验证登录
        !is_admin_login() && $this->redirect('login/index');

        // 获取授权菜单列表
        $this->authMenuList = $this->logicAuthGroupAccess->getAuthMenuList(is_admin_login());

        // 获得权限菜单URL列表
        $this->authMenuUrlList = $this->logicAuthGroupAccess->getAuthMenuUrlList($this->authMenuList);

        // 检查菜单权限
        list($jump_type, $message) = $this->logicBaseAdmin->authCheck(
            strtolower($this->request->controller()."/".$this->request->action()),
            $this->authMenuUrlList,
            $this->allow_url_list
        );

        // 权限验证不通过则跳转提示
        CodeEnum::SUCCESS == $jump_type ?:$this->result($jump_type, $message);

        // 初始化基础数据
       $this->initBaseInfo();

    }


    /**
     * 初始化基础数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    final private function initBaseInfo()
    {

        // 获取过滤后的菜单树
        $this->authMenuTree = $this->logicBaseAdmin->getMenuTree($this->authMenuList, $this->authMenuUrlList);

        // 菜单转换为视图
        $this->menuView = $this->logicMenu->menuToView($this->authMenuTree);

        // 获取默认标题
        $this->title = $this->logicMenu->getDefaultTitle();

        // 设置页面标题
        $this->assign('site_title', $this->title);

        // 菜单视图
        $this->assign('menu_view', $this->menuView);

        // 登录会员信息
        $this->assign('admin_info', session('admin_info'));
    }
}
