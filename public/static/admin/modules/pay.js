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

layui.define(["table", "form"],
    function(e) {
        var t = layui.$,
            i = layui.table,
            u = layui.util,
            n = layui.form;
        //方式
        i.render({
            elem: "#app-pay-code-list",
            url: "getCodeList",
            //自定义响应字段
            response: {
                statusCode: 1 //数据状态一切正常的状态码
            },
            cols: [
                [{
                    type: "checkbox",
                    fixed: "left"
                }, {
                    field: "id",
                    width: 80,
                    title: "ID",
                    sort: !0
                }, {
                    field: "name",
                    title: "产品名称"
                }, {
                    field: "code",
                    title: "通道代码"
                }, {
                    field: "remarks",
                    title: "备注"
                },{
                    field: "status",
                    title: "状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                }, {
                    title: "操作",
                    width: 200,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-pay-code"
                }]
            ],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-pay-code-list)",
                function(e) {
                    var d = e.data;
                    if ("del" === e.event) layer.confirm("确定删除此支付方式？",
                        function(d) {
                            t.ajax({
                                url: 'delCode?id='+ e.data.id,
                                method:'POST',
                                success:function (res) {
                                    if (res.code == 1){
                                        e.del()
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    layer.close(d); //关闭弹层
                                }
                            });
                        });
                    else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑支付方式",
                            content: "editCode.html?id="+ d.id,
                            maxmin: !0,
                            area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(d, f) {
                                var l = window["layui-layer-iframe" + d],
                                    o = "app-pay-code-submit",
                                    r = f.find("iframe").contents().find("#" + o);
                                l.layui.form.on("submit(" + o + ")",
                                    function(r) {
                                        var l = r.field;
                                        //提交修改
                                        t.post("editCode",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                e.update({
                                                    name: l.name,
                                                    code: l.code,
                                                    remarks: l.remarks,
                                                    status: l.status
                                                }),
                                                //渲染
                                                n.render(),
                                                layer.close(d);
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    r.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                }),
            //渠道
        i.render({
            elem: "#app-pay-channel-list",
            url: 'getChannelList',
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
                    width: 100,
                    title: "ID",
                    sort: !0
                },
                {
                    field: "name",
                    width: 150,
                    title: "渠道名称"
                },
                {
                    field: "action",
                    width: 150,
                    title: "控制类名称"
                },
                {
                    field: "remarks",
                    title: "备注",
                },
                {
                    field: "create_time",
                    title: "创建时间",
                    width: 200,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "status",
                    title: "状态",
                    width: 100,
                    templet: "#buttonTpl",
                    align: "center"
                },
                {
                    title: "操作",
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-pay-channel"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-pay-channel-list)",
                function(e) {
                var s = e;
                    if ("del" === e.event) layer.confirm("确定删除此支付渠道？",
                        function(d) {
                            t.ajax({
                                url: 'delChannel?id='+ e.data.id,
                                method:'POST',
                                success:function (res) {
                                    if (res.code == 1){
                                        e.del()
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    layer.close(d); //关闭弹层
                                }
                            });
                        });
                    else if ("account" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "渠道账户列表",
                            content: "account?cnl_id=" + e.data.id,
                            maxmin: !0,
                            area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(e, f) {},
                            success: function(e, t) {}
                        })
                    }
                    else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑渠道",
                            content: "editChannel?id=" + e.data.id,
                            maxmin: !0,
                            area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(e, f) {
                                var r = window["layui-layer-iframe" + e],
                                    l = "app-pay-channel-submit",
                                    o = f.find("iframe").contents().find("#" + l);
                                r.layui.form.on("submit(" + l + ")",
                                    function(r) {
                                        var l = r.field;
                                        //提交修改
                                        t.post("editChannel",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                s.update({
                                                    name: l.name,
                                                    daily: l.daily,
                                                    param: l.param,
                                                    rate: l.rate,
                                                    remarks: l.remarks,
                                                    status: l.status
                                                }),
                                                //渲染
                                                n.render(),
                                                layer.close(e);
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    o.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                }),

        //渠道
        i.render({
            elem: "#app-pay-account-list",
            url: 'getAccountList',
            //添加请求字段
            where: {
                cnl_id:  t("input[ name='cnl_id' ] ").val()
            },
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
                    width: 100,
                    title: "ID",
                    sort: !0
                },
                {
                    field: "name",
                    width: 150,
                    title: "渠道名称"
                },
                {
                    field: "rate",
                    title: "费率",
                    width: 100,
                    align: "center"
                },
                {
                    field: "single",
                    title: "单笔限额",
                    width: 100,
                    align: "center"
                },
                {
                    field: "daily",
                    title: "当日限额",
                    width: 110,
                    align: "center"
                },
                {
                    field: "remarks",
                    title: "备注",
                },
                {
                    field: "create_time",
                    title: "创建时间",
                    width: 200,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "status",
                    title: "状态",
                    width: 100,
                    templet: "#buttonTpl",
                    align: "center"
                },
                {
                    title: "操作",
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-pay-account"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-pay-account-list)",
                function(e) {
                    var s = e;
                    if ("del" === e.event) layer.confirm("确定删除此账户？",
                        function(d) {
                            t.ajax({
                                url: 'delAccount?id='+ e.data.id,
                                method:'POST',
                                success:function (res) {
                                    if (res.code == 1){
                                        e.del()
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    layer.close(d); //关闭弹层
                                }
                            });
                        });
                    else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑账户",
                            content: "editAccount?id=" + e.data.id,
                            maxmin: !0,
                            area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(e, f) {
                                var r = window["layui-layer-iframe" + e],
                                    l = "app-pay-account-submit",
                                    o = f.find("iframe").contents().find("#" + l);
                                r.layui.form.on("submit(" + l + ")",
                                    function(r) {
                                        var l = r.field;
                                        //提交修改
                                        t.post("editAccount",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                s.update({
                                                    name: l.name,
                                                    daily: l.daily,
                                                    param: l.param,
                                                    rate: l.rate,
                                                    remarks: l.remarks,
                                                    status: l.status
                                                }),
                                                    //渲染
                                                    n.render(),
                                                    layer.close(e);
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    o.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                }),
            //银行
        i.render({
            elem: "#app-pay-bank-list",
            url: 'getBankList',
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
                    width: 100,
                    title: "ID",
                    sort: !0
                },
                {
                    field: "name",
                    width: 150,
                    title: "渠道名称"
                },
                {
                    field: "remarks",
                    width: 200,
                    title: "备注",
                },
                {
                    field: "create_time",
                    width: 200,
                    title: "创建时间",
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "default",
                    title: "默认",
                    templet: "#buttonDef",
                    minWidth: 100,
                    align: "center"
                },
                {
                    field: "status",
                    title: "状态",
                    templet: "#buttonTpl",
                    minWidth: 100,
                    align: "center"
                },
                {
                    title: "操作",
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-pay-bank"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-pay-bank-list)",
                function(e) {
                var s = e;
                    if ("del" === e.event) layer.confirm("确定删除此银行？",
                        function(d) {
                            t.ajax({
                                url: 'delBank?id='+ e.data.id,
                                method:'POST',
                                success:function (res) {
                                    if (res.code == 1){
                                        e.del()
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    layer.close(d); //关闭弹层
                                }
                            });
                        });
                    else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑银行",
                            content: "editBank?id=" + e.data.id,
                            maxmin: !0,
                            area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(e, f) {
                                var r = window["layui-layer-iframe" + e],
                                    l = "app-pay-bank-submit",
                                    o = f.find("iframe").contents().find("#" + l);
                                r.layui.form.on("submit(" + l + ")",
                                    function(r) {
                                        var l = r.field;
                                        //提交修改
                                        t.post("editBank",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                s.update({
                                                    name: l.name,
                                                    daily: l.daily,
                                                    param: l.param,
                                                    rate: l.rate,
                                                    remarks: l.remarks,
                                                    status: l.status
                                                }),
                                                //渲染
                                                n.render(),
                                                layer.close(e);
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    o.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                });
            e("pay", {})
    });