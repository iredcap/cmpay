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

namespace app\common\service\worker;

use app\common\library\HttpHeader;
use app\api\service\Rest;
use app\common\model\OrdersNotify;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use think\Db;
use think\Log;
use think\queue\Job;

class AutoOrderNotify
{
    /**
     * 延时
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var int
     */
    protected static $delay = 15;

    /**
     * fire方法是消息队列默认调用的方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Job $job
     * @param $data
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \app\common\library\exception\ParameterException
     */
    public function fire(Job $job,$data){
        // 如有必要,可以根据业务需求和数据库中的最新数据,判断该任务是否仍有必要执行.
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }
        //查单
        $order = (new OrdersNotify())->where(['order_id' => $data['id']]);
        //处理队列
        $result = $this->doJob($data);
        if ($result) {
            //成功记录数据
            $order->update($result);
            //如果任务执行成功， 记得删除任务
            $job->delete();
            print("<info>The Order Job ID " . $data['id'] ." has been done and deleted"."</info>\n");
        }else{

            //失败记录数据
            $order->update([
                'times'   => $job->attempts()
            ]);
            if ($job->attempts() > 5) {
                //超过5次  停止发送
                print("<warn>The Order Job ID " . $data['id'] . " has been deleted and retried more than 5 times!" . "</warn>\n");
                $job->delete();
            }else{
                print("<info>The Order Job ID " . $data['id'] ." will be availabe again after ". $job->attempts() * self::$delay ." s."."</info>\n");
                $job->release($job->attempts() * self::$delay); //$delay为延迟时间，表示该任务延迟2秒后再执行

            }

        }
    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return bool
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){
        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \app\common\library\exception\ParameterException
     */
    private function doJob($data) {

        // 根据消息中的数据进行实际的业务处理...
        if ($data['create_time'] <= time()){

            //要签名的数据
            $to_sign_data =  $this->buildResponseData($data);

            //签名头部
            $header = [
                'user-agent'    =>  "Mozilla/4.0 (compatible; MSIE 7.0; cmpay webhook 1.0; Trident/4.0; SV1; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)",
                'content-type'  =>  "application/json; charset=UTF-8",
                HttpHeader::X_CA_NONCE_STR     =>  Rest::createUniqid(),
                HttpHeader::X_CA_TIMESTAMP     =>  Rest::getMicroTime()
            ];
            //签名串
            $header[HttpHeader::X_CA_SIGNATURE] =  $this->buildSignStr(json_encode($to_sign_data['charge']), $header);

            try{

                $client = new Client([
                    'headers' => $header
                ]);
                $response = $client->request(
                    'POST', $data['notify_url'],
                    [
                        //TODO 处理发送数据
                        'json' => $to_sign_data,
                    ]
                );

                $statusCode = $response->getStatusCode();
                $contents = $response->getBody()->getContents();
                // JSON转换对象
                $contentsObj = json_decode($contents);

                if ( $statusCode == 200 && !is_null($contentsObj)){
                    //判断放回是否正确
                    if ($contentsObj->result_code == "OK" && $contentsObj->result_msg == "SUCCESS"){
                        //TODO 更新写入数据
                        return [
                            'result'   => $contents,
                            'is_status'   => $statusCode
                        ];
                    }
                    return false;
                }
                return false;
            }catch (RequestException $e){
                Log::error('Notify Error:['.$e->getMessage().']');
                return false;
            }
        }
        return false;
    }

    /**
     * 构建返回数据对象
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return array
     */
    private function buildResponseData($data){
        //除去不需要字段
        unset($data['id']);
        unset($data['uid']);
        unset($data['cnl_id']);
        unset($data['trade_no']);
        unset($data['status']);
        unset($data['create_time']);
        unset($data['update_time']);

        //组合参数
        $payload['result_code'] = 'OK';
        $payload['result_msg'] = 'SUCCESS';
        $payload['charge'] = $data;
        //返回
        return $payload;
    }

    /**
     * 生成签名串
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $to_sign_data
     * @param $header
     *
     * @return string
     * @throws \app\common\library\exception\ParameterException
     */
    private function buildSignStr($to_sign_data,$header){
        $_to_sign_data = utf8_encode($header[HttpHeader::X_CA_NONCE_STR])
            ."\n" . utf8_encode($header[HttpHeader::X_CA_TIMESTAMP])
            ."\n" . utf8_encode($to_sign_data);

        return Rest::sign(base64_encode($_to_sign_data));
    }

}