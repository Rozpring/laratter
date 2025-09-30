<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweet::with(['user', 'liked'])->orderBy('pinned_at', 'desc')->latest()->get();
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'tweet' => 'required|max:255',
        ]);

        $request->user()->tweets()->create($request->only('tweet'));

        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'tweet' => 'required|max:255',
            ]);

            $tweet->update($request->only('tweet'));

            return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $tweet->delete();

        return redirect()->route('tweets.index');
    }

    public function search(Request $request)
    {

    $query = Tweet::query();

    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where('tweet', 'like', '%' . $keyword . '%');
    }

    $tweets = $query
        ->latest()
        ->paginate(10);

    return view('tweets.search', compact('tweets'));
    }

    public function pin(Tweet $tweet)
    {
        Gate::authorize('pin', $tweet);

        $tweet->pinned_at = now();
        $tweet->save();

        return back()->with('status', '投稿をピン留めしました。');
    }

    public function unpin(Tweet $tweet)
    {
        Gate::authorize('unpin', $tweet);

        $tweet->pinned_at = null;
        $tweet->save();

        return back()->with('status', '投稿のピン留めを解除しました。');
    }
}
