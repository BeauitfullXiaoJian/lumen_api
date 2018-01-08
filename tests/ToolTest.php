<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ToolTest extends TestCase
{
    /**
     * 测试视频/音频/富文本文件上传
     *
     * @return apiData['datas']
     */
    public function testPublicUpload()
    {
        $this->call('POST', '/tool/edit/upload', [], [], ['file' => UploadedFile::fake()->image(md5(time()) . '.jpg')]);
        $this->assertResponseOk();
        $apiData = json_decode($this->response->getContent(), true);
        $this->assertEquals($apiData['result'], true);
        $this->log('info', __class__ . '::' . __FUNCTION__, $apiData);
        return $apiData['datas'];
    }

}
