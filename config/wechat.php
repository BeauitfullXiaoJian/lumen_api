<?php
/*----------------------------------------
 *  买家账号：joqjlq5385@sandbox.com
 *  登录密码：111111
 *  支付密码：11111
 *  证件号码：356832190808272106
 * 
 *  商家账号：gsvymh7752@sandbox.com
 *  商户UID：2088102172443237
 *  登录密码：111111
 */
return [

    // 商户app_id
    'app_id' => 'wx945445c4bf50482f',

    // 商户号
    'mch_id' => '1430589002',

    // 商户秘钥
    'secret' => 'RvVmYLcVwH1XBddE6hMg7jCl1J4ETa3N',

    // 微信网关
    'gateway' => 'https://openapi.alipaydev.com/gateway.do',

    // 同步跳转
    'return_url' => "http://ts.cool1024.com/web/alipay/order/home",

    // 异步通知地址
    'notify_url' => "http://ts.cool1024.com/web/alipay/order/notify_url",
];