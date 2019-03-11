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
namespace IredCap\Pay\Util;

use IredCap\Pay\Constant\Constants;
use IredCap\Pay\Constant\ContentType;
use IredCap\Pay\Constant\HttpHeader;
use IredCap\Pay\Constant\HttpMethod;
use IredCap\Pay\Exception\InvalidResponseException;
use IredCap\Pay\Http\HttpRequest;
use IredCap\Pay\Http\HttpResponse;
use IredCap\Pay\Exception\InvalidParameterException;

class HttpUtil
{
    /**
     * @var array 需要发送的头信息
     */
    private $headers = [];

    /**
     * @var string 需要访问的URL地址
     */
    private $uri = '';
    /**
     * @var array 需要发送的数据
     */
    private $payload = [];


    /**
     * 发送HTTP请求
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $url 如果初始化对象的时候没有设置或者要设置不同的访问URL，可以传本参数
     * @param string $method 请求类型
     * @param array $payload 需要单独返送的GET变量
     * @param int $timeout 连接对方服务器访问超时时间，单位为秒
     *
     * @return mixed|string
     * @throws InvalidParameterException
     */
    public function request($url = '', $method = 'GET', $payload = [], $timeout = 5 ){
        $this->setUrl($url);
        $this->setHeader($payload);
        $this->setVar($payload);
        return $this->send($method, $timeout);
    }


    /**
     * 设置请求头
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $bodys
     *
     */
    private function setHeader($bodys)
    {
        if (empty($bodys)) {
            return;
        }
        if (is_array($bodys)){
            //时间戳
            date_default_timezone_set('PRC');
            $headers[HttpHeader::HTTP_HEADER_CONTENT_TYPE] = ContentType::CONTENT_TYPE_JSON;
            $headers[HttpHeader::HTTP_HEADER_ACCEPT] = ContentType::CONTENT_TYPE_JSON;
            $headers[HttpHeader::HTTP_HEADER_USER_AGENT] = Constants::USER_AGENT;
            $headers[HttpHeader::X_CA_REST_URL] = $this->uri;
            $headers[HttpHeader::X_CA_TIMESTAMP] = strval(SignUtil::getMicroTime());
            //防重放，协议层不能进行重试，否则会报NONCE被使用；如果需要协议层重试，请注释此行
            $headers[HttpHeader::X_CA_NONCE_STR] = strval(SignUtil::createUniqid());
            $headers[HttpHeader::X_CA_AUTH] = HttpRequest::getSecretKey();
            //赋值头信息
            $this->headers = $headers;
        }


    }

    /**
     * 设置要发送的数据信息
     *
     * 注意：本函数只能调用一次，下次调用会覆盖上一次的设置
     *
     * @param array 设置需要发送的数据信息，一个类似于 array('name1'=>'value1', 'name2'=>'value2') 的一维数组
     * @return void
     */
    private function setVar($payload){
        if (empty($payload)) {
            return;
        }
        if (is_array($payload)){
            $this->payload = $payload;
        }
    }

    /**
     * 设置要请求的URL地址
     *
     * @param string $url 需要设置的URL地址
     * @return void
     */
    private function setUrl($url){
        if ($url != '') {
            $this->uri = $url;
        }
    }

    /**
     * 发送HTTP请求核心函数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $method  使用GET还是POST方式访问
     * @param int $timeout  连接对方服务器访问超时时间，单位为秒
     * @param array $options
     *
     * @return mixed|string
     * @throws InvalidParameterException
     */
    private function send($method = 'GET', $timeout = 30, $options = []){
        //处理参数是否为空
        if ($this->uri == ''){
            throw new InvalidParameterException(__CLASS__ .": Access url is empty");
        }
        $response = new HttpResponse();
        //初始化CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->uri);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //设置特殊属性
        if (!empty($options)){
            curl_setopt_array($curl , $options);
        }
        //请求方式判断
        if (HttpMethod::POST == $method){
            curl_setopt($curl, CURLOPT_POST, 1 );
        }

        //设置HTTP缺省头
        if (empty($this->headers)){
            $this->setHeader($this->payload);
        }

        // 生成签名
        $this->headers[HttpHeader::X_CA_SIGNATURE] = SignUtil::to_sign_data($this->headers,$this->uri,$this->payload);

        //处理请求头
        $headersArr = [];
        if (is_array( $this->headers)) {
            if (0 < count( $this->headers)) {
                foreach ( $this->headers as $itemKey => $itemValue) {
                    if (0 < strlen($itemKey)) {
                        array_push($headersArr, $itemKey.":".$itemValue);
                    }
                }
            }
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->payload));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headersArr);

        //发送请求读取输数据
        try{
            $response->setContent(curl_exec($curl));
            $response->setHttpStatusCode(curl_getinfo($curl, CURLINFO_HTTP_CODE));
            $response->setContentType(curl_getinfo($curl, CURLINFO_CONTENT_TYPE));
            $response->setHeaderSize(curl_getinfo($curl, CURLINFO_HEADER_SIZE));
            LogUtil::INFO('Response Body :'. $response->getBody());
            //验签需要返回数据与返回sign比对校验
            if ($response->getHttpStatusCode() < 400){
               SignUtil::to_verify_data($response->getHeader(), $response->getBody());
            }
        }catch (InvalidResponseException $e) {
            LogUtil::DEBUG($e->getMessage());
            return $e->getMessage();
        }
        curl_close($curl);
        return json_decode($response->getBody(),true);
    }


}