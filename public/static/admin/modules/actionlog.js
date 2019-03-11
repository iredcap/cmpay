/*
 * +----------------------------------------------------------------------
 *   | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *   | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 * +----------------------------------------------------------------------
 *   | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *   | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

layui.define(["table", "form", "element"],
    function(e) {
        var q = layui.$
            ,u = layui.util
            ,i = layui.table;
        // 表格初始化
        i.render({
            elem: "#app-admin-log-list",
            url: 'getList',
            //自定义响应字段
            response: {
                statusCode: 1 //数据状态一切正常的状态码
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
                    field: "module",
                    width: 100,
                    title: "模块"
                },
                {
                    field: "uid",
                    width: 100,
                    title: "操作者ID"
                },
                {
                    field: "action",
                    width: 100,
                    title: "操作行为"
                },
                {
                    field: "describe",
                    title: "行为描述"
                },
                {
                    field: "url",
                    title: "URL"
                },
                {
                    field: "ip",
                    width: 100,
                    title: "IP"
                },
                {
                    field: "create_time",
                    width: 200,
                    title: "创建时间",
                    templet: function(d) {return u.toDateString(d.create_time*1000); }
                },
                {
                    title: "操作",
                    align: "center",
                    width: 100,
                    fixed: "right",
                    toolbar: "#table-system-order"
                }]],
            page: !0,
            limit: 10,
            limits: [10, 15, 20, 25, 30],
            text: "对不起，加载出现异常！"
        }),
            i.on("tool(app-admin-log-list)",
            function(e) {
                if ("del" === e.event){
                    layer.prompt({
                            formType: 1,
                            title: "敏感操作，请验证口令"
                        },
                        function(t, i) {
                            layer.close(i),
                                layer.confirm("真的删除么",
                                    function(t) {
                                        q.ajax({
                                            url: 'logDel?id='+ e.data.id,
                                            method:'POST',
                                            success:function (res) {
                                                if (res.code == 1){
                                                    e.del()
                                                }
                                                layer.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500});
                                                layer.close(t); //关闭弹层
                                            }
                                        });
                                    })
                        });
                }
            });
        e("actionlog", {})
    });