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

namespace app\admin\logic;


use app\common\library\enum\CodeEnum;
use think\Db;
use think\Log;
use think\Validate;

class Admin extends BaseAdmin
{
    /**
     * 获取管理员列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     */
    public function getAdminList($where = [], $field = true, $order = '', $paginate = 0)
    {
        return $this->modelAdmin->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取管理员总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return mixed
     */
    public function getAdminCount($where = []){
        return $this->modelAdmin->getCount($where);
    }

    /**
     * 获取管理员信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @return mixed
     */
    public function getAdminInfo($where = [], $field = true)
    {
        return $this->modelAdmin->getInfo($where, $field);
    }


    /**
     * 管理信息存储
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array
     */
    public function seveAdminInfo($data){

        $validate = $this->validateAdmin->scene($data['scene'])->check($data);

        if (!$validate) {

            return ['code' => CodeEnum::ERROR, 'msg' => $this->validateAdmin->getError()];
        }
        //TODO 修改数据
        Db::startTrans();
        try{
            if (empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password'] = data_md5_key($data['password']);
            }

            $this->modelAdmin->setInfo($data);

            $action = isset($data['id']) ? '编辑' : '新增';

            action_log($action, $action . '管理员信息，' . $data['nickname']);

            Db::commit();
            return [ 'code' => CodeEnum::SUCCESS,'msg' => $action . '管理员信息成功'];
        }catch (\Exception $ex){
            Db::rollback();
            Log::error($ex->getMessage());
            return [ 'code' => CodeEnum::ERROR, 'msg' => config('app_debug') ? $ex->getMessage() : '未知错误'];
        }

    }

    /**
     * 修改密码
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     *
     * @return array
     */
    public function changeAdminPwd($data){
        //数据验证'repassword'=>'require|confirm:password'
        $rules  = [
            'oldPassword'   => 'require',
            'password'   => 'require',
            'repassword' => 'require|confirm:password',
        ];

        $oldPwd = data_md5_key($data['oldPassword']);
        $newPwd = data_md5_key($data['password']);
        $user = $this->logicAdmin->getAdminInfo(['id' => is_admin_login()]);

        //验证原密码
        if ( $oldPwd == $user['password']) {
            $validate = new Validate($rules);;
            if (!$validate->check($data)) {
                return ['code' => CodeEnum::ERROR, 'msg' => $validate->getError()];
            }

            $result = $this->setAdminValue(['id' => is_admin_login()], 'password', $newPwd);
            action_log('修改', '管理员ID'. is_admin_login() .'密码修改');

            return $result && !empty($result) ? ['code' => CodeEnum::SUCCESS, 'msg' => '修改密码成功']
                : ['code' => CodeEnum::ERROR, 'msg' => '修改失败'];
        }else{
            return ['code' => CodeEnum::ERROR, 'msg' => '原密码不正确'];
        }
    }

    /**
     * 设置管理员信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function setAdminValue($where = [], $field = '', $value = '')
    {
        return $this->modelAdmin->setFieldValue($where, $field, $value);
    }

    /**
     * 授权用户组
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array
     */
    public function userAuth($data){

        if ( $data['id'] == 1) {

            return ['code' => CodeEnum::ERROR, 'msg' => '天神不能授权哦~'];
        }

        $where = ['uid' => ['in', $data['id']]];

        $this->modelAuthGroupAccess->deleteInfo($where, true);

        if (empty($data['role_ids'])) {

            return ['code' => CodeEnum::SUCCESS, 'msg' => '授权成功'];
        }

        $add_data = [];

        foreach ($data['role_ids'] as $group_id) {

            $add_data[] = ['uid' => $data['id'], 'group_id' => $group_id];
        }

        Db::startTrans();
        try{

            $this->modelAuthGroupAccess->setList($add_data);

            action_log('授权', '管理员ID'. $data['id'] . '用户组权限');

            Db::commit();
            return ['code' => CodeEnum::SUCCESS, 'msg' => '授权成功'];
        }catch (\Exception $e){
            Db::rollback();
            Log::error($e->getMessage());
            return ['code' => CodeEnum::ERROR, $e->getMessage()];
        }
    }

    /**
     * 获取管理员的所有下级
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function getSubUserIds($id = 0, $data = [])
    {

        $member_list = $this->modelAdmin->getList(['leader_id' => $id], 'id', 'id asc', false);

        foreach ($member_list as $v)
        {

            if (!empty($v['id'])) {

                $data[] = $v['id'];

                $data = array_unique(array_merge($data, $this->getSubUserIds($v['id'], $data)));
            }

            continue;
        }

        return $data;
    }

    /**
     * 删除管理员
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     *
     * @return array
     */
    public function userDel($where){

        $result = $this->modelAdmin->deleteInfo($where);

        $result && action_log('删除', '删除管理，where：' . http_build_query($where));

        return $result ? ['code' => CodeEnum::SUCCESS, 'msg' =>'删除管理员成功', ''] : ['code' => CodeEnum::ERROR, 'msg' =>$this->modelMember->getError(), ''];
    }
}
