<?php
/**
 * Created by QIEZILIFE.
 * User: yuanjy
 * Date: 2021/1/12
 * Time: 6:01 下午
 */


namespace App\Http\Middleware;

use App\Exceptions\CodeException;
use App\Exceptions\UserException;
use App\Http\Admin\Component\AdminUserComponent;
use App\Http\Common\Constant\ErrorConst;
use Closure;

/**
 * 登录校验
 *
 * Class CheckLoginMiddleware
 * @package App\Http\Middleware
 */
class CheckLoginMiddleware
{
    //不需要登录的路径白名单
    public static $white_path = [
        'admin/auth/login',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     * @throws CodeException
     * @throws UserException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $path = $request->path();
        if (in_array($path, self::$white_path)) {
            return $next($request);
        }

        $user = AdminUserComponent::getInstance()->getUser(false);
        if ($user) {
            return $next($request);
        }
        throw new CodeException(ErrorConst::NEED_LOGIN);
    }
}
