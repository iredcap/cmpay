/** layuiAdmin.std-v1.0.0 LPPL License By http://www.layui.com/admin/ */ ;
layui.define(["form", "upload"], function(t) {
    var i = layui.$,
        e = layui.layer,
        a = (layui.laytpl, layui.setter, layui.view, layui.admin),
        n = layui.form,
        s = layui.upload;
    i("body");
    n.verify({
        nickname: function(t, i) {
            return new RegExp("^[a-zA-Z0-9_一-龥\\s·]+$").test(t) ? /(^\_)|(\__)|(\_+$)/.test(t) ? "用户名首尾不能出现下划线'_'" : /^\d+\d+\d$/.test(t) ? "用户名不能全为数字" : void 0 : "用户名不能有特殊字符"
        },
        pass: [/^[\S]{6,12}$/, "密码必须6到12位，且不能出现空格"],
        repass: function(t) {
            if (t !== i("#LAY_password").val()) return "两次密码输入不一致"
        }
    }), n.on("submit(set_website)", function(t) {
        //这里是基本信息
        i.post("website",t.field,function (res) {
            return e.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500},function () {
                window.location.reload()
            })
        });
    }), n.on("submit(set_system_email)", function(t) {
        //这里是基本信息
        i.post("email",t.field,function (res) {
            return e.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500},function () {
                window.location.reload()
            })
        });
    }), n.on("submit(setmyinfo)", function(t) {
        //这里是基本信息
        i.post("profile",t.field,function (res) {
            return e.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500},function () {
                window.location.reload()
            })
        });
    }),n.on("submit(setmypass)", function(t) {
        //修改管理密码
        i.post("changePwd",t.field,function (res) {
            return e.msg(res.msg, {icon: res.code == 1 ? 1: 2,time: 1500},function () {
                window.location.reload()
            })
        });
    }), t("set", {})
});