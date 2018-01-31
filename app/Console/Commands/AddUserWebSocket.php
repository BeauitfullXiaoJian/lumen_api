<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\AuthContract;

class AddUserWebSocket extends Command
{
    /**
     * 控制台命令 signature 的名称。
     *
     * @var string
     */
    protected $signature = 'websocket:from {secret} {token} {platform} {socket}';

    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = '添加/更新用户的websocket链接信息';

    /**
     * 用户服务。
     *
     * @var AuthContract
     */
    private $auth;


    /**
     * 创建一个新的命令实例。
     *
     * @return void
     */
    public function __construct(AuthContract $auth)
    {
        parent::__construct();
        $this->auth = $auth;
    }

    /**
     * 执行控制台命令。
     *
     * @return mixed
     */
    public function handle()
    {
        $secret = $this->argument('secret');
        $token = $this->argument('token');
        $platform = $this->argument('platform');
        $socket = $this->argument('socket');
        $check = $this->auth->checkToken($secret, $token, $platform);
        if ($check === false) {
            $this->error('ACCESS DENIED');
        } else {
            $this->log->socket = $socket;
            $this->log->save();
            $this->info('SUCCESS');
        }
    }
}