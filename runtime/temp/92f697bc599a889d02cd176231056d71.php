<?php /*a:2:{s:73:"F:\phpStudy\PHPTutorial\WWW\cmpay\application\index\view\index\index.html";i:1552315554;s:74:"F:\phpStudy\PHPTutorial\WWW\cmpay\application\index\view\index-layout.html";i:1552315554;}*/ ?>
<!DOCTYPE html>
<!--
  ~ +----------------------------------------------------------------------
  ~  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
  ~ +----------------------------------------------------------------------
  ~  | Copyright (c) 2019 知行信息科技. All rights reserved.
  ~ +----------------------------------------------------------------------
  ~  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
  ~ +----------------------------------------------------------------------
  ~  | Author: Brian Waring <BrianWaring98@gmail.com>
  ~ +----------------------------------------------------------------------
  -->

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=yes">
    <link href="/favicon.ico" rel="shortcut icon" />
    <title><?php echo htmlentities((isset($site['seo_title']) && ($site['seo_title'] !== '')?$site['seo_title']:"聚合支付")); ?> - 支付技术服务商，让支付简单、专业、快捷！</title>
    <link rel="stylesheet" href="/static/index/css/qietu.css">
    <link rel="stylesheet" href="/static/index/css/iconfont.css">
    <link rel="stylesheet" href="/static/index/css/animate.min.css">
    
    <!-- css for this page -->
    <!-- End css for this page -->
    
    <link rel="stylesheet" href="/static/index/css/style.css">
    <link rel="stylesheet" href="/static/index/css/responsive.css">

</head>
<body>
<!--头部-->
<div class="header bj-3d7bf8">
    <div class="wrapper">
        <div class="logo">
            <a href="/">
                <img src="/static/common/logo.png" />
            </a>
        </div>
        <div class="nav-wrap">
            <div class="nav">
                <ul>
                    <li class="home_index">
                        <strong>
                            <a href="/">
                                首页
                            </a>
                        </strong>
                    </li>
                    <li>
                        <strong>
                            <a href="#">
                                产品中心
                                <i class="iconfont icon-jiantou8">
                                </i>
                            </a>
                        </strong>
                        <dl>
                            <dd>
                                <a href="<?php echo url('index/Index/products'); ?>#pro1">
                                    聚合收款
                                </a>
                            </dd>
                            <dd>
                                <a href="<?php echo url('index/Index/products'); ?>#pro4">
                                    二维码支付
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <strong>
                            <a href="#">
                                开发者中心
                                <i class="iconfont icon-jiantou8">
                                </i>
                            </a>
                        </strong>
                        <dl>
                            <dd>
                                <a href="<?php echo url('index/Index/demo'); ?>">
                                    DEMO体验
                                </a>
                            </dd>
                            <dd>
                                <a href="<?php echo url('index/Index/introduce'); ?>">
                                    接入指南
                                </a>
                            </dd>
                            <dd>
                                <a href="<?php echo url('index/Index/document'); ?>">
                                    开发文档
                                </a>
                            </dd>
                            <dd>
                                <a href="<?php echo url('index/Index/sdk'); ?>">
                                    SDK下载
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <strong>
                            <a href="<?php echo url('index/Index/about'); ?>">
                                关于我们
                            </a>
                        </strong>
                    </li>
                </ul>
            </div>
            <div class="btns">
                <ul>
                    <?php if(is_login()): ?>
                    <li class="">
                        <a href="<?php echo url('index/User/index'); ?>">
                            用户中心
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo url('index/Auth/logout'); ?>">
                            退出
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="">
                        <?php if(app('request')->action() == 'login'): ?>
                        <a href="<?php echo url('index/Auth/register'); ?>">
                            注册
                        </a>
                        <?php else: ?>
                        <a href="<?php echo url('index/Auth/login'); ?>">
                            登录
                        </a>
                        <?php endif; ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="gh">
            <a href="#">
            </a>
        </div>
    </div>
</div>
<!--内容-->
<div class="hbanner">    <div class="wrapper">        <div class="wow fadeInLeft hbanner-txt g-txt">            <h2>                CMPAY支付/Payment                <br/>                <font>                    让支付接口对接前所未有的简单                </font>            </h2>            <p>                无需后端开发，一个SDK即可接入一套完整的支付系统，高速集成主流支付接口，以更稳定的接口、更快的速度直达支付。            </p>            <!--<a href="#"class="hbanner-btn g-home-btn">立即注册</a>-->        </div>        <div class="wow fadeInRight hbanner-img g-img">            <img src="/static/index/picture/banner-1.png" />        </div>    </div></div><div class="hnav">    <div class="wrapper">        <ul>            <li>                <div class="img">                    <img src="/static/index/picture/img_01.png" alt="" />                </div>                <div class="txt">                    <h2>                        快速高效                    </h2>                    <p>                        10分钟超快速响应，1V1专业客                        <br/>                        服服务，7*24小时技术支持。                    </p>                </div>            </li>            <li>                <div class="img">                    <img src="/static/index/picture/img_02.png" alt="" />                </div>                <div class="txt">                    <h2>                        稳定持久                    </h2>                    <p>                        多机房异地容灾系统，服务器可用                        <br/>                        性99.9%，专业运维团队值守。                    </p>                </div>            </li>            <li>                <div class="img">                    <img src="/static/index/picture/img_03.png" alt="" />                </div>                <div class="txt">                    <h2>                        快速高效                    </h2>                    <p>                        金融级安全防护标准，强有力抵                        <br/>                        御外部入侵，支持高并发交易。                    </p>                </div>            </li>        </ul>    </div></div><div class="hhelp">    <div class="wrapper">        <div class="hhelp-hd g-hd">            <h2 class="wow fadeInUp">                最专业的支付帮手            </h2>            <p class="wow fadeInUp" data-wow-delay='.5s'>                -Payment Assistant-            </p>        </div>        <div class="wow fadeInUp hhelp-bd" data-wow-delay=".5s">            <ul class="clear">                <li class="s">                    <div class="img">                        <img src="/static/index/picture/img_04.png" />                    </div>                    <div class="txt">                        <h2>                            手机APP支付                        </h2>                        <h4>                            APP内实现收款                        </h4>                    </div>                </li>                <li>                    <div class="img">                        <img src="/static/index/picture/img_05.png" />                    </div>                    <div class="txt">                        <h2>                            手机网页支付                        </h2>                        <h4>                            手机浏览器内实现收款                        </h4>                    </div>                </li>                <li>                    <div class="img">                        <img src="/static/index/picture/img_06.png" />                    </div>                    <div class="txt">                        <h2>                            公众号支付                        </h2>                        <h4>                            微信浏览器内实现收款                        </h4>                    </div>                </li>                <li class="s">                    <div class="img">                        <img src="/static/index/picture/img_07.png" />                    </div>                    <div class="txt">                        <h2>                            PC网页支付                        </h2>                        <h4>                            PC浏览器内实现收款                        </h4>                    </div>                </li>                <li class="s">                    <div class="img">                        <img src="/static/index/picture/img_08.png" />                    </div>                    <div class="txt">                        <h2>                            线下扫码支付                        </h2>                        <h4>                            扫描二维码实现收款                        </h4>                    </div>                </li>            </ul>        </div>    </div></div><div class="hpro">    <div class="wrapper">        <div class="hpro-hd g-hd">            <h2 class="wow fadeInUp">                产品与服务            </h2>            <p class="wow fadeInUp" data-wow-delay='.5s'>                -PRODUCT SERVICE-            </p>        </div>        <div class="hpro-bd">            <ul>                <li>                    <div class="img">                        <img src="/static/index/picture/img_09.png" />                    </div>                    <div class="txt">                        <h2>                            财务对账                        </h2>                        <h4>                            相近的订单统计                            <br/>                            企业收支一目了然                        </h4>                    </div>                </li>                <li class="line-ffb573">                    <div class="img">                        <img src="/static/index/picture/img_10.png" />                    </div>                    <div class="txt">                        <h2>                            商户系统                        </h2>                        <h4>                            添加商户账号                            <br/>                            为交易实现分账功能                        </h4>                    </div>                </li>                <li class="line-47e7c4">                    <div class="img">                        <img src="/static/index/picture/img_11.png" />                    </div>                    <div class="txt">                        <h2>                            接口申请                        </h2>                        <h4>                            全支付场景覆盖                            <br/>                            主流支付接口支持                        </h4>                    </div>                </li>                <li class="line-6e94ff">                    <div class="img">                        <img src="/static/index/picture/img_12.png" />                    </div>                    <div class="txt">                        <h2>                            二维码支付                        </h2>                        <h4>                            专业收款工具                            <br/>                            线下商户经营必备                        </h4>                    </div>                </li>            </ul>        </div>    </div></div><div class="pay">    <div class="wrapper">        <div class="pay-hd g-hd">            <h2 class="wow fadeInUp">                对接多家支付接口            </h2>            <p class="wow fadeInUp" data-wow-delay='.25s'>                -Multiple payments-            </p>            <p class="wow fadeInUp pay-txt" data-wow-delay='.5s'>                对接行业内最优质的多家支付接口。全力保障业务流畅。                <br/>                让支付接口开发更加简单方便。            </p>        </div>        <div class="pay-bd">            <ul>                <li class="wow fadeInLeft">                    <div class="img">                        <img src="/static/index/picture/img_13.png" />                    </div>                    <div class="txt">                        <p>                            支付宝支付                        </p>                    </div>                </li>                <li class="wow fadeInLeft">                    <div class="img">                        <img src="/static/index/picture/img_14.png" />                    </div>                    <div class="txt">                        <p>                            微信支付                        </p>                    </div>                </li>                <li class="wow fadeInRight">                    <div class="img">                        <img src="/static/index/picture/img_15.png" />                    </div>                    <div class="txt">                        <p>                            QQ钱包                        </p>                    </div>                </li>                <li class="wow fadeInRight">                    <div class="img">                        <img src="/static/index/picture/img_16.png" />                    </div>                    <div class="txt">                        <p>                            银联支付                        </p>                    </div>                </li>                <li class="wow fadeInLeft">                    <div class="img">                        <img src="/static/index/picture/img_17.png" />                    </div>                    <div class="txt">                        <p>                            京东支付                        </p>                    </div>                </li>                <li class="wow fadeInLeft">                    <div class="img">                        <img src="/static/index/picture/img_18.png" />                    </div>                    <div class="txt">                        <p>                            百度钱包                        </p>                    </div>                </li>                <li class="wow fadeInRight">                    <div class="img">                        <img src="/static/index/picture/img_19.png" />                    </div>                    <div class="txt">                        <p>                            Apple Pay                        </p>                    </div>                </li>                <li class="wow fadeInRight">                    <div class="img">                        <img src="/static/index/picture/img_20.png" />                    </div>                    <div class="txt">                        <p>                            蚂蚁花呗                        </p>                    </div>                </li>            </ul>            <!--<a class="pay-btn g-btn"data-wow-delay=".2s"href="#">我要接入</a>-->        </div>    </div></div><div class="hview even">    <div class="wrapper">        <div class="wow fadeInRight hview-img img">            <img src="/static/index/picture/img_21.png" />        </div>        <div class="wow fadeInLeft hview-txt txt">            <h2 class=" line-27">                定制化支付解决方案            </h2>            <ul>                <li>                    <i class="iconfont icon-xuanzhongzhuangtai">                    </i>                    支持不同业务场景的交易方式。                </li>                <li>                    <i class="iconfont icon-xuanzhongzhuangtai">                    </i>                    免费在线一对一分析支付场景、梳理企业收款需                    <br/>                    求，提出接入建议、定制支付解决方案。                </li>            </ul>        </div>    </div></div><div class="hview odd">    <div class="wrapper">        <div class="wow fadeInLeft hview-img img">            <img src="/static/index/picture/img_22.png" />        </div>        <div class="wow fadeInRight hview-txt txt">            <h2 class="line-27 line-a996fa">                专业的全流程服务            </h2>            <ul>                <li>                    <i class="iconfont icon-xuanzhongzhuangtai">                    </i>                    支持个性化定制和私有化部署。                </li>                <li>                    <i class="iconfont icon-xuanzhongzhuangtai">                    </i>                    全程跟进定制化业务需求，可部署企业本地服务器，数据安全可控。                </li>                <li>                    <i class="iconfont icon-xuanzhongzhuangtai">                    </i>                    客户成功团队从接口联调、测试上线到后期系统运维、管理平台                    <br/>                    使用等各方向全面提供7*10小时服务。                </li>            </ul>            <!--<a href=""class="g-btn btn-jr">现在加入</a>-->        </div>    </div></div><div class="hpartner">    <div class="wrapper">        <div class="hpart-hd g-hd">            <h2 class="wow fadeInUp">                我们的伙伴            </h2>            <p class="wow fadeInUp" data-wow-delay=".4s">                -Our partners-            </p>        </div>        <div class="hpart-bd">            <div class="hpart-prev">                <i class="iconfont icon-zuo">                </i>            </div>            <div class="hpart-list">                <div class="list-wrper">                    <ul class="hpart-list-wrapper">                        <li class="hpart-list-item">                            <dl>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_1.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_2.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_3.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_4.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_5.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_6.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_7.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_8.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_9.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_10.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_11.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_12.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_13.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_14.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_15.png" />                                    </a>                                </dd>                            </dl>                        </li>                        <li class="hpart-list-item">                            <dl>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_1.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_2.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_3.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_4.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_5.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_6.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_7.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_8.png" />                                    </a>                                </dd>                                <dd>                                    <a href="#">                                        <img src="/static/index/picture/slider_9.png" />                                    </a>                                </dd>                            </dl>                        </li>                    </ul>                </div>            </div>            <div class="hpart-next">                <i class="iconfont icon-you">                </i>            </div>        </div>    </div></div><div class="hstart">    <div class="wrapper">        <div class="wow fadeInLeft hstart-txt">            <h2>                立即开启支付新时代！            </h2>            <p>                CMPAY支付，支付技术服务商，让支付简单、专业、快捷！            </p>        </div>        <div class="wow fadeInRight hstart-btn ">            <a class="g-btn" href="<?php echo url('index/Login/register'); ?>">立即开启</a>        </div>    </div></div>
<!--底部-->
<div class="footer">
    <div class="wrapper">
        <dl class="s">
            <dt>
                联系我们
            </dt>
            <dd>
                <a href="">
                联系方式
                <p> QQ:702154416</p>
                <p> Email:me@iredcap.cn</p>
                </a>
            </dd>
            <dd>
                <img src="/static/common/logo.png" style="width: 120px" />
            </dd>
        </dl>
        <dl>
            <dt>
                产品项目
            </dt>
            <dd>
                <a href="<?php echo url('index/index/products'); ?>#pro1">
                    聚合收款
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/products'); ?>#pro2">
                    商户系统
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/products'); ?>#pro3">
                    代付系统
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/products'); ?>#pro4">
                    二维码支付
                </a>
            </dd>
        </dl>
        <dl>
            <dt>
                关于公司
            </dt>
            <dd>
                <a href="<?php echo url('index/index/about'); ?>">
                    关于我们
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/partner'); ?>">
                    接口合作
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/partner'); ?>">
                    流量合作
                </a>
            </dd>
        </dl>
        <dl>
            <dt>
                开发者
            </dt>
            <dd>
                <a href="<?php echo url('index/index/demo'); ?>">
                    DEMO体验
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/introduce'); ?>">
                    接入指南
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/document'); ?>">
                    API开发文档
                </a>
            </dd>
            <dd>
                <a href="<?php echo url('index/index/sdk'); ?>">
                    SDK下载
                </a>
            </dd>
        </dl>
        <dl class="s">
            <dt>
                扫一扫
            </dt>
            <dd>
                <img src="/static/common/qr-pub.jpg" style="width: 109px;" />
            </dd>
        </dl>
    </div>
</div>
<div class="copyright">
    <div class="wrapper">
        <?php echo htmlentities((isset($site['app_copyright']) && ($site['app_copyright'] !== '')?$site['app_copyright']:"© 2018 CMPAY. ")); ?> <a href="https://www.iredcap.cn" style="color: #fff;" target="_blank">小红帽科技</a> · <?php echo htmlentities((isset($site['seo_title']) && ($site['seo_title'] !== '')?$site['seo_title']:"草帽聚合支付")); ?> · 桂ICP备180004251号
    </div>
</div>
<div class="sidebar">
    <ul>
        <!--<li><a href="#"><i class="iconfont icon-liaotian"></i></a></li>-->
        <li class="sidebar-scroll">
            <a href="#">
                <i class="iconfont icon-shang">
                </i>
            </a>
        </li>
    </ul>
</div>

<script type="text/javascript" src="/static/index/js/jquery-1.7.2.min.js">
</script>
<script type="text/javascript" src="/static/index/js/jquery.glide.js">
</script>
<script type="text/javascript" src="/static/index/js/wow.min.js">
</script>
<script type="text/javascript" src="/static/index/js/script.js">
</script>
<script src="/static/common/js/copyright.js"></script>
<!--Layui-->
<script type="text/javascript" src="/static/layui/layui.js">
</script>

<script type="text/javascript">
    $(function() {
        //$(".home_index").addClass("on");
        $('.sidebar-scroll').click(function() {
            $('html ,body').animate({
                scrollTop: 0
            }, 1000);
            return false
        });
        var glide = $('.list-wrper').glide({
            arrows: false
        }).data('api_glide');
        $(".hpart-prev").click(function() {
            glide.prev()
        });
        $(".hpart-next").click(function() {
            glide.next()
        })
    })
</script>

</body>

</html>