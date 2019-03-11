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

return [
  'alipay'    => [
      'app_id' => '2018030402313504',
      'notify_url' => '',
      'return_url' => '',
      //商户私钥, 请把生成的私钥文件中字符串拷贝在此
      'private_key'    =>'MIIEowIBAAKCAQEAp7f6gGT8GXS2glJ48hOdj6FgRKTTFzqho9IMxIlfcIpieQ4NO3jyl36tRxSQUtt8pUp85Z9v6/fqI1bt7dq4NPdero14dXgzL3XZt18QPAVntosqEjyI0QgiZZg3oXnh4fEgDwln+NFs8T/Ni/BDHMwzpuRpnNdglr6167kRj1frxjLWGUAgo3gmKQgZiVOmeFGNWEJ3vlB6nhfIrQOi2p+nPbPLOQKyUiJeGKh1aR3qGtFrUUpIYasizx3Kg/xdxMISzMSVOqDIxeVCB9FWSJXr9Ws6uErmpBI7lXmaAs3144Ie5rqRbKslMriJ3ovdaLlmTlXDxjbTR/AGsKu3XQIDAQABAoIBABX2a6FAmBqlQ/kQ37Gji/BxC3AxvUq/bMdNDEr4Sj0sgfSkOGtfTTU1a29xa+zNvSbP+EcBd+CImGqESafqClE1S3rEH9ASK3G9lwMCOdgCRTCMTLgSoT/uNsLjCfXlRgUWVEJj0u+sTP3SgxIeJkuxGdpy8rmNIqLa2mvB0mDYxiytOVyMO+J8amaTbz/MllRxa+iAxIbd/M12rrV3vvEYUgitvK4uXER62MZMyIvOW6Cf+CLfOq3Tsp+M1Jve4ox/xJOrg1815e9//+7hHcujjCo5XiG+u1rVyH+Tr/Qs6Rdk+CVgBiZ/YqWMSdFUBkUIYCKazzeVkzCkx0eJYIECgYEA3bIM0H7kCwfAHiWm5EGXEW8qoSTqc8bZMG5S6D2BuMTTVixRcfDTJlt2daKfxLRsU1ijrG6EVKaLblrBOFVJb1WYrgxgKkoUHIUqNwGMnTTe3dj8w2uA5/IUYcqmzwO5Rb49mc/1ATzzMqn2kUck5Vts9i8DpJUe0PLYJ7VkT5ECgYEAwavDC5NkPrE/QOYmvy1Aqj5vhKIr5W6IEGSGDMIfZ2e7o08URfRkc5jprZozcOl3MuseCE1I6ysIyDlvHtbV0eAl128xUWI5HXIC8zGrYJQ95Fsl2Xd6yymEC6CUKgnae2WyyOls3QM56XAmZbh1W+QN8Hsb5X0yLTii8LDsXQ0CgYB6AzVERqHxZCmbLfPFKkgfY0Rd/fg/EhCUtBNTGA7eBw2dHrUQdY9wS+RNZ9xwoTABSwaBry2LfUG90ZsICwBokv59w/flLnIVJEEQlvyxxNhn1rV+RBtlDHmlPKhDxPPh64rxrV9VeBsNJje6yyIGTSQR9dwWZ6/XJeBLMmzr0QKBgQC0uXeVAcF1zyjbgul9VNkXBJREDKExw+cshOGiXjO35tDuIAknDlv+kx7cZRzDrNkSptyrmpMFAG99iDrtaES3SJeHZbd73lC17YJbNmpaAXuP8I5tVFU96EvUHdClOfSrWcdwPILd6vjLoV/zZCH/0dxAIGFz0VRVZpiGSlMGsQKBgET9vKqOSvI88W8/Ve3YGOoXQ5qjrm5JqbL/J8f4GiOoDrBfaYij2Yg2WZlaPWi5t1KXh8IsN/Yn6NenY8CiI5hQZi/0CQM5ICEZmCnX3U1QdpffGzAbd/tb4ldmA3ez3cYNPzIP3q1bUQ3ybwO2eD8N978mHXBokoO0AHh01thZ',
      //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
      'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAix0lmphMY4htd8sw6kLMBGyju6p2y4pQtmiUpk7KxIV2NaUj0Zve2WJvPDptbKB0Lmn3EksPVG8VCrlh97shKjerm0gW314YN1DY/7RFPqxeeYNIFaMiGgf1ecMZUAOwO/v8NKn2nKH5hA0eMFxXNTtAXfSY/UBBnMFWOd765uQsXNn6r0PjhIpC2T9Hk+KfVm2eQ3QqY82/s0SaeebN/xjbkTsAc6yKGPCJxbe2vyE5coQ8iCj4pVvlFX6+SO+lEFvB56r8H+dQlDixPGgEGz+PZkUny7SZjFBZm5amH6XEl40ac9iWuuaW2C28FMoHX6XjJgu95aZMeVa5ZCrqmQIDAQAB',
      'log' => [ // optional
          'file' => RUNTIME_PATH . './logs/alipay.log',
          'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
          'type' => 'daily', // optional, 可选 daily.
          'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
      ],
      'http' => [ // optional
          'timeout' => 5.0,
          'connect_timeout' => 5.0,
          // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
      ],
      // 'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
  ]
];
