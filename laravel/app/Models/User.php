<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

/**
 * @mixin Eloquent
 * @property string user_id
 * @property string username
 * @property string password
 * @property string about
 */
class User extends Model
{
    use HasUuid;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'username',
        'password',
        'about'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function followees()
    {
        return $this->hasMany(self::class, 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(self::class, 'followee_id', 'user_id');
    }
}
