<?php
use App\Api\Contracts\ApiContract;
use App\Api\Contracts\FileContract;

// 音频上传
$app->post('/tool/audio', function (ApiContract $api, FileContract $file) {
    $params = $api->checkParams(['audio:mimetypes:audio/*']);
    $url = $file->saveFileTo('audio', 'upload');
    return $api->datas($url);
});

// 视频上传
$app->post('/tool/video', function (ApiContract $api, FileContract $file) {
    $params = $api->checkParams(['video:mimetypes:video/*']);
    $url = $file->saveFileTo('video', 'upload');
    return $api->datas($url);
});
