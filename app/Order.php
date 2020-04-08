<?php

namespace App;

use App\Classes\Filters\OrderScope;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'file_link',
        'author_id',
        'assignee_id',
        'parent_id',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

}
