<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/9
 * Time: 14:20
 */

namespace App\Http\Common\Helper;

use App\Exceptions\UserException;
use Exception;
use Illuminate\Support\Facades\Log;
use Sentry\Severity;
use Throwable;
use function Sentry\captureException;
use function Sentry\captureMessage;

class LogHelper
{
    /**
     * 记录日志到storage里面的log下
     * @param $message
     * @param array $params
     */
    public static function info($message, $params = [])
    {
        $params = is_array($params) ? $params : [$params];
        Log::channel(LOG_CHANNEL)->info($message, $params);
    }

    /**
     * 记录错误信息到sentry，并记录到storage里面的log下
     * @param $message
     * @param array $params
     * @param bool $tack
     */
    public static function warning($message, $params = [], $tack = true)
    {
        $params = is_array($params) ? $params : [$params];
        if (app()->bound('sentry')) {
            captureMessage($message, Severity::warning());
        }
        Log::channel(LOG_CHANNEL)->warning($message, $params);
    }

    /**
     * 错误日志，并提醒到企业微信
     * @param $message
     * @param array $params
     * @param bool $tack
     */
    public static function error($message, $params = [], $tack = true)
    {
        $params = is_array($params) ? $params : [$params];
        if (app()->bound('sentry')) {
            captureMessage($message, Severity::error());
        }
        Log::channel(LOG_CHANNEL)->error($message, $params);
        $e = new UserException($message . ': ' . json_encode($params));
        self::alarm($e);
    }

    /**
     * 记录异常到sentry，并记录异常信息到storage里面的log下
     * @param $e
     */
    public static function exception($e)
    {
        if (app()->bound('sentry')) {
            captureException($e);
        }
        Log::channel(LOG_CHANNEL)->error($e->getMessage());
        self::alarm($e);
    }

    /**
     * 格式化消息
     * @param $message
     * @return string
     */
    public static function sprintfMsg($message)
    {
        return '[ ' . date('Y-m-d H:i:s') . ' ] ' . $message . PHP_EOL;
    }

    /**
     * 异常报警
     * @param Throwable $e
     */
    public static function alarm(Throwable $e)
    {
        $url = env('LOG_FEISHU_WEBHOOK_URL');
        if ($url) {
            $param = [
                "msg_type" => "post",
                "content" => [
                    "post" => [
                        "zh_cn" => [
                            "title" => "异常信息：{$e->getMessage()} ",
                            "content" => [
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 异常CODE：' . $e->getCode()
                                    ]
                                ],
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 异常文件：' . $e->getFile()
                                    ]
                                ],
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 异常行数：' . $e->getLine()
                                    ]
                                ],
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 主机名称：' . gethostname()
                                    ]
                                ],
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 所属系统：auth_php_admin'
                                    ]
                                ],
                                [
                                    [
                                        "tag" => "text",
                                        "text" => '> 报警时间: ' . date('Y-m-d H:i:s')
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ];

            CurlHelper::post($url, json_encode($param));
        }
    }
}
