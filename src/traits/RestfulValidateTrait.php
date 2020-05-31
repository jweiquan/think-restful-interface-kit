<?php
declare (strict_types=1);


namespace RestfulInterfaceKit\traits;


use app\common\interfaces\IRestfulService;
use think\Validate;

/**
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/29
 * Time: 16:01
 */
trait RestfulValidateTrait
{
    protected $_params = [];


    /**
     * @var IRestfulService $_service
     */
    protected $_service;

    public function sceneGet(){}

    public function scenePost()
    {
        if (!empty($this->_params)) {
            $pk = $this->_service->_getModel()->getPk();
            foreach ($this->_params as $k => $v) {

            }
        }
    }
}