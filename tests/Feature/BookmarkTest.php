<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;

class BookmarkTest extends TestCase
{
    use RefreshDatabase; 
    public function a_user_can_bookmark_a_tweet(): void
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create();

        $response = $this->actingAs($user)->post(route('tweets.bookmark', $tweet));
        $response->assertRedirect(); 
        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);
    }

    public function a_user_can_unbookmark_a_tweet(): void
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create();
        $user->bookmark_tweets()->attach($tweet->id);

        $response = $this->actingAs($user)->delete(route('tweets.unbookmark', $tweet));

        $response->assertRedirect(); 
        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);
    }

    public function a_guest_cannot_bookmark_a_tweet(): void
    {
        $tweet = Tweet::factory()->create();

        $response = $this->post(route('tweets.bookmark', $tweet));

        $response->assertRedirect('/login');
    }

    public function a_user_can_view_their_bookmarked_tweets(): void
    {
        $user = User::factory()->create();
        $bookmarkedTweet1 = Tweet::factory()->create(['tweet' => 'This is the first bookmarked tweet.']);
        $bookmarkedTweet2 = Tweet::factory()->create(['tweet' => 'This is the second bookmarked tweet.']);
        $notBookmarkedTweet = Tweet::factory()->create(['tweet' => 'This tweet is NOT bookmarked.']);

        $user->bookmark_tweets()->attach($bookmarkedTweet1->id);
        $user->bookmark_tweets()->attach($bookmarkedTweet2->id);

        $response = $this->actingAs($user)->get(route('bookmarks.index'));

        $response->assertStatus(200); 
        $response->assertSee('This is the first bookmarked tweet.'); 
        $response->assertSee('This is the second bookmarked tweet.'); 
        $response->assertDontSee('This tweet is NOT bookmarked.'); 
    }
}
