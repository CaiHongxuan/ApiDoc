<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19
 * Time: 10:46
 */

namespace App\Http\Controllers;


use App\Http\Traits\ApiTrait;

class ApiController extends Controller
{
    use ApiTrait;

    protected $pageNum = 15;
}