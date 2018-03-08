<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Faker\Factory;

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
        // 单元测试关闭签名加密
        putenv('APP_SIGN_CHECK=false');
        $real_path = realpath(__DIR__ . '/../storage/logs') . '/tests';
        $this->real_path = $real_path;
        $this->storage_path = realpath(__DIR__ . '/../storage/app');
        if (!file_exists($real_path)) mkdir($real_path);
        $this->log_file_path = $real_path . '/' . date('Y-m-d') . '.txt';
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();
        $this->setLog();
        $this->setFaker();
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

    protected function setFaker()
    {
        $this->faker = Factory::create();
    }

    protected function createHtml($name)
    {
        if ($this->response->status() !== 200) {
            $name = strtolower($name);
            file_put_contents($this->real_path . '/' . date('Y-m-d') . '-' . $name . '.html', $this->response->getContent());
        }
    }

    protected $storage_path;
    private $log;
    private $log_file_path;
    private $real_path;
    protected $faker;
}