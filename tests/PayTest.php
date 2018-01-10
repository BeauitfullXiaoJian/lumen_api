<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PayTest extends TestCase
{
    /**
     * 异步通知测试
     *
     * @return apiData['datas']
     */
    public function testAlipPayNotify()
    {
        $notify_params = json_decode('{"gmt_create":"2018-01-02 17:12:42","charset":"UTF-8","gmt_payment":"2018-01-02 17:12:52","notify_time":"2018-01-02 17:12:52","subject":"\u5236\u7247\u5e2e","sign":"gXNdw+l49OOJKFHZwb+BeRxVBBBRI9hAZ5\/Ip7jWlET+HD3ndv+chIIWdeDS+KKW555ANJPBYAV4vCaXRMnq5bl\/2vhTuOOSLUu9ma3nAMgYMaXpyywl8qZKcw1I18YEMt2\/dB5tcP8LcXabAtxMl7Vjw07amcpbkq6dua8MHRzoHTTVGYFXXBmBW7WxNbwmJtND78wEhSA3Pcz2pdp0YiNa1YxVP6FKDU1Z4TVXey70HykhROl6p\/6oQa4CXS\/NH\/oNEv1vgtS7LCp2VuBiWB7nnzP\/qN50tvJ1qwSVrGYNwdSQPXpUALYVayN\/9omWl\/thbHV3q6XHDeKrKXSVLg==","buyer_id":"2088612565717231","body":"sky","invoice_amount":"0.01","version":"1.0","notify_id":"26b4ef4382ac4d2335960781fd82434hry","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","out_trade_no":"5a45f79cb63b9","total_amount":"0.01","trade_status":"TRADE_SUCCESS","trade_no":"2018010221001004230269615834","auth_app_id":"2017122601216087","receipt_amount":"0.01","point_amount":"0.00","app_id":"2017122601216087","buyer_pay_amount":"0.01","sign_type":"RSA2","seller_id":"2088821890695091"}', true);

        $this->call('post', '/web/alipay/order/notify_url', $notify_params);
        // $this->assertResponseOk();
        dd($this->response->getContent());
        $apiData = json_decode($this->response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }
}
