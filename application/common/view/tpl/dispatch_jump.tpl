{__NOLAYOUT__}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$msg} - 跳转提示</title>
</head>
<body style="background-color: #f7f9fa;">
<div style="margin: -15px; padding: 8vh 0 2vh;color: #a6aeb3; background-color: #ffffff; text-align: center; font-family:NotoSansHans-Regular,'Microsoft YaHei',Arial,sans-serif; -webkit-font-smoothing: antialiased;">
    <div style="width: 750px; max-width: 85%; margin: 0 auto; background-color: #fff; -webkit-box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);-moz-box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);">
        <div style="padding: 20px 10%; text-align: center; font-size: 16px;line-height: 16px;">
            <a href="https://www.iredcap.cn" style="vertical-align: top;" target="_blank"> <img style="margin:32px auto; max-width: 95%; color: #0e2026;" src="__COMMON__/logo-color.png" /> </a>
        </div>
        <table width="600" style="background-color:#fff;margin:0 auto;" cellpadding="0" cellspacing="0">
            <tbody><tr>
                <td>
                    <table width="600" style="background-color:#fff;margin:0 auto;" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td colspan="3" style="height:40px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="line-height:20px;">
                                <p style="text-align:center;margin:0;padding:0;">
                                    {php}$errCode = $code == 1 ? 'success' : ($code == 0 ? 'wrong' : 'waring');{/php}
                                    <img src="__COMMON__/images/icon/{$errCode}.png" width="24" height="24" style="margin:0 12px;vertical-align:top;">
                                    <span style="font-size:18px;line-height:24px;color:{$code == 0 ? 'red' : 'green'};">{$msg}</span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td width="40">&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody></table>
        <div style="padding-bottom: 40px;font-size: 14px;">
            <div style="padding-bottom: 40px;font-size: 14px;">
                <div style="width: 420px; max-width: 90%;margin: 10px auto;">
                    彻底告别繁琐的支付接入流程 一次接入所有主流支付渠道和分期渠道，99.99% 系统可用性，满足你丰富的交易场景需求,为你的用户提供完美支付体验。
                </div>
                <div>
                    <span style="color: #76858c;">服务咨询请联系：</span>
                    <a href="me@iredcap.cn" style="color:#35c8e6; text-decoration: none;" target="_blank"> me@iredcap.cn </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>