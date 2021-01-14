<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/6/17
 * Time: 15:15
 */

namespace App\Http\Common\Helper;

use App\Exceptions\UserException;
use Closure;
use Illuminate\Support\Facades\DB;

class DBHelper
{
    /**
     * 事务
     * @param Closure $closure
     * @param string $message
     * @return mixed
     * @throws UserException
     */
    public static function doWithTransaction(Closure $closure, $message = '事务中存在异常') {
        DB::beginTransaction();
        try {
            $result = $closure();
            DB::commit();
            return $result ?: true;
        } catch (\Exception $exception) {
            DB::rollback();//事务回滚
            LogHelper::warning($message, [
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            throw new UserException("系统异常,请稍后重试");
        }
    }
}