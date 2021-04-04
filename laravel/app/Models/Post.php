<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

/**
 * @mixin Eloquent
 */
class Post extends Model
{
    use HasUuid;

    protected $table = 'post';
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'text',
        'user_id'
    ];
}
