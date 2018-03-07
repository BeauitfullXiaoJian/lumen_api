<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayTest extends TestCase
{
    /**
     * 异步通知测试
     *
     * @return string
     */
    public function testAlipPayNotify()
    {
        $notify_params = json_decode('{"gmt_create":"2018-02-02 20:49:51","charset":"utf-8","seller_email":"pay@anasit.com","subject":"\u975e\u4e00\u652f\u4ed8\u8ba2\u5355","sign":"nZqA+f4AoiCSuSwdZ3V3aWczbwXyMUzxt0HuTzkNTQ6kocbQ0eYb1BNlv15Ie7J7CekDZIBs5yjTBgzAWZUZiV2sa9irHwM8PQdeSPhDZztEnjACKfkPB6HzoLw82OYA2FBRPQsk0oxoQ4WRqxVidx3w6DERr5DzaTlEfGVW4CUx4rVKSQoJsDCYQaX2nDbFrVQPta+s3GK6dafNwHzNJRZs77Ut8kQX+S+z9RjOe0Nq2nzU5SwQ9iEihDvtcj27rZ8xmrx+fJTB4NhyfSffzeITYd+V9EB1DDkuC\/EHpon4wtjeLNjQe2GGYZuiKjpbXL2umIS7B3MTjTFTEcsidw==","body":"\u975e\u4e00\u4e8c\u624b\u623f","buyer_id":"2088112045813847","invoice_amount":"0.01","notify_id":"ea04d7f5fc6bfb8e0c88e8b0809a68bmhh","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"0.01","app_id":"2017091208684867","buyer_pay_amount":"0.01","sign_type":"RSA2","seller_id":"2088121492117392","gmt_payment":"2018-02-02 20:49:51","notify_time":"2018-02-02 20:53:33","version":"1.0","out_trade_no":"5a745e68285c1","total_amount":"0.01","trade_no":"2018020221001004840594901612","auth_app_id":"2017091208684867","buyer_logon_id":"153****0309","point_amount":"0.00"}', true);
        $this->call('post', '/web/alipay/order/notify_url', $notify_params);
        $this->assertResponseOk();
        $response = json_decode($this->response->getContent(), true);
        $this->assertEquals($response, 'success');
        $this->log('info', __class__ . '::' . __FUNCTION__, [$response]);
    }

    /**
     * 同步通知测试
     *
     * @return apiData['datas']
     */
    public function testAlipPayCall()
    {
        $notify_params = json_decode('{"alipay_trade_app_pay_response":"{\"code\":\"10000\",\"msg\":\"Success\",\"app_id\":\"2017091208684867\",\"auth_app_id\":\"2017091208684867\",\"charset\":\"utf-8\",\"timestamp\":\"2018-02-02 20:08:24\",\"total_amount\":\"0.01\",\"trade_no\":\"2018020221001004840594111739\",\"seller_id\":\"2088121492117392\",\"out_trade_no\":\"5a7454a914cc2\"}","sign":"CokYO+NtzlaYOH\/1DW0fAhx3kmt5QFHH0FsPYsGBof4C2RNP1lPDIoAojRZJpsdlM9XBVHoPpjrba\/Xu9yPy\/TUXzg6yty+sCJyTQrz9JtbaXikZvaPyFFOA3Y95WTzFMVtf2QzdAZs7jGSEKNQghGY2XQZKbkVR\/91x6xIwnS9JOfU3MkQ3iNldoxTLhNtiSHT8zN2HOqZ\/\/bCgsMkvAdmWXMI12JV9et\/aA2rC9EvQsD6TtrAIRNofcWlSni1PFKXiSYDYWuzpI4tlo1xVAnRZE72xSszFl5w2AWregDozFUEt51gdFHmpagBnJ\/D7pnD6U2imy53S2p9kDlWycQ==","sign_type":"RSA2"}', true);
        $this->call('post', '/web/alipay/order/app_url', $notify_params);
        $this->assertResponseOk();
        $response = $this->response->getContent();
        $this->assertEquals($response, 'success');
        $this->log('info', __class__ . '::' . __FUNCTION__, [$response]);
    }

    public function testWechatOrderFind()
    {
        $this->call('get', '/web/wechat/app/order/search');
        $this->assertResponseOk();
        $response = $this->response->getContent();
        $this->assertEquals($response, 'success');
        $this->log('info', __class__ . '::' . __FUNCTION__, [$response]);
    }
}
