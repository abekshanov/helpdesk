<?php

namespace App;

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
}
