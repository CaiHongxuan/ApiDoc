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
        'name', 'parent_id', 'parent_ids', 'pro_id', 'sort'
    ];

    /**
     * 该目录下的文档
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function docs()
    {
        return $this->hasMany(Document::class, 'cat_id', 'id');
    }

    /**
     * 所属项目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pro()
    {
        return $this->belongsTo(Project::class, 'pro_id', 'id');
    }

}