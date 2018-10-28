<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/23
 * Time: 1:26
 */

if (!function_exists('list_to_tree')) {

    /**
     * 将数组转化为树形结构
     * @param $lists [待处理的数组]
     * @param integer $id [根的parent_id值]
     * @param string $key_id [参考id名]
     * @param string $parent_id [上级id名]
     * @param string $children {子级名称]
     * @return mixed
     */
    function list_to_tree($lists, $id = 0, $key_id = 'id', $parent_id = 'parent_id', $children = 'children')
    {
        $results = [];
        foreach ($lists as $key => $value) {
            if ($value[$parent_id] == $id) {
                unset($lists[$key]);
                $value[$children] = list_to_tree($lists, $value[$key_id], $key_id, $parent_id, $children);
                if (!$value[$children]) {
                    unset($value[$children]);
                }
                $results[] = $value;
            }
        }
        return $results;
    }
}

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

