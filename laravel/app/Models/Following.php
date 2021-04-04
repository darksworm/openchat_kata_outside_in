<?php


namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Eloquent
 * @property string follower_id - WHO follows.
 * @property string followee_id - WHO is followed.
 */
class Following extends Pivot
{
    protected $table = 'following';
    public $timestamps = false;

    protected $fillable = [
        'follower_id',
        'followee_id'
    ];

    public function followee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followee_id', 'user_id');
    }
}
