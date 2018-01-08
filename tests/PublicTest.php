<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\TokenParams;
use Illuminate\Support\Facades\Storage;

class PublicTest extends TestCase
{
    /**
     * 测试获取用户信息
     *
     * @return apiData['datas']
     */
    public function testUserInfo()
    {
        $this->get('/info', $this->getTokenParams());
        $this->assertResponseOk();
        $apiData = json_decode($this->response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }

    /**
     * 校验权限令牌
     *
     * @return apiData['datas']
     */
    public function testCheck()
    {
        $this->post('/check', $this->getTokenParams());
        $this->assertResponseOk();
        $apiData = json_decode($this->response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }

    /**
     * 获取用户所有菜单
     *
     * @return apiData['datas']
     */
    public function testMenus()
    {
        $this->get('/menus', $this->getTokenParams());
        $this->assertResponseOk();
        $apiData = json_decode($this->response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }

    /**
     * 设置用户为登入状态
     *
     * @return apiData['datas']
     */
    protected function setSign()
    {
        // 测试账号
        $params = [
            'account' => 'admin',
            'password' => 'admin',
            'platform' => 'admin',
        ];
        $response = $this->call('POST', '/signin', $params);
        $this->assertResponseOk();
        $apiData = json_decode($response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }

    /**
     * 默认配置方法
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $tokenParams = TokenParams::getParams();
        if (!isset($tokenParams)) {
            $tokenParams = $this->setSign();
            TokenParams::setParams(($tokenParams));
        }
        $this->setTokenParams($tokenParams);
    }

    protected function getTokenParams()
    {
        return $tokenParams = [
            'ng-params-one' => $this->tokenParams['secret'],
            'ng-params-two' => $this->tokenParams['token'],
            'ng-params-three' => $this->tokenParams['platform'],
        ];
    }

    protected function setTokenParams($headers)
    {
        $this->tokenParams = $headers;
    }

    protected $tokenParams;
}
