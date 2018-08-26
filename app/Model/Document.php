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
     * 是否必填
     */
    const NO_MUST = 0; // 非必填
    const IS_MUST = 1; // 必填

    public static $must = [
        self::NO_MUST => '非必填',
        self::IS_MUST => '必填'
    ];

    /**
     * 参数类型
     */
    public static $para_type = [
        0 => '字符串', // 'string'
        1 => '整形', // 'int'
        2 => '数量类型', // 'number'
        3 => '数组', // 'array'
        4 => 'JSON类型', // 'json'
        5 => '任意类型', // 'any'
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
     * 所属项目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pro()
    {
        return $this->belongsTo(Project::class, 'pro_id', 'id');
    }

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

    /**
     * 根据条件获取文档列表
     * @param        $where
     * @param array  $field
     * @param string $order_by
     * @param int    $is_asc
     * @return mixed
     */
    public function getDocs($where, $field=['*'], $order_by='id', $is_asc=1)
    {
        $is_asc = $is_asc ? 'ASC' : 'DESC';
        $docs = $this
            ->where($where)
            ->orderBy($order_by, $is_asc)
            ->orderBy('id', $is_asc)
            ->get($field)
            ->toArray();

        return $docs;
    }

}