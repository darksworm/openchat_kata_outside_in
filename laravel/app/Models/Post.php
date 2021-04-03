<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

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
