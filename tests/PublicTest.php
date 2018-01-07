<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PublicTest extends TestCase
{

    // use DatabaseTransactions;

    /**
     * 测试用户登入
     *
     * @return token(string)
     */
    public function testSign()
    {
        // 测试账号
        $params = [
            'account' => 'admin',
            'password' => 'admin',
            'platform' => 'admin',
        ];

        // $response = $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
        $response = $this->call('POST', 'signin', $params);
        $apiData = json_decode($response->getContent(), true);
        $this->assertResponseOk();
        $this->assertEquals($apiData['result'], true);
        echo "测试登入成功，获得令牌{$apiData['datas']['token']}";
        return '111';
    }
}
