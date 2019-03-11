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

namespace app\common\library;

/**
 * RSA算法类
 * 签名及密文编码：base64字符串/十六进制字符串/二进制字符串流
 * 填充方式: PKCS1Padding（加解密）/NOPadding（解密）
 *
 * Notice:Only accepts a single block. Block size is equal to the RSA key size!
 * 如密钥长度为1024 bit，则加密时数据需小于128字节，加上PKCS1Padding本身的11字节信息，所以明文需小于117字节
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 * @version: 1.0.0
 * @date: 2018/05/30
 */
class RsaUtils
{
    private $pubKey = null;
    private $priKey = null;

    /**
     * 构造函数
     * RsaUtils constructor.
     * @param string $public_key
     * @param string $private_key
     * @throws
     */
    public function __construct($public_key = '', $private_key = '')
    {
        if (!empty($public_key)) {
            $this->_setPublicKey($public_key);
        }
        if (!empty($private_key)) {
            $this->_setPrivateKey($private_key);
        }
    }


    /**
     * 检测填充类型
     * 加密只支持PKCS1_PADDING
     * 解密支持PKCS1_PADDING和NO_PADDING
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int 填充模式
     * @param string 加密en/解密de
     * @return bool
     */
    private function _checkPadding($padding, $type)
    {
        if ($type == 'en') {
            switch ($padding) {
                case OPENSSL_PKCS1_PADDING:
                    $ret = true;
                    break;
                default:
                    $ret = false;
            }
        } else {
            switch ($padding) {
                case OPENSSL_PKCS1_PADDING:
                case OPENSSL_NO_PADDING:
                    $ret = true;
                    break;
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    /**
     * 加密
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $data 加密数据
     * @param string $code 加密类型
     * @return string
     */
    private function _encode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $data = base64_encode('' . $data);
                break;
            case 'hex':
                $data = bin2hex($data);
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    /**
     * 解密
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $data   加密数据
     * @param string $code  加密类型
     * @return bool|string
     */
    private function _decode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $data = base64_decode($data);
                break;
            case 'hex':
                $data = $this->_hex2bin($data);
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    /**
     * 读取文件
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $file
     *
     * @return bool|string
     */
    private function _readFile($file)
    {
        if (!file_exists($file)){
            die('file not exists');
        }
        return file_get_contents($file);
    }

    /**
     * Hex2bin
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param bool $hex
     * @return bool|string
     */
    private function _hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }

    /**
     * 设置公钥
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $file
     *
     */
    private function _setPublicKey($file)
    {
        if (is_file($file)){
            $publicKeyString = $this->_readFile($file);
        }else{
            $publicKeyString = "-----BEGIN PUBLIC KEY-----".PHP_EOL
                . chunk_split($file,64,"\n")
                . "-----END PUBLIC KEY-----".PHP_EOL;
        }
        $this->pubKey = openssl_pkey_get_public($publicKeyString);
    }

    /**
     * 设置私钥
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $file
     *
     */
    private function _setPrivateKey($file)
    {

        if (is_file($file)){
            $privateKeyString = $this->_readFile($file);
        }else{
            $privateKeyString = "-----BEGIN RSA PRIVATE KEY-----".PHP_EOL
                . chunk_split($file,64,"\n")
                . "-----END RSA PRIVATE KEY-----".PHP_EOL;

        }
        $this->priKey = openssl_pkey_get_private($privateKeyString);

    }


    /**
     * 生成签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string 签名材料
     * @param string 签名编码（base64/hex/bin）
     * @return string 签名值
     */
    public function sign($data, $code = 'base64')
    {
        $ret = false;
        if ( $this->priKey && openssl_sign($data, $ret, $this->priKey)) {
            $ret = $this->_encode($ret, $code);
        }
        return $ret;
    }

    /**
     * 验证签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string 签名材料
     * @param string 签名值
     * @param string 签名编码（base64/hex/bin）
     * @return bool
     */
    public function verify($data, $sign, $code = 'base64')
    {
        $ret = false;
        $sign = $this->_decode($sign, $code);
        if ($sign !== false) {
            switch ( $this->pubKey && openssl_verify($data, $sign, $this->pubKey)) {
                case 1:
                    $ret = true;
                    break;
                case 0:
                case -1:
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    /**
     * 加密
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string 明文
     * @param string 密文编码（base64/hex/bin）
     * @param int 填充方式（貌似php有bug，所以目前仅支持OPENSSL_PKCS1_PADDING）
     * @return string 密文
     */
    public function encrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING)
    {
        $ret = false;
        if (!$this->_checkPadding($padding, 'en'))
        if (openssl_public_encrypt( $this->pubKey && $data, $result, $this->pubKey, $padding)) {
            $ret = $this->_encode($result, $code);
        }
        return $ret;
    }

    /**
     * 解密
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string 密文
     * @param string 密文编码（base64/hex/bin）
     * @param int 填充方式（OPENSSL_PKCS1_PADDING / OPENSSL_NO_PADDING）
     * @param bool 是否翻转明文（When passing Microsoft CryptoAPI-generated RSA cyphertext, revert the bytes in the block）
     * @return string 明文
     */
    public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
    {
        $ret = false;
        $data = $this->_decode($data, $code);
        if (!$this->_checkPadding($padding, 'de'))
        if ($data !== false) {
            if (openssl_private_decrypt( $this->priKey && $data, $result, $this->priKey, $padding)) {
                $ret = $rev ? rtrim(strrev($result), "\0") : '' . $result;
            }
        }
        return $ret;
    }

    /**
     * 生成证书
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $filename
     *
     */
    public function exportOpenSSLFile($filename = ''){
        $config = array(
            //依据自己的配置文件
            "config" => "F:\phpStudy\PHPTutorial\Apache\conf\openssl.cnf",
            "digest_alg" => "sha512",
            "private_key_bits" => 2048, //字节数    512 1024  2048   4096 等
            "private_key_type" => OPENSSL_KEYTYPE_RSA, //加密类型
        );
        //创建公钥和私钥   返回资源
        $res = openssl_pkey_new($config);
        //从得到的资源中获取私钥，把私钥赋给$privKey
        openssl_pkey_export($res, $private_key, null, $config);
        //从得到的资源中获取公钥，返回公钥$pubKey
        $pubKey = openssl_pkey_get_details($res);
        $public_key = $pubKey["key"];
        //输出文件
        $filename =  !empty($filename) ? $filename : date('Ymd');
        if (!is_dir(CRET_PATH . "/export/"))  mkdir(CRET_PATH . "/export/", 0777);
        file_put_contents(CRET_PATH . "/export/". $filename ."_public.pem", $public_key);
        file_put_contents(CRET_PATH . "/export/". $filename ."_private.pem", $private_key);
    }
}