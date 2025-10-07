<?php
use App\Models\User;
use App\Models\Tweet;
use function Pest\Laravel\{actingAs, post, get, delete};
use Illuminate\Foundation\Testing\RefreshDatabase;

it('allows authenticated users to bookmark a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();
    $response = actingAs($user)->post(route('tweets.bookmark', $tweet));
    $response->assertRedirect();
    $this->assertDatabaseHas('bookmarks', [
        'user_id' => $user->id,
        'tweet_id' => $tweet->id,
    ]);
});

it('allows authenticated users to unbookmark a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();
    $user->bookmark_tweets()->attach($tweet->id);
    $response = actingAs($user)->delete(route('tweets.unbookmark', $tweet));
    $response->assertRedirect();
    $this->assertDatabaseMissing('bookmarks', [
        'user_id' => $user->id,
        'tweet_id' => $tweet->id,
    ]);
});

it('prevents guests from bookmarking a tweet', function () {
    $tweet = Tweet::factory()->create();
    $response = post(route('tweets.bookmark', $tweet));
    $response->assertRedirect('/login');
});

it('displays the bookmarked tweets list page for a user', function () {
    $user = User::factory()->create();
    $bookmarkedTweet = Tweet::factory()->create(['tweet' => 'This is a bookmarked tweet']);
    $notBookmarkedTweet = Tweet::factory()->create(['tweet' => 'This is a normal tweet']);

    $user->bookmark_tweets()->attach($bookmarkedTweet->id);

    $response = actingAs($user)->get(route('bookmarks.index'));
    $response->assertOk(); 
    $response->assertViewIs('bookmarks.index'); 
    $response->assertSee($bookmarkedTweet->tweet); 
    $response->assertDontSee($notBookmarkedTweet->tweet); 
});