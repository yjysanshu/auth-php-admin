<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2018/11/29
 * Time: 17:39
 */

namespace App\Http\Middleware;

use App\Http\Common\Helper\LogHelper;
use Closure;

/**
 * 日志记录
 *
 * Class LogdbMiddleware
 * @package App\Http\Middleware
 */
class LogdbMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $startTime = msectime();
        LogHelper::info("*********** {$request->path()} 接口执行开始 ***************");
        LogHelper::info("参数信息", [ $request->input() ]);
        LogHelper::info("头信息", [ $request->header() ]);
        $response = $next($request);
        $endTime = msectime();
        $spendTime = $endTime - $startTime;
        LogHelper::info("返回的信息", [ $response->original ]);
        LogHelper::info("*********** {$request->path()} 接口执行结束 {$spendTime} ***************");

        return $response;
    }
}
