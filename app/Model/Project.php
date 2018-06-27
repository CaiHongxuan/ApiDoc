<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/21
 * Time: 21:53
 */

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'name', 'desc', 'icon', 'created_by', 'sort'
    ];

    /**
     * 拥有的目录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cats()
    {
        return $this->hasMany(Catalog::class, 'pro_id', 'id');
    }

    /**
     * 所属创建者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}