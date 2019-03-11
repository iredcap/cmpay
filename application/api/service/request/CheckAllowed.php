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


namespace app\api\service\request;
use app\common\library\exception\ForbiddenException;
use think\Log;
use think\Request;

/**
 * 检验接口允许IP
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class CheckAllowed extends ApiCheck
{

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Request $request
     * @return mixed|void
     * @throws ForbiddenException
     */
    public function doCheck(Request $request)
    {
        // 获取Ip Map
        $checkAllowedIpMap = (array)$this->logicApi->getallowedIpMap();
        //客户端IP
        $ip = $request->ip();
        //ip参数拆分成数组
        $check_ip_arr= explode('.',$ip);
        if(!in_array($ip, $checkAllowedIpMap)) {
            $allowed = false;
            foreach ($checkAllowedIpMap as $val){
                if(strpos($val,'*')!==false){
                    //发现有*号替代符
                    $arr = array();
                    $arr = explode('.', $val);
                    $allowed = true;
                    //用于记录循环检测中是否有匹配成功的
                    for ( $i=0; $i<4; $i++ ){
                        if( $arr[$i] != '*' ){
                            //不等于* 就要进来检测，如果为*符号替代符就不检查
                            if( $arr[$i] != $check_ip_arr[$i] ){
                                $allowed = false;
                                break;
                                //终止检查本个ip 继续检查下一个ip
                            }
                        }
                    }
                    //end for
                    if( $allowed) {
                        //如果是true则终止匹配
                        break;
                    }
                }
            }
            //存在性
            if ( !$allowed ) {
                throw new ForbiddenException([
                    'msg'=>'Invalid Request.[ Request IP not authorized.]',
                    'errorCode'=> 400003
                ]);
            }
        }

    }
}
