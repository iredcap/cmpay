CMPAY_v1.1.0.190311_beta
===============

### 项目介绍
***

- `CMPAY聚合支付` 使用ThinkPHP框架开发，已接入微信、支付宝等主流支付渠道。

- 目前已经接入支付渠道：微信(扫码支付)、支付宝(电脑网站支付、手机网站支付)；

> [小红帽科技：https://www.iredcap.cn](https://www.iredcap.cn "小红帽科技")


> 运行环境要求PHP > 5.6以上(推荐7.0.*)。

使用系统前请：

>详细开发文档参考 [ThinkPHP5完全开发手册](http://www.kancloud.cn/manual/thinkphp5)

### 项目结构

```
project                             应用部署目录
│
├─application                       应用目录
│  ├─admin                          后台模块目录
│  ├─api                            API模块目录
│  ├─common                         公共模块目录
│  ├─index                          前端模块目录
│  ├─command.php                    命令行工具配置文件
│  ├─common.php                     应用公共（函数）文件
│  ├─tags.php                       应用行为扩展定义文件
│  └─...
├─config                            配置目录
│  ├─app.php                        应用配置文件
│  ├─database.php                   数据库配置文件
│  └─...
├─extend                            拓展类库
│  ├─tool                           工具类库
│  └─...
├─public                            Web 部署目录（对外访问目录）
│  ├─static                         静态资源存放目录(css,js,image)
│  ├─uploads                        系统文件上传存放目录
│  ├─index.php                      入口文件
│  ├─.htaccess                      用于 apache 的重写
│  └─...
├─resource                          资源存储目录
│  ├─cret                           证书文件
│  ├─crond                          Cron定时文件
│  ├─runtime                        系统运行runtime目录
│  ├─supervisord                    supervisord配置目录
│  └─...
├─route                             路由目录
│  └─route.php                      系统路由文件
├─runtime                           系统运行runtime目录
│  ├─log                            日志目录
│  └─...
├─build.php                         自动生成定义文件（参考）
├─composer.json                     composer 定义文件
├─LICENSE.txt                       授权说明文件
├─README.md                         README 文件
└─think                             命令行入口文件
```

## **在线演示**

后台 ：https://pay.iredcap.cn/admin  (nouser nouser) 

前台 ：https://pay.iredcap.cn (nouser@iredcap.cn nouser) 


## **运营截图**

![输入图片说明](https://t2.wodetu.cn/2018/11/13/b607987dd8dafcf770a1f566e6acafc7.png "商户平台")

![输入图片说明](https://t3.wodetu.cn/2018/11/13/5cca82d9e18380316fe1c3a98870bce9.png "商户平台")

![输入图片说明](https://t2.wodetu.cn/2018/11/07/89b6b3846f0d6bedfe6a56e18218c7d0.png "运营平台")

![输入图片说明](https://t3.wodetu.cn/2018/11/07/d5d6a3563b9f926cfaa751814a61f0ad.png "运营平台")

![输入图片说明](https://t4.wodetu.cn/2018/11/07/bd094bcaa6aed7d6054e706dfa2155b7.png "运营平台")

![输入图片说明](https://t3.wodetu.cn/2018/11/07/945e8aca35925fc2210831fcb0b7eca2.png "运营平台")

![输入图片说明](https://t2.wodetu.cn/2018/11/07/19ebbd6a728bae38e0cbb776b59a252b.png "运营平台")

### 版本更新

版本 |日期 |描述
------- | ------- | -------
V1.1.0 | 2018-3-11 | 提升ThinkPHP为5.1版本
V1.0.3 | 2018-12-22 | 更改支付接入方式，修复部分BUG
V1.0.2 | 2018-12-15 | 更新SDK接入，修复部分BUG
V1.0.1 | 2018-12-10 | 更新部分，修复BUG
V1.0.0 |2018-11-12 |更换前端页面，完善部分功能，商户端开发进行中...
V1.0.0 |2018-11-06 |修复部分Bug,完善后台权限管理，数据统计，其他
V1.0.0 |2018-10-23 |最初版本

接下来的开源版本开发计划：
```html
+ 持续完善V1.1.0版本；
+ 持续完善V1.0.0版本；
+ 增加渠道对接，API接口完善；
+ 完善支付核心，便于第三/四方渠道对接；
+ 持续增加其他附属功能；
+ 构思...

```
其他想法，大家请提交issues。

## **问题反馈**

在使用中有任何问题，请使用以下联系方式联系我们

QQ群: [973413297](https://dwz.cn/2fMw89Zm) ![加入QQ群](http://pub.idqqimg.com/wpa/images/group.png) (交流群 暗号：Cmpay)

Email: me@iredcap.cn

Github: https://github.com/iredcap/pay

Gitee: https://gitee.com/iredcap/pay

## **特别鸣谢**

感谢以下的项目,排名不分先后

ThinkPHP：http://www.thinkphp.cn

LayuiAdmin：https://www.layui.com/admin/ (商用请授权)

OneBase： https://www.onebase.org


## **版权信息**

`CMPAY聚合支付`遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2017-2019 by Iredcap (https://www.iredcap.cn)

All rights reserved。

## 若对您有帮助，支持下吧

![赞赏](https://sirhe.cn/wp-content/uploads/2018/06/打赏.jpg)

称呼 | 方式 | 金额
------- | ------- | -------
*二云 | 支付宝收款码 |￥200.00
*丽 | 支付宝收款码 |￥68.00