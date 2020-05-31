<?php
declare (strict_types=1);


namespace RestfulInterfaceKit\interfaces;


/**
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/29
 * Time: 15:57
 */
interface IRestfulValidate
{
    /**
     * 获取  场景验证
     * @author jweiquan
     * @date: 2020/5/29
     * @time: 15:57
     * @return mixed
     */
    public function sceneGet();

    /**
     * 增加｜编辑   场景验证
     * @author jweiquan
     * @date: 2020/5/29
     * @time: 15:58
     * @return mixed
     */
    public function scenePost();

    /**
     * 删除  场景验证
     * @author jweiquan
     * @date: 2020/5/29
     * @time: 15:58
     * @return mixed
     */
    public function sceneDelete();
}