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
    function(t) {
        var q = layui.$,
            i = layui.table,
            u = layui.util,
            n = layui.form;
        i.render({
            elem: "#app-article-list",
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
                    field: "id",
                    width: 100,
                    title: "文章ID",
                    sort: !0
                },
                {
                    field: "title",
                    title: "文章标题"
                },
                {
                    field: "author",
                    title: "作者"
                },
                {
                    field: "describe",
                    title: "描述"
                },
                {
                    field: "content",
                    title: "描述"
                },
                {
                    field: "create_time",
                    title: "发布时间",
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "update_time",
                    title: "更新时间",
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.update_time*1000); }
                },
                {
                    field: "status",
                    title: "发布状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                },
                {
                    title: "操作",
                    minWidth: 150,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-content-list"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-article-list)",
                function(t) {
                var e = t.data;
                "del" === t.event ? layer.confirm("确定删除此文章？",
                    function(d) {
                        q.ajax({
                            url: 'delArticle?id='+ e.id,
                            method:'POST',
                            success:function (res) {
                                if (res.code == 1){
                                    t.del()
                                }
                                layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                layer.close(d); //关闭弹层
                            }
                        });
                    }) : "edit" === t.event && layer.open({
                    type: 2,
                    title: "编辑文章",
                    content: "edit.html?id=" + e.id,
                    maxmin: !0,
                    maxmin: !0,                             area: ['80%','60%'],
                    btn: ["确定", "取消"],
                    yes: function(e, i) {
                        var l = window["layui-layer-iframe" + e],
                            a = i.find("iframe").contents().find("#app-article-form-edit");
                        l.layui.form.on("submit(app-article-form-edit)",
                            function(i) {
                                var l = i.field;
                                layui.$.post("edit",l,function (res) {
                                    if (res.code == 1){
                                        //更新数据表
                                        t.update({
                                            label: l.label,
                                            title: l.title,
                                            author: l.author,
                                            status: l.status
                                        }),
                                            n.render(),
                                                layer.close(e)
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                });
                            }),
                            a.trigger("click")
                    }
                })
            });
        i.render({
            elem: "#app-notice-list",
            url: "getNoticeList",
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
                    title: "文章ID",
                    sort: !0
                },
                {
                    field: "title",
                    title: "文章标题"
                },
                {
                    field: "author",
                    title: "作者"
                },
                {
                    field: "content",
                    title: "内容"
                },
                {
                    field: "create_time",
                    title: "发布时间",
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    field: "update_time",
                    title: "更新时间",
                    sort: !0,
                    templet: function(d) {return u.toDateString(d.update_time*1000); }
                },
                {
                    field: "status",
                    title: "发布状态",
                    templet: "#buttonTpl",
                    minWidth: 80,
                    align: "center"
                },
                {
                    title: "操作",
                    minWidth: 150,
                    align: "center",
                    fixed: "right",
                    toolbar: "#table-content-list"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-notice-list)",
                function(t) {
                    var e = t.data;
                    "del" === t.event ? layer.confirm("确定删除此通知？",
                        function(d) {
                            q.ajax({
                                url: 'delNotice?id='+ e.id,
                                method:'POST',
                                success:function (res) {
                                    if (res.code == 1){
                                        t.del()
                                    }
                                    layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    layer.close(d); //关闭弹层
                                }
                            });
                        }): "edit" === t.event && layer.open({
                        type: 2,
                        title: "编辑通知",
                        content: "editNotice.html?id=" + e.id,
                        maxmin: !0,
                        maxmin: !0,                             area: ['80%','60%'],
                        btn: ["确定", "取消"],
                        yes: function(e, i) {
                            var l = window["layui-layer-iframe" + e],
                                a = i.find("iframe").contents().find("#app-notice-form-edit");
                            l.layui.form.on("submit(app-notice-form-edit)",
                                function(i) {
                                    var l = i.field;
                                    layui.$.post("editNotice",l,function (res) {
                                        if (res.code == 1){
                                            //更新数据表
                                            t.update({
                                                label: l.label,
                                                title: l.title,
                                                author: l.author,
                                                status: l.status
                                            }),
                                                n.render(),
                                                layer.close(e)
                                        }
                                        layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                    });
                                }),
                                a.trigger("click")
                        }
                    })
                });
            t("article", {})
    });