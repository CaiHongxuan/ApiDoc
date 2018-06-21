<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19
 * Time: 11:56
 */

namespace App\Http\Traits;


trait ApiTrait
{
    protected $errorCode = 0;
    protected $resMsg = 'ok';
    protected $state = 'success';

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param boolean $state
     * @return ApiTrait
     */
    public function setState($state = true)
    {
        $this->state = $state ? 'success' : 'failed';
        return $this;
    }

    /**
     * @return string
     */
    public function getResMsg()
    {
        return $this->resMsg;
    }

    /**
     * @param string $resMsg
     * @return ApiTrait
     */
    public function setResMsg($resMsg)
    {
        $this->resMsg = $resMsg;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     * @return ApiTrait
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    protected function setResponse($code = 0, $msg = '')
    {
        return $this->setErrorCode($code)->setResMsg($msg);
    }

    protected function response($data = [])
    {
        return response()->json([
            'data'  => $data,
            'code'  => $this->errorCode,
            'msg'   => $this->resMsg,
            'state' => $this->state
        ]);
    }

    /**
     * 失败返回
     * @param        $code
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($code, $msg = '')
    {
        return $this->setState(false)->setResponse($code, $msg)->response();
    }

    /**
     * 成功返回
     * @param array  $content
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($content = [], $msg = 'ok')
    {
        return $this->setState(true)->setResponse(0, $msg)->response($content);
    }
}