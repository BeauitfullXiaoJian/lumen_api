<?php
//-----------------------------------------------------
// 2018-01-12 15:20:51
// author xiaojian
//-----------------------------------------------------

namespace App\Sdk\Wechat;

class Wechat
{

    private $app_id;
    private $mch_id;
    private $secret_key;
    private $notify_url;
    private $return_url;
    private $pre_pay;

    public function __construct()
    {
        $this->app_id = config('wechat.app_id');
        $this->mch_id = config('wechat.mch_id');
        $this->secret_key = config('wechat.secret');
        $this->notify_url = config('wechat.notify_url');
        $this->return_url = config('wechat.return_url');
        $this->pre_pay_url = config('wechat.pre_pay_url');
    }

    /*
     * exp      用户订单信息生成--APP
     * params   array[price,title,body,ordersn]
     * return   string(orderinfo)
     */
    public function initAppOrderData($price, $title, $body, $ordersn)
    {
        return $this->initOrderData($price, $title, $body, $ordersn, 'APP');
    }

    /*
     * exp      用户订单信息生成--小程序
     * params   array[price,title,body,ordersn]
     * return   string(orderinfo)
     */
    public function initSmallRoutineOrderData($price, $title, $body, $ordersn)
    {
        return $this->initOrderData($price, $title, $body, $ordersn, 'JSAPI');
    }

    /*
     * exp      统一用户订单信息生成
     * params   array[price,title,body,ordersn]
     * return   string(orderinfo)
     */
    public function initOrderData($price, $title, $body, $ordersn, $trade_type)
    {
        $pay_array = [];
        //生成预支付交易单的必选参数:
        $newPara = array();
        //应用ID
        $newPara["appid"] = $this->app_id;
        //商户号
        $newPara["mch_id"] = $this->mch_id;
        //设备号
        $newPara["device_info"] = "WEB";
         //随机字符串
        $newPara["nonce_str"] = $this->get_rand_key() . uniqid();
        //商品描述
        $newPara["body"] = $body;
        //商户订单号
        $newPara["out_trade_no"] = $ordersn;
        //总金额
        $newPara["total_fee"] = $price;
        //终端IP
        $newPara["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
        //通知地址
        $newPara["notify_url"] = $this->notify_url;
        //交易类型
        $newPara["trade_type"] = $trade_type;
        //签名
        $newPara["sign"] = $this->produce_wechat_sign($newPara);
        //传参
        $xmlData = $this->get_wechat_xml($newPara);
        //统一下单接口返回正常的prepay_id，再按签名规范重新生成签名后，将数据传输给APP
        $get_data = $this->send_pre_pay_curl($xmlData);
        //如果确认支付正确
        if ($get_data['return_code'] == "SUCCESS" && $get_data['result_code'] == "SUCCESS") {
            //微信支付，返回prepayid给我
            $newPara["nonce_str"] = $this->get_rand_key() . uniqid();
            $newPara['timeStamp'] = time() . "";
            $secondSignArray = array(
                "appid" => $newPara['appid'],
                "noncestr" => $newPara['nonce_str'],
                "package" => "Sign=WXPay",
                "prepayid" => $get_data['prepay_id'],
                "partnerid" => $newPara['mch_id'],
                "timestamp" => $newPara['timeStamp'],
            );
            $pay_array['sign_array'] = $secondSignArray;
            $pay_array['ordersn'] = $newPara["out_trade_no"];
            $pay_array['sign_array']['sign'] = $this->wechat_second_sign($newPara, $get_data['prepay_id']);
            return $pay_array;
        }
        return 'sign error';
    }

    private function send_pre_pay_curl($xml_data)
    {
        $url = $this->pre_pay_url;
        $header[] = "Content-type: text/xml";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml_data);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            print curl_error($curl);
        }
        curl_close($curl);
        return $this->xml_data_parse($data);
    }

    private function xml_data_parse($data)
    {
        $msg = array();
        $msg = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $msg;
    }

    // 获取一个随机串
    private function get_rand_key()
    {
        $string = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
        $cdkey = "";
        for ($i = 0; $i < 3; $i++) {
            $cdkey .= $string[rand(0, strlen($string) - 1)];
        }
        return $cdkey;
    }

    // 生成签名--第一次签名
    public function produce_wechat_sign($new_para)
    {
        $stringA = $this->get_sign_content($new_para);
        $stringSignTemp = $stringA . "&key={$this->secret_key}";
        return strtoupper(MD5($stringSignTemp));
    }

    // 生成签名--第二次签名
    private function wechat_second_sign($newPara, $prepay_id)
    {
        $secondSignArray = array(
            "appid" => $newPara['appid'],
            "noncestr" => $newPara['nonce_str'],
            "package" => "Sign=WXPay",
            "prepayid" => $prepay_id,
            "partnerid" => $newPara['mch_id'],
            "timestamp" => $newPara['timeStamp'],
        );
        $stringA = self::get_sign_content($secondSignArray);
        $stringSignTemp = $stringA . "&key={$this->secret_key}";
        return strtoupper(MD5($stringSignTemp));
    }

    // 获取要签名的内容
    private function get_sign_content($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->check_empty($v) && "@" != substr($v, 0, 1)) {
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        return $stringToBeSigned;
    }

    // 判断内容是否为空
    private function check_empty($value)
    {
        if (!isset($value)) return true;
        if ($value === null) return true;
        if (trim($value) === "") return true;
        return false;
    }

    // 编码转换
    private function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    // 数组拼接成XML
    public static function get_wechat_xml($newPara)
    {
        $xmlData = "<xml>";
        foreach ($newPara as $key => $value) {
            $xmlData = $xmlData . "<" . $key . ">" . $value . "</" . $key . ">";
        }
        $xmlData = $xmlData . "</xml>";
        return $xmlData;
    }

    // /*
    //  * exp      异步回掉
    //  * params   string(data)
    //  * return   array[...]
    //  */
    // public function notifyCheck($params)
    // {
    //     $alipay_public_key = $this->alipay_public_key;

    //     $return = [
    //         //验签结果
    //         'result' => false,
    //         //提示消息
    //         'message' => '',
    //         //可用参数
    //         'datas' => array(),
    //     ];

    //     /*---------------剔除参数------------------*/

    //     $sign = $params['sign'];

    //     unset($params['sign']);

    //     $sign_type = $params['sign_type'];

    //     unset($params['sign_type']);

    //     /*---------------数据拼接------------------*/
    //     foreach ($params as $key => $value) {
    //         $params[$key] = $key . '=' . $value;
    //         $return['datas'][$key] = $value;
    //     }

    //     $data = implode('&', $params);

    //     /*---------------字典排序-------------------*/
    //     $data = $this->get_sort_data($data);

    //     /*---------------开始验签-------------------*/
    //     $public_key = $this->get_public_key($alipay_public_key);

    //     $sign = base64_decode(stripslashes($sign));

    //     $return['result'] = (bool)openssl_verify($data, $sign, $public_key, OPENSSL_ALGO_SHA256);

    //     $return['message'] = $return['result'] ? "验签成功" : "验签失败";

    //     if ($return['result']) {
    //         if ($params['app_id'] != "app_id={$this->app_id}") {
    //             $return['result'] = false;
    //             $return['message'] = "消息错误";
    //         }
    //     }

    //     return $return;
    // }

    // /*
    //  * exp     私钥处理方法，把private_key处理为可用私钥
    //  * pramas  string(private_key)
    //  * return  string(private_key)
    //  */
    // private function get_private_key($private_key)
    // {
    //     $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
    //     $private_key = openssl_pkey_get_private($private_key);
    //     return $private_key;
    // }

    // /*
    //  * exp     公钥处理方法，把public_key处理为可用公钥
    //  * pramas  string(public_key)
    //  * return  string(public_key)
    //  */
    // private function get_public_key($public_key)
    // {
    //     $public_key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    //     $public_key = openssl_get_publickey($public_key);
    //     return $public_key;
    // }

    // /*
    //  * exp     字典排序，把原始数据按字母索引排序
    //  * pramas  string(data)
    //  * return  string(data)
    //  */
    // private function get_sort_data($data)
    // {
    //     $array = array();
    //     $result = array();
    //     $values = explode('&', $data);
    //     foreach ($values as $key => $value) {
    //         $temp = explode('=', $value);
    //         $array[$temp[0]] = $temp[1];
    //     }

    //     ksort($array);
    //     foreach ($array as $key => $value) {
    //         array_push($result, $key . '=' . $value);
    //     }
    //     $data = implode("&", $result);

    //     return $data;
    // }
    // /*
    //  * exp     urlencode数据，把数据串中的值进行urlencode
    //  * pramas  string(data)
    //  * return  string(data)
    //  */
    // private function get_url_data($data)
    // {
    //     $array = explode('&', $data);
    //     $keyarray = array();
    //     foreach ($array as $key => $value) {
    //         $temp = explode('=', $value);
    //         $keyarray[$key] = $temp[0];
    //         $array[$key] = urlencode($temp[1]);
    //     }
    //     foreach ($array as $key => $value) {
    //         $array[$key] = $keyarray[$key] . '=' . $array[$key];
    //     }
    //     $data = implode("&", $array);
    //     return $data;
    // }
}
