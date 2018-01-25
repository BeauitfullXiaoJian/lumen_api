<?php

return [

    // 商户app_id
    'app_id' => 'wx945445c4bf50482f',

    // 商户号
    'mch_id' => '1430589002',

    // 商户秘钥
    'secret' => 'RvVmYLcVwH1XBddE6hMg7jCl1J4ETa3N',

    // 预下单地址
    'pre_pay_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',

    // 同步跳转
    'return_url' => "http://ts.cool1024.com/web/wechat/order/home",

    // 异步通知地址
    'notify_url' => "http://ts.cool1024.com/web/wechat/order/notify_url",
];