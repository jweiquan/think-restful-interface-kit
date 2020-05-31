<?php
declare (strict_types = 1);

namespace RestfulInterfaceKit\interfaces;


/**
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/27
 * Time: 13:33
 */
interface IRestfulController
{
    public function get(); //查
    public function post(); //增|改
    public function delete(); //删
}