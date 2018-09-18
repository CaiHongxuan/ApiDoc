<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22
 * Time: 18:04
 */

namespace App\Lib\Code;


class ApiCode
{

    const NOT_FOUND_OF_PROJECT = 40001;
    const NOT_FOUND_OF_CATALOG = 40002;
    const NOT_FOUND_OF_DOCUMENT = 40003;

    const DELETE_ERROR_EXIST_CAT = 40101;
    const DELETE_ERROR_EXIST_DOC = 40102;

    const LACK_OF_PARAMETERS = 40901;

    public static $msg = [
        self::NOT_FOUND_OF_PROJECT   => '项目不存在',
        self::NOT_FOUND_OF_CATALOG   => '目录不存在',
        self::NOT_FOUND_OF_DOCUMENT  => '文档不存在',

        self::DELETE_ERROR_EXIST_CAT => '该目录下存在子目录，暂不能删除',
        self::DELETE_ERROR_EXIST_DOC => '该目录下存在文档，暂不能删除',

        self::LACK_OF_PARAMETERS     => '缺少参数',
    ];

}