<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/21
 * Time: 21:56
 */

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $guarded = [];

    /**
     * 文档类型
     */
    const API_DOC = 1;
    const GENERAL_DOC = 2;

    public static $type_of_doc = [
        self::API_DOC     => '接口文档',
        self::GENERAL_DOC => '普通文档'
    ];

    /**
     * 请求方式
     */
    const METHOD_OF_ANY = 0;
    const METHOD_OF_GET = 1;
    const METHOD_OF_POST = 2;
    const METHOD_OF_PUT = 3;
    const METHOD_OF_PATCH = 4;
    const METHOD_OF_DELETE = 5;
    const METHOD_OF_OPTIONS = 6;

    public static $type_of_method = [
        self::METHOD_OF_ANY     => 'ANY',
        self::METHOD_OF_GET     => 'GET',
        self::METHOD_OF_POST    => 'POST',
        self::METHOD_OF_PUT     => 'PUT',
        self::METHOD_OF_PATCH   => 'PATCH',
        self::METHOD_OF_DELETE  => 'DELETE',
        self::METHOD_OF_OPTIONS => 'OPTIONS',
    ];

    /**
     * 开发状态
     */
    const STATUS_OF_DEV = 0;
    const STATUS_OF_FINISH = 1;
    const STATUS_OF_ABANDON = 2;

    public static $type_of_status = [
        self::STATUS_OF_DEV     => '开发中',
        self::STATUS_OF_FINISH  => '已完成',
        self::STATUS_OF_ABANDON => '已废弃'
    ];

    /**
     * 所属目录
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cat()
    {
        return $this->belongsTo(Catalog::class, 'cat_id', 'id');
    }

    /**
     * 所属创建者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * 所属修改者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}