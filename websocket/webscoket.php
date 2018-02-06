<?php
$server = new swoole_websocket_server("139.129.161.216", 9502);

$server->on('open', function ($server, $req) {
    echo "connection open: {$req->fd}\n";
    $server->push($req->fd, "send some message");
    if (!isset($req->server['request_uri'])) {
        $server->push($req->fd, "error");
        $server->close($req->fd, true);
        echo "params lost";
    } else {
        $access_params = explode('/', $req->server['request_uri']);
        if (count($access_params) !== 4) {
            $server->push($req->fd, "error");
            $server->close($req->fd, true);
            var_dump($access_params);
            echo "params invalid";

        }
        $laravel_app_path = realpath(__DIR__ . '/../');
        $check_command = "cd {$laravel_app_path}\nphp artisan websocket:from {$access_params[1]} {$access_params[2]} {$access_params[3]} {$req->fd}";
        $check = exec($check_command);
        if ($check !== "SUCCESS") {
            $server->push($req->fd, "error");
            $server->close($req->fd, true);
            echo "access denied";
        }
    }
});

$server->on('message', function ($server, $frame) {
    echo "received message: {$frame->data}\n";
    // 根据收到的消息进行不同的操作---通常我们不用websocket接受客户端消息，客户端发送消息统一用http请求
    foreach ($server->connections as $fd) {
        if ($frame->fd !== $fd) {
            // 发送这条消息给其他人
            $server->push($fd, json_encode(["received message", $frame->data]));
        }
    }
});

$server->on('close', function ($server, $fd) {
    echo " connection close : {$fd} \n ";
});

// 发送消息推送
$server->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    // $server->connections 遍历所有websocket连接用户的fd，给所有用户推送
    foreach ($server->connections as $fd) {
        $server->push($fd, $request->get['message']);
    }
    return $response->end('push success');
});

$server->start();