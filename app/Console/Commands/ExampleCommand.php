<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2018/9/27
 * Time: 11:24
 */

namespace App\Console\Commands;

use App\Exceptions\UserException;
use App\Http\Common\Helper\LogHelper;
use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    protected $signature = 'e {action} {--id=} {--type=} {--time=}';

    protected $description = 'example 测试工具';

    /**
     * MessageCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 可调用的action
     * @var array
     */
    private static $actions = [
        "test1",
        "test2",
        "test3",
        "test4",
        "test5",
    ];

    /**
     * php artisan message --id=1
     * $this->argument('uid')
     * $this->option('uid')
     */
    public function handle()
    {
        $action = $this->argument('action');
        if (!in_array($action, self::$actions)) {
            echo "命令参数：{action} {--id=} {--type=} {--time=}" . PHP_EOL;
            exit;
        }
        $this->$action($this->option('id'), $this->option('type'), $this->option('time'));
    }

    public function test1()
    {
        LogHelper::error("xxx", [111]);
    }

    public function test2()
    {
        LogHelper::alarm(new UserException("ssssddddd"));
    }
}
