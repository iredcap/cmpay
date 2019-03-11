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
        i.render({
            elem: "#app-user-manage",
            url: "getList",
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
                    field: "uid",
                    width: 100,
                    title: "商户UID",
                    sort: !0
                },
                {
                    field: "username",
                    width: 150,
                    title: "商户名"
                },
                {
                    field: "account",
                    width: 180,
                    title: "登录邮箱"
                },
                {
                    field: "phone",
                    width: 150,
                    title: "手机"
                },
                {
                    field: "qq",
                    width: 100,
                    title: "QQ"
                },
                {
                    field: "is_agent",
                    title: "是否代理",
                    templet: "#isAgent",
                    minWidth: 80,
                    align: "center"
                },
                {
                    field: "is_verify_phone",
                    title: "手机验证",
                    templet: "#isPhone",
                    minWidth: 80,
                    align: "center"
                },
                {
                    field: "is_verify",
                    title: "商户验证",
                    templet: "#isVerify",
                    minWidth: 80,
                    align: "center"
                },
                {
                    field: "status",
                    title: "商户状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                },
                {
                    field: "create_time",
                    title: "创建时间",
                    width: 180,
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "update_time",
                    title: "更新时间",
                    width: 180,
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.update_time*1000); }
                },
                {
                    title: "操作",
                    minWidth: 220,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-useradmin-webuser"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-user-manage)",
                function(e) {
                    if ("del" === e.event){
                        layer.prompt({
                                formType: 1,
                                title: "敏感操作，请验证口令"
                            },
                            function(d, i) {
                                layer.close(i),
                                    layer.confirm("真的删除此商户吗？",
                                    function(d) {
                                        t.ajax({
                                            url: 'del?uid='+ e.data.uid,
                                            method:'POST',
                                            success:function (res) {
                                                if (res.code == 1){
                                                    e.del()
                                                }
                                                layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                                layer.close(d); //关闭弹层
                                            }
                                        });
                                    })
                            });
                    } else if ("profit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "商户分润配置",
                            content: "profit.html?id=" + e.data.uid,
                            maxmin: !0,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(f, t) {
                                var l = window["layui-layer-iframe" + f],
                                    r = "app-user-profit-submit",
                                    n = t.find("iframe").contents().find("#" + r);
                                l.layui.form.on("submit(" + r + ")",
                                    function(t) {
                                        var l = t.field;
                                        console.log(l);
                                        layui.$.post("profit",l,function (res) {
                                            if (res.code == 1){
                                                i.render(),
                                                    layer.close(f)
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    n.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    } else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑用户",
                            content: "edit.html?id=" + e.data.uid,
                            maxmin: !0,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(f, t) {
                                var l = window["layui-layer-iframe" + f],
                                    r = "app-user-manage-submit",
                                    n = t.find("iframe").contents().find("#" + r);
                                l.layui.form.on("submit(" + r + ")",
                                    function(t) {
                                        var l = t.field;
                                        layui.$.post("edit",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                e.update({
                                                    username: l.username,
                                                    phone: l.phone,
                                                    qq: l.qq,
                                                    is_agent: l.is_agent,
                                                    status: l.status
                                                }),i.render(),
                                                    layer.close(f)
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    n.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                }),
        i.render({
            elem: "#app-user-auth-manage",
            url: "getAuthList",
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
                    field: "uid",
                    width: 100,
                    title: "商户UID",
                    sort: !0
                },
                {
                    field: "realname",
                    width: 150,
                    title: "姓名"
                },
                {
                    field: "sfznum",
                    width: 180,
                    title: "身份证号码"
                },
                {
                    field: "card",
                    title: "认证信息"
                },
                {
                    field: "status",
                    title: "认证状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                },
                {
                    field: "create_time",
                    title: "创建时间",
                    width: 180,
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "update_time",
                    title: "更新时间",
                    width: 180,
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.update_time*1000); }
                },
                {
                    title: "操作",
                    minWidth: 150,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-useradmin-webuser"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-user-auth-manage)",
                function(e) {
                    if ("del" === e.event) layer.prompt({
                            formType: 1,
                            title: "敏感操作，请验证口令"
                        },
                        function(t, i) {
                            layer.close(i),
                                layer.confirm("真的删除行么",
                                    function(t) {
                                        e.del(),
                                            layer.close(t)
                                    })
                        });
                    else if ("auth" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "审核用户认证信息",
                            content: "userAuthInfo.html?id=" + e.data.uid,
                            maxmin: !0,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(f, t) {
                                var l = window["layui-layer-iframe" + f],
                                    r = "app-user-auth-manage-submit",
                                    n = t.find("iframe").contents().find("#" + r);
                                l.layui.form.on("submit(" + r + ")",
                                    function(t) {
                                        var l = t.field;
                                        console.log(l)
                                        layui.$.post("userAuthInfo",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                e.update({
                                                    status: l.status
                                                }),i.render(),
                                                    layer.close(f)
                                            }
                                            layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                        });
                                    }),
                                    n.trigger("click")
                            },
                            success: function(e, t) {}
                        })
                    }
                }),
            e("user", {})
    });