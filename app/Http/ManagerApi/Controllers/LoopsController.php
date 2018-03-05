<?php

namespace App\Http\ManagerApi\Controllers;

use App\Http\ManagerApi\Controllers\Controller;
use App\Models\StoreLoops;

class LoopsController extends Controller
{

    //添加新幻灯片
    public function add()
    {

        $params = $this->api->checkParams(['src:mimetypes:image/*', 'url']);

        // 保存图片
        $params['src'] = $this->file->saveFileTo('src', 'upload');

        // 添加新幻灯片
        $max = StoreLoops::max('level');
        $params['level'] = empty($max) ? 1 : ++$max;
        $params['id'] = StoreLoops::insertGetId($params);
        return $this->api->datas($params);
    }

    //删除幻灯片
    public function delete()
    {
        $params = $this->api->checkParams(['id:integer']);

        // 判断轮播图是否存在
        $loopCard = StoreLoops::find($params['id']);
        if (!isset($loopCard)) {
            return $this->api->error('删除的幻灯片不存在～');
        }

        // 从数据库移除
        StoreLoops::destroy($params['id']);

        // 从文件夹移除
        $this->file->removeFile($loopCard->src);

        return $this->api->success('删除成功～');
    }

    //修改幻灯片
    public function update()
    {

        $params = $this->api->checkParams(['id:integer', 'url'], ['src:mimetypes:image/*']);

        // 判断轮播图是否存在
        $loopCard = StoreLoops::find($params['id']);
        if (!isset($loopCard)) {
            return $this->api->error('修改的幻灯片不存在～');
        }

        // 保存图片
        if ($this->file->fileIsReady('src')) {
            $loopCard->src = $this->file->saveFileTo('src', 'upload');
        }

        $loopCard->url = $params['url'];
        $loopCard->save();
        return $this->api->datas($loopCard);
    }

    //获取所有幻灯片
    public function gets()
    {
        return $this->api->datas(StoreLoops::orderBy('level')->get());
    }

    //幻灯片排序
    public function sort()
    {
        $params = $this->api->checkParams(['ids:string']);
        $ids = explode(',', $params['ids']);
        $result = with(new StoreLoops())->sort($ids, 'level');
        return $result ? $this->api->success("排序成功") : $this->api->error("排序失败");
    }

}
