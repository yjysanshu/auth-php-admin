<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2018/9/18
 * Time: 13:43
 */

namespace App\Exceptions;

use App\Http\Common\Constant\ErrorConst;
use Throwable;

/**
 * 直接抛出异常的msg给用户看
 * 默认的code不记录到sentry
 * Class CodeException
 * @package App\Exceptions
 */
class CodeException extends \Exception
{
    public function __construct($code = -10000, $message = "您的服务信息有误，请稍后重试", Throwable $previous = null)
    {
        parent::__construct(ErrorConst::$msg[$code] ?? $message, $code, $previous);
    }

    public function __toString()
    {
        return implode(' ||| ', [ 'CodeException', $this->getMessage(), $this->getCode(), $this->getFile(), $this->getLine() ]);
    }
}
