
/*
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

layui.define(function(e) {
    var a = layui.admin;
    layui.use(["admin", "carousel"], function() {
        var e = layui.$,
            a = (layui.admin, layui.carousel),
            l = layui.element,
            t = layui.device();
        e(".layadmin-carousel").each(function() {
            var l = e(this);
            a.render({
                elem: this,
                width: "100%",
                arrow: "none",
                interval: l.data("interval"),
                autoplay: l.data("autoplay") === !0,
                trigger: t.ios || t.android ? "click" : "hover",
                anim: l.data("anim")
            })
        }), l.render("progress")
    }), layui.use(["carousel", "echarts"], function() {
        var e = layui.$,
            a = (layui.carousel, layui.echarts),
            l = [],
            t = [{
                tooltip: {
                    trigger: "axis"
                },
                calculable: !0,
                legend: {
                    data: ["订单量", "交易额"]
                },
                xAxis: [{
                    type: "category",
                    data: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
                }],
                yAxis: [{
                    type: "value",
                    name: "订单量",
                    axisLabel: {
                        formatter: "{value}"
                    }
                }, {
                    type: "value",
                    name: "交易额",
                    axisLabel: {
                        formatter: "{value}"
                    }
                }],
                series: []
            }],
            i = e("#app-index-welcome-carousel").children("div"),
            n = function(d) {
                var orders = [], fees = [];
                l[d] = a.init(i[d], layui.echartsTheme), e.get('getOrderStat').done(function(res) {
                    if (res.code == 1) {
                        for (var i = 1; i <= 12; i++) {
                            orders.push(res.data.orders[i]);
                            fees.push(res.data.fees[i]);
                        }
                        t[d].series = [{
                            name: "订单量",
                            type: "line",
                            data: orders
                        }, {
                            name: "交易额",
                            type: "line",
                            yAxisIndex: 1,
                            data: fees,
                        }],
                            console.log(t[d])
                    }
                    l[d].setOption(t[d]), window.onresize = l[d].resize
                })
            };
        console.log(i), i[0] && n(0)
    }), e("welcome", {})
});