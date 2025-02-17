<?php

namespace App\Models;

use App\Jobs\AddTweetsToFriendsTweetsListJob;
use App\Jobs\DeleteTweetsFriendRemovedJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'following_id', 'status'];

    public function following()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($flollowing) {
            dispatch(new AddTweetsToFriendsTweetsListJob($flollowing));
        });

        static::deleting(function ($flollowing) {
            $data = $flollowing;
            dispatch(new DeleteTweetsFriendRemovedJob($data->user_id, $data->following_id));
        });
    }
}
