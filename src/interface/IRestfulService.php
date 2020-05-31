<?php
declare (strict_types = 1);

namespace RestfulInterfaceKit\interfaces;

use think\Model;

/**
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/27
 * Time: 13:40
 */
interface IRestfulService
{
    public function index($request); //查
    public function addOrEdit($request); //增｜改
    public function delete($request);//删
    public function _getModel() : Model;
}