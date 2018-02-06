<?php
use App\Api\Contracts\ApiContract;
use App\Api\Contracts\FileContract;
use App\Api\Contracts\HttpContract;
use App\Core\AuthContract;
use App\Models\StoreVipUser;
use App\Events\ExampleEvent;
use Illuminate\Support\Facades\Storage;

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
$app->post('/tool/edit/upload', function (ApiContract $api, FileContract $file, AuthContract $auth) {
    $params = $api->checkParams(
        ['file:mimetypes:video/*,image/*', 'ng-params-one:min:4|max:100', 'ng-params-two:min:30|max:200', 'ng-params-three:max:10']
    );
    $check = $auth->checkToken($params['ng-params-one'], $params['ng-params-two'], $params['ng-params-three']);
    if ($auth->checkToken($params['ng-params-one'], $params['ng-params-two'], $params['ng-params-three'])) {
        // 使用文件系统保存
        return ['link' => env('APP_URL', 'http://localhost') . '/storage/' . $api->file('file')->store('images')];
        // 使用文件服务保存
        // return ['link' => env('APP_URL', 'http://localhost') . "/" . $file->saveFileTo('file', 'upload')];
    } else {
        return $api->error("未授权的令牌~");
    }
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

// 事件测试
$app->get('/event/test', function (ApiContract $api) {
    event(new ExampleEvent());
    return $api->success('event test success');
});

// 文件系统测试
$app->get('/filesystem/test', function (ApiContract $api) {
    Storage::disk('local')->put('file.txt', 'test file');
    return $api->success('filesystem test success');
});