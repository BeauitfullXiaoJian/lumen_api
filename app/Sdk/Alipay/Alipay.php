<?php
//-----------------------------------------------------
//2017-03-07 09:51:21
// author xiaojian
// 提供了签名订单信息的生成方法，请确保认真阅读了支付宝APP开发文档
// 代码仅供参考，请不要用于生产环境，一切以支付宝官方文档为准
// 请确保PHP引入了OPENSSL模块
//-----------------------------------------------------
//
// 使用示例
// $order_inof=GetOrderInfo(array(
//     'title'=>"测试订单",
//     'body'=>"订单描述：不要提供空的信息哦",
//     'price'=>"0.01",
//     'ordersn'=>"201703070951210001"
// ));
//-----------------------------------------------------
namespace App\Sdk\Alipay;

class Alipay
{

    private $alipay_public_key;
    private $private_key;
    private $app_id;
    private $notify_url;
    private $return_url;
    private $gateway;

    public function __construct()
    {
        $this->alipay_public_key = config('alipay.alipay_public_key');
        $this->private_key = config('alipay.private_key');
        $this->app_id = config('alipay.app_id');
        $this->notify_url = config('alipay.notify_url');
        $this->return_url = config('alipay.return_url');
        $this->gateway = config('alipay.gateway');
    }

    /*
     * exp      用户订单信息生成--APP
     * params   array[price,title,body,ordersn]
     * return   string(orderinfo)
     */
    public function initAppOrderData($price, $title, $body, $ordersn)
    {
        
        //APPID
        $app_id = $this->app_id;
        
        //支付创建时间
        $timestamp = date('Y-m-d H:i:s');
        
        //支付超时
        $biz_content["timeout_express"] = "30m";
        
        //产品编码，固定值（必填）
        $biz_content["product_code"] = "QUICK_MSECURITY_PAY";
        
        //支付金额（必填）
        $biz_content["total_amount"] = $price;
        
        //支付标题（必填）
        $biz_content["subject"] = $title;
        
        //描述信息（选填）
        $biz_content["body"] = $body;
        
        //订单编号（必填）
        $biz_content["out_trade_no"] = $ordersn;
        
        //异步通知地址（选填，肯定是要的）
        $notify_url = $this->notify_url;
        
        
        //其他附加参数，编码，格式，api接口，异步通知地址,此处数据一般不变
        $other_info = "&method=alipay.trade.app.pay&charset=utf-8&version=1.0&sign_type=RSA2&notify_url=" . $notify_url;
        
        //用户私钥
        $private_key = $this->get_private_key('MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQChmYzzhvlPC/se38nK95VBxl7SWfp37FmifueGFw/9yy/rq4JsQYd1rr4o9fCI13qQScIzN/qtr+AHvr4ReGuixAeFcpmFBLtf6Upe63Jkn3pHTCVjViAlba3FMdqx0UtSeK1E+/WoY4QYEUeiv1hon6IueJRm65uN6t7411ikXm4ViHqTiKoJXTSYCp8mIsmwAsiJTIQt7TcjP6HKT4dcsx5q8zWfngYgQflqj1sJpu1b5kaKKtHAphdeZ+hUradxEFj29NsLgRu3YAHutwMCOvZvbmyTi/I4s0823x0uhRqdm5dPp6HNYkJhEiuJzuIhmcgLKernXAi6G25ZXsbVAgMBAAECggEAfrgI9mIuF5U+izxTYcx5h0WFz49tQCLfOvQmm1h0WsC9SCGKuVc9YMPxK++Hedb2rjSYi09nTFGw7IHfS5XRWaY8e6GcztkiEZi/j+UzI8KGwWftnZLIzHDQJeTtKCkF1pr4zDKDtVKsH69VLEK9kfUz2RsGzBoPNd8qj5lWUjTPP0aOPU6YwBXxvu9mne6vZ+AgqYeMQttVj5HiK9iOcKQ3R2nGo5iHbzDDjl/Oxu8Jf+HAbHRcRJgFpsz6SD0j8xvhOiER0jTPub1FYhsJSvGm3D9AWhv8i9zsw9Xho6cVktLA5OFPlJCEGov8XXierskakwEd314WI64+kIfV4QKBgQD+YR5AzotxIM2nq42aJHOvznIJFyLYhS393TlTuMIyfu0+4DgeTNyXffnjNKTLkrEstRwppZVuS2IJcxvhezMobuwZrqEq2/gIEk1UUlpj4fI2NNFsxW80YLYL3yYrhdcyzqCA8tEGiYneUpurqjvGVeejDPv9eeX/+eel+DCZ+QKBgQCioRzpWF7DXRAKv/+jt8Sp+cwfp1z+wI4f8tAVYaJ7LrAUdNUN0mTDQTAT3RnLH5jIPlzg+vnhSK5DDomRF703Xo7EpR1Bq+xbUdamx4YQo0QN7LQPakNYI4zRwzfbsC1/lnhRDPC6DQEe3ULQER/3o3eOZ3QGzO4dGyQf+pxqvQKBgQCtyOGnOrRO7C8zvmL2DLMwR2TmyvSWYZ4DEnDIvq+FWetxCjOsl6wYnwrp6xwuq5/5QB3mYeZNvJhUhlxk1gskM2t/PCuFIVQ5B2/nDIAOPt1/pOPyYIvRh8S3JZNpJn3XiwxiLAEbazlSNrN9OsatgCDI5uT8zZuRHkTgwUdK2QKBgB7nivz05B0wBCpmzgeolmDOrXGedlea8+cyV0SY5y2Nc7yPbyVPRAKu0poTvCETgg8ber7uMqoTC7qGerHt1vE8sNjBn8upBiNca/QJmnpy7C3RO25qfR20s7/w3x6KXjsOtxJ/6QcSDZJ17YpolrCSidev5SadruotDDJfh3XpAoGAZlW8CKavJ7zTPobrqmef+PQqR7s5icBe0+d9uqCXdpq6dVlIX1ZPdXn64QM0MvF60U8estyVCBVwKnVZONjhEUW4hrvbGIdlvjc8TWrzbj20e7kVRzJeeARrHAz7TiyuD0gPlRx5cT1Q4dU+h4aaw08F22vuxTXB/8qtazniIes=');

        /*---------------拼接数据，生成原始串------------*/

        foreach ($biz_content as $key => $value) {
            $biz_content[$key] = urlencode($value);
        }
        $biz_content = urldecode(json_encode($biz_content));
        $data = "app_id=" . $app_id . '&timestamp=' . $timestamp . '&biz_content=' . $biz_content . $other_info;
        
        /*-------------------排序参数-------------------*/

        $data = $this->get_sort_data($data);
        
        /*-------------------生成签名-------------------*/

        $signature = '';
        openssl_sign($data, $signature, $this->private_key, OPENSSL_ALGO_SHA256);
        openssl_free_key($private_key);
        $signature = base64_encode($signature);
        $signature = urlencode($signature);
        
        /*----------------ENCODE外层数据----------------*/

        $data = $this->get_url_data($data);
        
        /*---------------数据追加签名---------------------*/

        $data = $data . "&sign=" . $signature;

        return $data;
    }

     /*
     * exp      用户订单信息生成--PC-WEB
     * params   array[price,title,body,ordersn]
     * return   string(orderinfo)
     */
    public function initPcOrderData($price, $title, $body, $ordersn)
    {
        
        //APPID
        $app_id = $this->app_id;
        
        //支付创建时间
        $timestamp = date('Y-m-d H:i:s');
        
        //支付超时
        $biz_content["timeout_express"] = "30m";
        
        //产品编码，固定值（必填）
        $biz_content["product_code"] = "FAST_INSTANT_TRADE_PAY";
        
        //支付金额（必填）
        $biz_content["total_amount"] = $price;
        
        //支付标题（必填）
        $biz_content["subject"] = $title;
        
        //描述信息（选填）
        $biz_content["body"] = $body;
        
        //订单编号（必填）
        $biz_content["out_trade_no"] = $ordersn;
        
        //异步通知地址（选填，肯定是要的）
        $notify_url = $this->notify_url;
        $return_url = $this->return_url;
        
        //其他附加参数，编码，格式，api接口，异步通知地址,此处数据一般不变
        $other_info = "&method=alipay.trade.page.pay&charset=utf-8&version=1.0&sign_type=RSA2&notify_url=" . $notify_url . "&return_url=" . $return_url;
        
        //用户私钥
        $private_key = $this->get_private_key($this->private_key);

        /*---------------拼接数据，生成原始串------------*/

        foreach ($biz_content as $key => $value) {
            $biz_content[$key] = urlencode($value);
        }
        $biz_content = urldecode(json_encode($biz_content));
        $data = "app_id=" . $app_id . '&timestamp=' . $timestamp . '&biz_content=' . $biz_content . $other_info;
        /*-------------------排序参数-------------------*/

        $data = $this->get_sort_data($data);
        
        /*-------------------生成签名-------------------*/

        $signature = '';
        openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA256);
        openssl_free_key($private_key);
        $signature = base64_encode($signature);
        $signature = urlencode($signature);

        /*----------------ENCODE外层数据----------------*/

        $data = $this->get_url_data($data);
        
        /*---------------数据追加签名---------------------*/

        $data = $data . "&sign=" . $signature;
        // parse_str($data, $data);
        // echo json_encode($data);
        // exit(0);
        return $this->gateway . '?' . $data;
    }


    /*
     * exp      异步回掉
     * params   string(data)
     * return   array[...]
     */
    public function notifyCheck($params)
    {
        file_put_contents("/home/payback.txt", json_encode($params), FILE_APPEND);
        // $params = json_decode('{"gmt_create":"2018-01-02 17:12:42","charset":"UTF-8","gmt_payment":"2018-01-02 17:12:52","notify_time":"2018-01-02 17:12:52","subject":"\u5236\u7247\u5e2e","sign":"gXNdw+l49OOJKFHZwb+BeRxVBBBRI9hAZ5\/Ip7jWlET+HD3ndv+chIIWdeDS+KKW555ANJPBYAV4vCaXRMnq5bl\/2vhTuOOSLUu9ma3nAMgYMaXpyywl8qZKcw1I18YEMt2\/dB5tcP8LcXabAtxMl7Vjw07amcpbkq6dua8MHRzoHTTVGYFXXBmBW7WxNbwmJtND78wEhSA3Pcz2pdp0YiNa1YxVP6FKDU1Z4TVXey70HykhROl6p\/6oQa4CXS\/NH\/oNEv1vgtS7LCp2VuBiWB7nnzP\/qN50tvJ1qwSVrGYNwdSQPXpUALYVayN\/9omWl\/thbHV3q6XHDeKrKXSVLg==","buyer_id":"2088612565717231","body":"sky","invoice_amount":"0.01","version":"1.0","notify_id":"26b4ef4382ac4d2335960781fd82434hry","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","out_trade_no":"5a45f79cb63b9","total_amount":"0.01","trade_status":"TRADE_SUCCESS","trade_no":"2018010221001004230269615834","auth_app_id":"2017122601216087","receipt_amount":"0.01","point_amount":"0.00","app_id":"2017122601216087","buyer_pay_amount":"0.01","sign_type":"RSA2","seller_id":"2088821890695091"}', true);

        $alipay_public_key = $this->alipay_public_key;

        $return = [
            //验签结果
            'result' => false,
            //提示消息
            'message' => '',
            //可用参数
            'datas' => array(),
        ];

        /*---------------剔除参数------------------*/

        $sign = $params['sign'];

        unset($params['sign']);

        $sign_type = $params['sign_type'];

        unset($params['sign_type']);

        /*---------------数据拼接------------------*/
        foreach ($params as $key => $value) {
            $params[$key] = $key . '=' . $value;
            $return['datas'][$key] = $value;
        }

        $data = implode('&', $params);

        /*---------------字典排序-------------------*/
        $data = $this->get_sort_data($data);

        /*---------------开始验签-------------------*/
        $public_key = $this->get_public_key($alipay_public_key);

        $sign = base64_decode(stripslashes($sign));

        $return['result'] = (bool)openssl_verify($data, $sign, $public_key, OPENSSL_ALGO_SHA256);

        $return['message'] = $return['result'] ? "验签成功" : "验签失败";

        if ($return['result']) {
            if ($params['app_id'] != "app_id={$this->app_id}") {
                $return['result'] = false;
                $return['message'] = "消息错误";
            }
        }

        return $return;
    }

    /*
     * exp     私钥处理方法，把private_key处理为可用私钥
     * pramas  string(private_key)
     * return  string(private_key)
     */
    private function get_private_key($private_key)
    {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        $private_key = openssl_pkey_get_private($private_key);
        return $private_key;
    }

    /*
     * exp     公钥处理方法，把public_key处理为可用公钥
     * pramas  string(public_key)
     * return  string(public_key)
     */
    private function get_public_key($public_key)
    {
        $public_key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        $public_key = openssl_get_publickey($public_key);
        return $public_key;
    }

    /*
     * exp     字典排序，把原始数据按字母索引排序
     * pramas  string(data)
     * return  string(data)
     */
    private function get_sort_data($data)
    {
        $array = array();
        $result = array();
        $values = explode('&', $data);
        foreach ($values as $key => $value) {
            $temp = explode('=', $value);
            $array[$temp[0]] = $temp[1];
        }

        ksort($array);
        foreach ($array as $key => $value) {
            array_push($result, $key . '=' . $value);
        }
        $data = implode("&", $result);

        return $data;
    }
    /*
     * exp     urlencode数据，把数据串中的值进行urlencode
     * pramas  string(data)
     * return  string(data)
     */
    private function get_url_data($data)
    {
        $array = explode('&', $data);
        $keyarray = array();
        foreach ($array as $key => $value) {
            $temp = explode('=', $value);
            $keyarray[$key] = $temp[0];
            $array[$key] = urlencode($temp[1]);
        }
        foreach ($array as $key => $value) {
            $array[$key] = $keyarray[$key] . '=' . $array[$key];
        }
        $data = implode("&", $array);
        return $data;
    }
}
