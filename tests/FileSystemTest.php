<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;

class FileSystemTest extends TestCase
{
    /**
     * 测试视频/音频/富文本文件上传
     *
     * @return apiData['datas']
     */
    public function testPublicUpload()
    {
        $this->call('GET', '/filesystem/test');
        $this->createHtml(__FUNCTION__);
        $this->assertResponseOk();
        $apiData = json_decode($this->response->getContent(), true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        $this->assertEquals($apiData['result'], true);
    }

}
