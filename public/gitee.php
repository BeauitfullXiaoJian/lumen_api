<?php

// 本地仓库路径
$local = __DIR__;
// 安全验证字符串，为空则不验证
$secret = '123456789';

$payload = file_get_contents('php://input');
$payload = json_decode($payload, true);
var_dump($payload);

// echo shell_exec("git -C {$local}/.. pull 2>&1");
// echo shell_exec('whoami');
die("done " . date('Y-m-d H:i:s', time()));
