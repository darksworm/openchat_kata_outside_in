<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

/**
 * @mixin Eloquent
 * @property string user_id
 * @property string post_id
 * @property string text
 * @property Carbon created_at
 * @property Carbon updated_at
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
