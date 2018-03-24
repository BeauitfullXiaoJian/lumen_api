<?php
use App\Sdk\Alipay\Alipay;
use App\Sdk\Wechat\Wechat;
use App\Api\Contracts\ApiContract;
use App\Models\StorePayLog;

// 获取app支付订单数据
$app->get('/alipay/app/order/new', function (ApiContract $api) {
    $pay = new Alipay();
    $order = [
        'price' => 99.8,
        'title' => '测试支付订单',
        'body' => '测试公司名称',
        'ordersn' => date('YmdHis'),
    ];
    $order_data = $pay->initAppOrderData($order['price'], $order['title'], $order['body'], $order['ordersn']);
    return $api->datas($order_data);
});

// 获取PC-WEB支付订单数据
$app->get('/alipay/pc/order/new', function (ApiContract $api) {
    $pay = new Alipay();
    $order = [
        'price' => 99.8,
        'title' => '测试支付订单',
        'body' => '测试公司名称',
        'ordersn' => date('YmdHis'),
    ];
    $order_data_url = $pay->initPcOrderData($order['price'], $order['title'], $order['body'], $order['ordersn']);
    return redirect($order_data_url);
});

// 同步跳转地址
$app->get('/alipay/order/home', function (ApiContract $api) {
    $return_params = $api->all();
    $return_params['page_info'] = "这是支付宝同步跳转的页面";
    return response()->json($return_params);
});

// 异步通知地址
$app->post('/alipay/order/notify_url', function (ApiContract $api) {

    // 获取所有请求参数
    $return_params = $api->all();

    // 初始化支付对象，验证签名
    $pay = new Alipay();
    $check_result = $pay->notifyCheck($return_params);
    if ($check_result['result']) {
        // 支付成功业务

        // StorePayLog::where(['type' => '支付宝', 'ordersn' => $return_params['out_trade_no']])->update([
        //     'params' => json_encode($return_params),
        //     'status' => 1,
        //     'no' => $return_params['trade_no'],
        // ]);

        return 'success';
    } else {
        return 'fail';
    }
});

// APP同步通知地址
$app->post('/alipay/order/app_url', function (ApiContract $api) {

    // 获取所有请求参数
    $return_params = $api->all();

    // 初始化支付对象，验证签名
    $pay = new Alipay();
    $check_result = $pay->appPayCheck($return_params);
    if ($check_result['result']) {

        // 支付成功业务
        // StorePayLog::where(['type' => '支付宝', 'ordersn' => $check_result['datas']['out_trade_no']])->update([
        //     'params' => json_encode($check_result['datas']),
        //     'status' => 1,
        //     'no' => $check_result['datas']['trade_no'],
        // ]);

        return 'success';
    } else {

        // StorePayLog::insert([
        //     'price' => 0,
        //     'ordersn' => '0',
        //     'type' => '支付宝-错误',
        //     'params' => json_encode($return_params),
        // ]);
        return 'fail';
    }
});

// 获取app支付订单数据--微信支付
$app->get('/wechat/app/order/new', function (ApiContract $api) {
    $pay = new Wechat();
    $order = [
        'price' => 1000,
        'title' => '测试支付订单',
        'body' => '测试公司名称',
        'ordersn' => date('YmdHis'),
    ];
    $order_data = $pay->initAppOrderData($order['price'], $order['title'], $order['body'], $order['ordersn']);
    return $api->datas($order_data);
});

// 查询订单-微信支付-通知出现异常，可以手动查询
$app->get('/wechat/app/order/search', function (ApiContract $api) {
    $pay = new Wechat();
    $ordersn = "2018020509121582465282329";
    $order_data = $pay->findOrder($ordersn);
    if ($order_data['return_code'] !== 'SUCCESS') {
        return $api->error("通信失败:" . $order_data['return_msg']);
    }
    if ($order_data['result_code'] !== 'SUCCESS') {
        return $api->error("业务失败:" . $order_data['err_code']);
    }
    if ($order_data['trade_state'] !== 'SUCCESS') {
        return $api->error("交易失败:" . $order_data['trade_state_desc']);
    }
    return $api->datas($order_data, '交易成功');
});