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
    const NOT_FOUND_OF_DOCUMENT = 40002;

    const LACK_OF_PARAMETERS = 40101;

    public static $msg = [
        self::NOT_FOUND_OF_PROJECT  => '项目不存在',
        self::NOT_FOUND_OF_DOCUMENT => '文档不存在',

        self::LACK_OF_PARAMETERS    => '缺少参数',
    ];

}