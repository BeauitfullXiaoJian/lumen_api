<?php
use App\Api\Contracts\ApiContract;
use App\Api\Contracts\FileContract;
use App\Api\Contracts\HttpContract;
use App\Models\StoreVipUser;
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

// 富文本编辑文件上传接口
$app->post('/tool/edit/upload', function (ApiContract $api, FileContract $file) {
    $params = $api->checkParams(['file:mimetypes:video/*,image/*']);
    return ['link' => env('APP_URL', 'http://localhost') . "/" . $file->saveFileTo('file', 'upload')];
});

// 从https://randomuser.me下载一些测试用户数据到用户表
$app->get('/tool/download/randomuser', function (ApiContract $api, HttpContract $http) {

    $response = $http->get('https://randomuser.me/api', ['results' => 100]);
    if (!isset($response) || empty($response)) {
        return $api->error('接口调用失败-请求失败');
    }
    if ($http->responsetoJson($response) === false) {
        return $api->error('接口调用失败-数据解析失败');
    }
    $response = $response['results'];
    $users = [];
    foreach ($response as $value) {
        $users[] = [
            'nick' => $value['name']['last'],
            'location' => implode(',', $value['location']),
            'email' => $value['email'],
            'phone' => $value['phone'],
            'avatar' => $value['picture']['thumbnail'],
            'gender' => $value['gender'] === 'female' ? 1 : 0,
            'vip_level' => random_int(1, 5),
            'vip_credit' => random_int(1, 9999),
        ];
    }
    StoreVipUser::insert($users);
    return $users;
});