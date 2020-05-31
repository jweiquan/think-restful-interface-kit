<?php


namespace RestfulInterface\traits;

use RestfulInterfaceKit\interfaces\IRestfulService;
use app\Request;

/**
 * 实现简单业务需求的接口类型
 *  使用前请实现 app\common\traits\RestfulControllerTrait接口
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/27
 * Time: 13:31
 * @property Request $request
 * @method array|string|true validate(array $data, $validate, array $message = [], bool $batch = false)
 */
 trait RestfulControllerTrait
{
    public function get()
    {
        $validator = $this->_getValidator();
        if (!empty($validator)) {
            $this->validate($this->request->param(), $validator . '@get');
        }
        return $this->_getService()->index($this->request);
    }

    public function post()
    {
        $validator = $this->_getValidator();
        if (!empty($validator)) {
            $this->validate($this->request->param(), $validator . '@post');
        }
        return $this->_getService()->addOrEdit($this->request);
    }


    public function delete()
    {
        $validator = $this->_getValidator();
        if (!empty($validator)) {
            $this->validate($this->request->param(), $validator . '@delete');
        }
        return $this->_getService()->delete($this->request);
    }

    abstract protected function _getService() : IRestfulService;

    abstract protected function _getValidator() : string;
}