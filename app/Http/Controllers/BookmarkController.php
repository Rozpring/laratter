<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function store(Tweet $tweet)
    {
        Auth::user()->bookmark_tweets()->attach($tweet->id);
        return back()->with('success', 'ブックマークしました。'); 
    }

    public function destroy(Tweet $tweet)
    {
        Auth::user()->bookmark_tweets()->detach($tweet->id);
        return back()->with('success', 'ブックマークを解除しました。'); 
    }

    public function index()
    {
        $tweets = Auth::user()->bookmark_tweets()->latest()->paginate(10);

        return view('bookmarks.index', compact('tweets'));
    }
}
