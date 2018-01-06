<?php

// 本地仓库路径
$local = __DIR__;

// 安全验证字符串，为空则不验证
$secret = '123456789';

// 日志文件路径
$git_log_file = __DIR__ . '/git.log';

// 记录日志
function add_log($git_log_file, $message)
{
    $log = sprintf('[%s]:%s', date('Y-m-d H:i:s'), $message . "\n");
    file_put_contents($git_log_file, $log, FILE_APPEND);
}

// 获取请求内容
$payload = file_get_contents('php://input');

// 写入日志--记录hook消息
add_log($git_log_file, $payload);

// 验证密码是否匹配
$payload = json_decode($payload, true);
if ($payload['password'] !== $secret) {
    header('HTTP/1.1 403 Permission Denied');
    die('Permission denied.');
}

// 执行git-pull
$pull_message = shell_exec("git -C {$local}/.. pull 2>&1");
// 记录git-pull 结果
add_log($git_log_file, $pull_message);
echo $pull_message;
echo shell_exec('whoami');
die("done " . date('Y-m-d H:i:s', time()));
