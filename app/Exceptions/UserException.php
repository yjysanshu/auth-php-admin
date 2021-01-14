<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2018/9/18
 * Time: 13:43
 */

namespace App\Exceptions;

use Throwable;

/**
 * 直接抛出异常的msg给用户看
 * 默认的code不记录到sentry
 * Class UserException
 * @package App\Exceptions
 */
class UserException extends \Exception
{
    public function __construct($message = "您的服务信息异常，请稍后重试", $code = -10000, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return implode(' ||| ', [ 'UserException', $this->getMessage(), $this->getCode(), $this->getFile(), $this->getLine() ]);
    }
}
