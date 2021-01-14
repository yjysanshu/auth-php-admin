<?php

namespace App\Http\Base;

use App\Exceptions\CodeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;

abstract class BaseController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * 所有的参数信息
     * @var array
     */
    protected $param;

    /**
     * BaseController constructor.
     * @param Request $request
     * @throws CodeException
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->param = $request->input();

        //参数验证
        $this->validateBusinessParam();
    }

    /**
     * 验证业务参数
     * @throws CodeException
     */
    public function validateBusinessParam()
    {
        $module = str_replace('/', '.', $this->request->path());

        $rules = config('rules.' . $module . '.rules');
        $msg = config('rules.' . $module . '.messages');
        if (!$rules || !$msg) return;

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->param, $rules, $msg);
        if ($validator->fails()) {
            $errCodeKey = $validator->messages()->first();
            throw new CodeException($errCodeKey);
        }
    }
}
