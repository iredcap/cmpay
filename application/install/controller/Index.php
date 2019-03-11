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

namespace app\install\controller;

use enum\CodeEnum;
use app\install\logic\Install;
use think\Controller;

/**
 * 安装控制器
 */
class Index extends Controller
{

    protected $install;

    /**
     * Index constructor.
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();
        $this->install = new Install();
        'complete' != $this->request->action() && $this->checkInstall();
    }

    /**
     * 检查是否已安装
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function checkInstall()
    {
        file_exists(RES_PATH . 'install.lock') && $this->error('已经成功安装，请勿重复安装!','/');
    }

    /**
     * 安装引导首页
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index()
    {
        !function_exists('saeAutoLoader') && $dirfile = check_dirfile();

        $this->assign('dirfile', $dirfile);

        $this->assign('env', check_env());

        $this->assign('func', check_func());

        return $this->fetch();
    }


    /**
     * 站点数据写入
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param null $site
     * @param null $admin
     *
     * @return array|mixed
     */
    public function step1($site = null, $admin = null)
    {

        if ($this->request->isPost()) {

            $this->install->checkSiteConfig($site,$admin);

            return  [ 'code' => CodeEnum::SUCCESS, 'msg' => '保存数据成功'];

        }
        return $this->fetch();
    }

    /**
     * 安装数据写入
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param null $db
     *
     * @return array|bool|mixed
     */
    public function step2($db = null)
    {

        if ($this->request->isPost()) {
            // 检查安装数据
            $this->install->checkDbConfig($db);

            // 开始安装
            return $this->install->install();

        }
        return $this->fetch();
    }

}
