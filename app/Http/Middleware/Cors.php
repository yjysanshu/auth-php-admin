<?php
namespace App\Http\Middleware;

use App\Http\Common\Helper\LogHelper;
use Closure;

/**
 * 跨域配置
 *
 * Class Cors
 * @package App\Http\Middleware
 */
class Cors
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $origin = $request->header("Origin");
        if ($request->getMethod() == "OPTIONS") {
            $allowOrigin = explode(',', env('ALLOW_ORIGIN'));
            var_dump($allowOrigin);
            if ($allowOrigin) {
                if (!in_array($origin, $allowOrigin)) {
                    LogHelper::warning("不允许的跨域域名", [ 'origin' => $origin, ]);

                    return response()->json([
                        "code" => 405,
                        "msg" => 'FAIL',
                        "data" => [],
                    ], 405);
                } else {
                    return response()->json([
                        "code" => 200,
                        "msg" => 'SUCCESS',
                        "data" => [],
                    ], 200, [
                        'Access-Control-Allow-Origin' => $origin,
                        'Access-Control-Allow-Headers' => 'Content-Type,admin-token',
                        'Access-Control-Allow-Methods' => 'DELETE,GET,POST,OPTIONS,PUT',
                        'Access-Control-Allow-Credentials' => 'true',
                    ]);
                }
            } else {
                LogHelper::warning("允许跨域配置不存在", [ 'origin' => $origin, ]);
            }
        }

        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', $origin);
        $response->header('Access-Control-Allow-Headers', 'Content-Type,admin-token');
        $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
