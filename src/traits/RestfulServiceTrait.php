<?php


namespace RestfulInterfaceKit\traits;


use app\common\http\ErrorResponse;
use app\Request;
use Exception;
use think\Model;
use think\paginator\driver\Bootstrap;

/**
 * 实现简单业务需求的服务类类型
 *  使用前请实现 app\common\traits\RestfulServiceTrait接口
 * This is a part of miudrive.
 * Author: jweiquan
 * Date: 2020/5/27
 * Time: 11:53
 */
trait RestfulServiceTrait
{
    /**
     * 获取列表
     * @param array|Request $request
     * @return Mixed
     * @throws Exception
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 16:28
     */
    public function index($request)
    {
        $model = $this->_getModel();
        $pk = $model->getPk();
        $params = $this->_getParams($request);

        if (array_key_exists($pk, $params) &&
            false === $multi = strpos($params[$pk], ',') //是不是发来逗号隔开的，如果有逗号，使用查询多条的模式
        ) { //判断查单条
            $id = $params[$pk];
            $data =  $model->find([$pk => $id]);
            if (!empty($data)) {
                $data = $data->toArray();
                $this->_parseData($data, $model);
            }

        } else {
            if (isset($multi) && $multi) { //逗号隔开转换成in查询
                $where[$pk] = ['in', explode($params[$pk], ',')];
                unset($params[$pk]);
            }
            $where = $this->_onBeforeIndexWhere($params);
            $where = array_merge($where, $params);
            $data =  $this->list($this->_getModel(), $where);

            if (!empty($data)) {
                if ($data instanceof Bootstrap) { //翻页类判断
                    $data = $data->toArray();
                    if (!empty($data['data'])) {
                        foreach ($data['data'] as &$value) {
                            $this->_parseData($value, $model);
                        }
                    }
                } else { //普通列表
                    /**
                     * @var Model $data
                     */
                    $data = $data->toArray();
                    foreach ($data as &$value) {
                        $this->_parseData($value, $model);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 添加或编辑
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 16:28
     * @param Request|array $request
     * @return Mixed
     */
    public function addOrEdit($request)
    {
        $model = $this->_getModel();
        $pk = $model->getPk();
        $params = $this->_getParams($request);

        if (!isset($params[$pk])) {
            $params[$pk] = generateID();

            return $this->createData($model,$params) ?? $this->getErr('add');
        } else {
            $where = [$pk => $params[$pk]];
            unset($params[$pk]);

            return $this->editData($model, $where , $params) ?? $this->getErr('edit');
        }
    }

    /**
     * 删除
     * @param Request|array $request
     * @return Mixed
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 16:28
     */
    public function delete($request)
    {
        $model = $this->_getModel();
        $pk = $model->getPk();
        $params = $this->_getParams($request);

        $where = [$pk => $params[$pk]];

        return $this->remove($model, $where) ?? $this->getErr('delete');
    }

    /**
     * 判断是否有参数限制，并转化request对象为参数数组
     * @param Request|array $request
     * @return array
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 14:17
     */
    protected function _getParams($request) : array
    {
        $_params = [];

        if (!empty( $a = $this->_getAllowParams())) {
            $_params = $request instanceof Request ? $request->only($a) : array_filter($request, function ($key) use ($a) {
                return in_array($key, $a);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $_params;
    }

    /**
     * 判断并自动转换单条数据，作为模型关联的替代品
     * @param array $data
     * @param Model $model
     * @return void
     * @throws Exception
     * @author jweiquan
     * @date: 2020/5/27
     * @time: 15:50
     */
    protected function _parseData(array &$data, Model $model)
    {
        try {
            $method_name = 'DataParser';
            $class = new \ReflectionClass($model);
            if ($class->hasMethod($method_name)) {
                $method = $class->getMethod($method_name);
                if ($method->isStatic()) { //模型类有 DataParser的静态方法，才调用自动转换
                    $data = call_user_func([$model, $method_name], $data);
                }
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 查询列表前回调生成where，并移除这个参数，作为范围查询、时间查询等复杂查询使用
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 16:05
     * @param array $param
     * @return array
     */
    protected function _onBeforeIndexWhere(array &$param) : array
    {
        return [];
    }


    /**
     * 返回失败信息判断
     * tag参数会传入埋点标签
     * @author jweiquan
     * @date: 2020/5/28
     * @time: 16:24
     * @param string $tag  'index'  'add'  'edit'  'delete'
     * @return ErrorResponse
     */
    protected function getErr(string $tag) : ErrorResponse
    {
        switch ($tag) { //TODO 子类填充业务逻辑
            default:
                return new ErrorResponse(00, '操作失败！');
        }
    }

    abstract public function _getModel() : Model;

    abstract protected function _getAllowParams() : array ;
}