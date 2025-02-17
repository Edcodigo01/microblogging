<?php

namespace App\Models;

use App\Jobs\DeleteAllTweetsFriendRemovedJob;
use App\Jobs\DistributeTweetFollowingJob;
use App\Jobs\TweetUpdateFollowingJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'content'];

    protected static function booted()
    {
        static::created(function ($tweet) {
            dispatch(new DistributeTweetFollowingJob($tweet));
        });

        static::updated(function ($tweet) {
            dispatch(new TweetUpdateFollowingJob($tweet));
        });

        static::deleting(function ($tweet) {
            dispatch(new DeleteAllTweetsFriendRemovedJob($tweet->id));
        });
    }
}
