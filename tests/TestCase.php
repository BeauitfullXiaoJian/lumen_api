<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        // 单元测试关闭CSRF
        putenv('APP_CSRF_CHECK=false');
        $this->log_file_path = realpath(__DIR__ . '/../storage/logs/') . '/' . date('Y-m-d') . '.txt';
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();
        $this->setLog();
    }

    protected function log($type, $message, $content)
    {
        $log_level = 'add' . ucfirst($type);
        $this->log->$log_level($message, $content);
    }

    protected function setLog()
    {
        $log_file_path = $this->log_file_path;
        $log = new Logger('API_TEST_RESULT');
        $log->pushHandler(new StreamHandler($log_file_path, Logger::DEBUG));
        $log->pushHandler(new FirePHPHandler());
        $this->log = $log;
    }

    private $log;
    private $log_file_path;
}
