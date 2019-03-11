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

layui.define(["table", "form", "element"],
    function(e) {
        var t = layui.$,
            i = layui.table,
            u = layui.util;
        i.render({
            elem: "#app-order-list",
            url: 'getList',
            //自定义响应字段
            response: {
                statusName: 'code' //数据状态的字段名称
                ,statusCode: 1 //数据状态一切正常的状态码
                ,msgName: 'msg' //状态信息的字段名称
                ,dataName: 'data' //数据详情的字段名称
            },
            cols: [[{
                type: "checkbox",
                fixed: "left"
            },
                {
                    field: "id",
                    width: 120,
                    title: "ID",
                    sort: !0
                },
                {
                    field: "uid",
                    width: 100,
                    title: "商户编号"
                },
                {
                    field: "channel",
                    width: 100,
                    title: "交易方式"
                },
                {
                    field: "puid",
                    width: 100,
                    title: "代理编号"
                },
                {
                    field: "trade_no",
                    width: 200,
                    title: "平台订单号"
                },
                {
                    field: "out_trade_no",
                    width: 200,
                    title: "商户订单号"
                },
                {
                    field: "subject",
                    width: 150,
                    title: "交易项目"
                },
                {
                    field: "body",
                    width: 150,
                    title: "交易内容"
                },
                {
                    field: "currency",
                    width: 80,
                    title: "货币"
                },
                {
                    field: "amount",
                    width: 100,
                    title: "交易金额",
                    style:"color:red"
                },
                {
                    field: "income",
                    width: 100,
                    title: "到账金额",
                    style: "color:green"
                },
                {
                    field: "user_in",
                    width: 100,
                    title: "商户收入",
                    style: "color:grey"
                },
                {
                    field: "agent_in",
                    width: 100,
                    title: "代理收入",
                    style: "color:orange"
                },
                {
                    field: "platform_in",
                    width: 100,
                    title: "平台收入",
                    style: "color:red"
                },
                {
                    field: "create_time",
                    width: 180,
                    title: "创建时间",
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "update_time",
                    width: 180,
                    title: "更新时间",
                    templet: function(d) {return u.toDateString(d.update_time*1000); }
                },
                {
                    field: "status",
                    title: "订单状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                },
                {
                    title: "操作",
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-system-order"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-order-list)",
                function(e) {
                    e.data;
                    if ("details" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "交易详情",
                            content: "details.html?id=" + e.data.id,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(e, t) {},
                            success: function(e, t) {}
                        })
                    }
                }),
            e("orders", {})
    });