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
            elem: "#app-admin-user-manage",
            url: "userList",
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
                    field: "username",
                    title: "登录名"
                }, {
                    field: "nickname",
                    title: "昵称"
                },{
                    field: "email",
                    title: "邮箱"
                }, {
                    field: "create_time",
                    title: "加入时间",
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.jointime*1000); }
                }, {
                    field: "status",
                    title: "审核状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                }, {
                    title: "操作",
                    width: 200,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-useradmin-admin"
                }]
            ],
            text: "对不起，加载出现异常！"
        }),
        i.on("tool(app-admin-user-manage)",
            function(e) {
                var d = e.data;
                if ("del" === e.event) layer.prompt({
                        formType: 1,
                        title: "敏感操作，请验证口令"
                    },
                    function(d, i) {
                        layer.close(i),
                            layer.confirm("确定删除此管理员？",
                                function(d) {
                                    console.log(e),
                                        t.ajax({
                                            url: 'userDel?id='+ e.data.id,
                                            method:'POST',
                                            success:function (res) {
                                                if (res.code == 200){
                                                    e.del()
                                                }
                                                layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                                layer.close(d); //关闭弹层
                                            }
                                        });
                                })
                    });
                else if ("auth" === e.event) {
                    t(e.tr);
                    layer.open({
                        type: 2,
                        title: "管理授权",
                        content: "userAuth.html?id="+ d.id,
                        maxmin: !0,                             area: ['80%','60%'],
                        btn: ["确定", "取消"],
                        yes: function(f, t) {
                            var l = window["layui-layer-iframe" + f],
                                r = "app-admin-user-auth-submit",
                                n = t.find("iframe").contents().find("#" + r);
                            l.layui.form.on("submit("+ r +")",
                                function(t) {
                                    var l = t.field;
                                    layui.$.post("userAuth",l,function (res) {
                                        if (res.code == 200){
                                            i.render(),
                                                layer.close(f)
                                        }
                                        layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                    });
                                }),
                                n.trigger("click")
                        },
                        success: function(e, t) {}
                    })
                }
                else  if ("edit" === e.event) {
                    t(e.tr);
                    layer.open({
                        type: 2,
                        title: "编辑管理员",
                        content: "userEdit.html?id="+ d.id,
                        maxmin: !0,                             area: ['80%','60%'],
                        btn: ["确定", "取消"],
                        yes: function(f, t) {
                            var l = window["layui-layer-iframe" + f],
                                r = "app-admin-user-back-submit",
                                n = t.find("iframe").contents().find("#" + r);
                            l.layui.form.on("submit(" + r + ")",
                                function(d) {
                                    var l = d.field;
                                    layui.$.post("userEdit",l,function (res) {
                                        if (res.code == 200){
                                            //更新数据表
                                            e.update({
                                                username: l.username,
                                                nickname: l.nickname,
                                                email: l.email,
                                                status: l.status
                                            }),i.render(),
                                                layer.close(f)
                                        }
                                        layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                    });
                                }),
                                n.trigger("click")
                        },
                        success: function(e, t) {}
                    })
                }
            }),
        i.render({
            elem: "#app-admin-user-role",
            url: "groupList",
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
                    width: 100,
                    title: "角色名"
                }, {
                    field: "rules",
                    title: "拥有权限"
                }, {
                    field: "describe",
                    title: "具体描述"
                }, {
                    title: "操作",
                    width: 200,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-admin-user-role"
                }]
            ],
            text: "对不起，加载出现异常！"
        }),
        i.on("tool(app-admin-user-role)",
            function(e) {
                var d = e.data;
                if ("del" === e.event) layer.confirm("确定删除此角色？",
                    function(d) {
                        t.ajax({
                            url: 'groupDel?id='+ e.data.id,
                            method:'POST',
                            success:function (res) {
                                if (res.code == 200){
                                    e.del()
                                }
                                layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                layer.close(d); //关闭弹层
                            }
                        });
                    });
                else if ("auth" === e.event) {
                    t(e.tr);
                    layer.open({
                        type: 2,
                        title: "角色授权",
                        content: "menuAuth.html?id="+ d.id,
                        maxmin: !0,                             area: ['80%','60%'],
                        btn: ["确定", "取消"],
                        yes: function(f, t) {
                            var l = window["layui-layer-iframe" + f],
                                r = "app-admin-user-role-submit";
                            n = t.find("iframe").contents().find("#" + r);
                            l.layui.form.on("submit("+ r +")",
                                function(t) {
                                    var l = t.field;
                                    console.log(l)
                                    layui.$.post("menuAuth",l,function (res) {
                                        if (res.code == 200){
                                            i.render(),
                                                layer.close(f)
                                        }
                                        layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                    });
                                }),
                                n.trigger("click")
                        },
                        success: function(e, t) {}
                    })
                }
                else if ("edit" === e.event) {
                    t(e.tr);
                    layer.open({
                        type: 2,
                        title: "编辑角色",
                        content: "groupEdit.html?id="+ d.id,
                        maxmin: !0,                             area: ['80%','60%'],
                        btn: ["确定", "取消"],
                        yes: function(f, t) {
                            var l = window["layui-layer-iframe" + f],
                                r = "app-admin-user-role-submit";
                                n = t.find("iframe").contents().find("#" + r);
                            l.layui.form.on("submit("+ r +")",
                                function(t) {
                                    var l = t.field;
                                    layui.$.post("groupEdit",l,function (res) {
                                        if (res.code == 200){
                                            //更新数据表
                                            e.update({
                                                name: l.name,
                                                describe: l.describe
                                            }),i.render(),
                                                layer.close(f)
                                        }
                                        layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                    });
                                }),
                                n.trigger("click")
                        },
                        success: function(e, t) {}
                    })
                }
            }),
        e("useradmin", {})
    });
