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

layui.define(["treeGrid","table", "form"],
    function(e) {
        var t = layui.$,
            b = layui.table,
            i = layui.treeGrid;
        // 表格初始化
        i.render({
            id:'app-admin-menu-list'
            ,elem: '#app-admin-menu-list'
            ,url: 'getList'
            ,method:'get'//默认以post方式请求后台
            ,cellMinWidth: 100
            ,idField:'id'//必須字段
            ,treeId:'id'//树形id字段名称
            ,treeUpId:'pid'//树形父id字段名称
            ,treeShowName:'name'//以树形式显示的字段
            ,heightRemove:[".dHead",10]//不计算的高度,表格设定的是固定高度，此项不生效
            ,height:'100%'
            ,isFilter:false
            ,iconOpen:false//是否显示图标【默认显示】
            ,isOpenDefault:true//节点默认是展开还是折叠【默认展开】
            ,loading:true
            ,cols: [[{
                    type:'checkbox',
                    sort:true
                }
                ,{
                    field:'id',
                    width:100,
                    title: 'ID',
                    sort:true
                }
                ,{
                    field:'pid',
                    width:100,
                    title: '上级ID',
                    sort:true
                }
                ,{
                    field:'name',
                    width:300,
                    title: '菜单名称',
                    edit:'text',
                    sort:true
                }
                ,{
                    field:'module',
                    width:120,
                    title: '模块'
                }
                ,{
                    field:'url',
                    title: '链接'
                }
                ,{
                    width: 220,
                    title: '操作',
                    align:'center',
                    templet:"#table-menu-list"

                }
            ]]
            ,isPage:false
            ,parseData:function (res) {
                return res;
            }
        });
            i.on('tool(app-admin-menu-list)',
                function (e) {
                    var d = e.data;
                    if ("del" === e.event) layer.confirm("确定删除此菜单？",
                        function(d) {
                            t.ajax({
                                url: 'menuDel?id='+ e.data.id,
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
                    else if ("add" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "新增菜单",
                            content: "menuAdd.html?id="+ d.id,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(f, t) {
                                var l = window["layui-layer-iframe" + f],
                                    r = "app-admin-menu-submit",
                                    n = t.find("iframe").contents().find("#" + r);
                                    l.layui.form.on("submit("+ r +")",
                                        function(t) {
                                            var l = t.field;
                                            console.log(l)
                                            layui.$.post("menuAdd",l,function (res) {
                                                if (res.code == 1){
                                                    b.render('app-admin-menu-list'),
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
                    else if ("edit" === e.event) {
                        t(e.tr);
                        layer.open({
                            type: 2,
                            title: "编辑菜单",
                            content: "menuEdit.html?id="+ d.id+"&pid="+ d.pid,
                            maxmin: !0,                             area: ['80%','60%'],
                            btn: ["确定", "取消"],
                            yes: function(f, t) {
                                var l = window["layui-layer-iframe" + f],
                                    r = "app-admin-menu-submit",
                                    n = t.find("iframe").contents().find("#" + r);
                                l.layui.form.on("submit("+ r +")",
                                    function(t) {
                                        var l = t.field;
                                        layui.$.post("menuEdit",l,function (res) {
                                            if (res.code == 1){
                                                //更新数据表
                                                e.update({
                                                    pid: l.pid,
                                                    name: l.name,
                                                    url: l.url,
                                                    module: l.module,
                                                    icon: l.icon
                                                }),
                                                    b.render('app-admin-menu-list'),
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
            });
        e("menu", {})
    });