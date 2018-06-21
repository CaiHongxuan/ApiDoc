<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/21
 * Time: 21:55
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{

    protected $fillable = [
        'name', 'parent_id', 'pro_id'
    ];

}