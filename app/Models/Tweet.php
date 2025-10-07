<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tweet extends Model
{
    /** @use HasFactory<\Database\Factories\TweetFactory> */
    use HasFactory;

    protected $fillable = ['user_id','tweet','image_path'];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function liked()
    {
         return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function bookmark_users()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'tweet_id', 'user_id')->withTimestamps();
    }
}
