<?php


namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Eloquent
 */
class Following extends Pivot
{
    protected $table = 'following';
    public $timestamps = false;

    protected $fillable = [
        'follower_id',
        'followee_id'
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'follower_id');
    }

    public function followee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'followee_id');
    }
}