<?php

namespace App\Listeners;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use App\Events\ExampleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExampleListener implements ShouldQueue
{
    //use InteractsWithQueue;

    private $log;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // 初始化日志系统
        $log_file_path = realpath(__DIR__ . '/../../storage/logs/') . '/event.txt';
        $this->log = new Logger('API_EVENT_LOG');
        $this->log->pushHandler(new StreamHandler($log_file_path, Logger::DEBUG));
        $this->log->pushHandler(new FirePHPHandler());
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(ExampleEvent $event)
    {
        // 这里的操作是在队列里面哦
        $this->log->addInfo('触发成功');
        // $this->delete();
        // 停止传播
        // return true;
    }
}
