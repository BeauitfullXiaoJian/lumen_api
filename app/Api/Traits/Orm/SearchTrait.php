<?php

/**
 * @file:    SearchTrait.php
 * @author:  xiaojian
 * @date:    2017-12-16
 * @exp:     提供了模型搜索方法
 */
namespace App\Api\Traits\Orm;

trait SearchTrait
{
    //查询字段配置(示例)
    // private $search_params = [
    //    "goods_id"=>['where','='],    //goods_id商品id等于某个值
    //    "price"=>['where','>'],       //price价格大于某个值
    //    "name"=>['where','like'],     //name名称类似某个值
    //    "type"=>['whereIn']           //type类型属于某个区间
    // ];
    //后续操作配置
    //private $search_operations = [
    //   'created_at'=>['orderBy','desc'],//按created_at创建时间排序
    //]

    /**
     * @name   默认查询方法(分页)
     * @author xiaojian
     * @param  array[limit:此次查询获取的最大数据量，offset:此次查询的偏移数据量...],array[...查询字段，这是可选参数，如果有的话，会覆盖模型内的设置参数],array[...操作字段，这是可选参数，如果有会覆盖模型内置参数]
     * @return array[total:符合条件的数据条数，rows:此次查询获取的数据列表]
     * @todo   特殊查询需要定制
     */
    public function search($params, $search_params = null, $search_operations = null)
    {
        if ($search_params === null) {
            $search_params = isset($this->search_params) ? $this->search_params : [];
        }
        if ($search_operations === null) {
            $search_operations = isset($this->search_operations) ? $this->search_operations : [];
        }

        // 为了兼容，这里识别v2版本的参数，如果符合v2的参数，就使用v2方法
        if (count($search_params) > 0 && isset($search_params[0])) {
            return $this->searchV2($params, $search_params, $search_operations);
        }

        $sql = $this;
        $result = ['total' => 0, 'rows' => []];
        foreach ($search_params as $key => $judgment) {
            if (isset($params[$key]) && (!empty($params[$key] || $params[$key] == 0))) {
                $fnc = $judgment[0];
                $sql = count($judgment) === 1 ? $sql->$fnc($key, $params[$key]) : $sql->$fnc($key, $judgment[1], $params[$key]);
            } else if (!isset($params[$key]) && count($judgment) === 1) {
                $fnc = $judgment[0];
                // suport filter
                $filters = ['whereNull', 'whereNotNull'];
                if (in_array($fnc, $filters)) {
                    $sql = $sql->$fnc($key);
                }
            }
        }
        $result['total'] = $sql->count();
        if ($result['total'] > 0) {
            foreach ($search_operations as $key => $rule) {
                $fnc = $rule[0];
                $sql = count($rule) === 1 ? $sql->$fnc($key) : $sql->$fnc($key, $rule[1]);
            }
            $result['rows'] = $sql->skip($params['offset'])->take($params['limit'])->get();
        }
        return $result;
    }

    //查询字段配置(示例)
    
    // private $search_params = [
    //      ['where', 'vip_level', '>' ,4], //会员等级大于4
    //      ['where', 'nick', 'like', '$params-one'], // 昵称模糊匹配$params-one
    //      ['whereIn', 'id', '$params-two'], // id在$params-two中
    //      ['orderBy', 'vip_level', 'asc'] // 按从小到大的等排序
    // ];

    //后续操作配置
    //private $format_operations = [
    //   'params-one' => '%$params-one%', // 将$params中的params-one参数格式化为 '%'.$params['params-one'].'%'
    //   'params-two' => function ($param) {
    //        return explode(',', $param); // 将$params中的params-two参数用逗号分割成一个数组
    //   },
    //]

    public function searchV2($params, $search_params = null, $format_operations = null)
    {
        if ($search_params === null) {
            $search_params = isset($this->search_params) ? $this->search_params : [];
        }
        if ($format_operations === null) {
            $format_operations = isset($this->format_operations) ? $this->format_operations : [];
        }
        $sql = $this;
        $result = ['total' => 0, 'rows' => []];

        foreach ($search_params as $judgment) {
            $func = $judgment[0];
            unset($judgment[0]);
            $func_params = [];
            foreach ($judgment as $param) {
                if (gettype($param) === 'string') {
                    //使用了变量，那么变量存在就生效，不存在就不算入查询条件
                    if (substr($param, 0, 1) === '$') {
                        if (isset($params[substr($param, 1)])) {
                            $field = substr($param, 1);
                            // 使用了格式化操作就调用格式化操作
                            if (isset($format_operations[$field])) {
                                $format_type = gettype($format_operations[$field]);
                                // 格式化操作是一个方法，就调用这个方法
                                if ($format_type === 'object') {
                                    $format_func = $format_operations[$field];
                                    $func_params[] = call_user_func($format_func, $params[$field]);
                                }
                                // 格式化操作是一个串就按串的解析
                                if ($format_type === 'string') {
                                    $func_params[] = str_replace($param, $params[$field], $format_operations[$field]);
                                }

                            } else {
                                $func_params[] = $params[substr($param, 1)];
                            }
                        } else {
                            $func_params = [];
                            continue;
                        }
                    } else { // 使用常量字符串
                        $func_params[] = $param;
                    }
                } else { // 使用了其他常量
                    $func_params[] = $param;
                }
            }
            switch (count($func_params)) {
                case 1:
                    {
                        $sql = $sql->$func($func_params[0]);
                        break;
                    }
                case 2:
                    {
                        $sql = $sql->$func($func_params[0], $func_params[1]);
                        break;
                    }
                case 3:
                    {
                        $sql = $sql->$func($func_params[0], $func_params[1], $func_params[2]);
                        break;
                    }
                case 4:
                    {
                        $sql = $sql->$func($func_params[0], $func_params[1], $func_params[2], $func_params[3]);
                        break;
                    }
                default:
                    {
                        break;
                    }
            }
        }
        $result['total'] = $sql->count();
        if ($result['total'] > 0) {
            $result['rows'] = $sql->skip($params['offset'])->take($params['limit'])->get();
        }
        return $result;
    }
}
