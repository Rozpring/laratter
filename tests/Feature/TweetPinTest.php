<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a user can pin their own tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->post(route('tweets.pin', $tweet));

    $response->assertRedirect();
    $this->assertNotNull($tweet->refresh()->pinned_at);
});

test('a user can unpin their own tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create([
        'user_id' => $user->id,
        'pinned_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->delete(route('tweets.unpin', $tweet));

    $response->assertRedirect();
    $this->assertNull($tweet->refresh()->pinned_at);
});

test('a user cannot pin another user\'s tweet', function () {
    $tweetOwner = User::factory()->create();
    $anotherUser = User::factory()->create();
    $tweet = Tweet::factory()->create(['user_id' => $tweetOwner->id]);

    $response = $this
        ->actingAs($anotherUser)
        ->post(route('tweets.pin', $tweet));

    $response->assertForbidden();
    $this->assertNull($tweet->refresh()->pinned_at);
});

test('a guest cannot pin a tweet', function () {
    $tweet = Tweet::factory()->create();

    $response = $this->post(route('tweets.pin', $tweet));

    $response->assertRedirect(route('login'));
});

test('pinned tweets are displayed first on the tweets index page', function () {
    $user = User::factory()->create();
    $oldUnpinnedTweet = Tweet::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDay(),
    ]);
    $newPinnedTweet = Tweet::factory()->create([
        'user_id' => $user->id,
        'created_at' => now(),
        'pinned_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('tweets.index'));

    $response->assertOk();
    $response->assertSeeInOrder([
        $newPinnedTweet->tweet,
        $oldUnpinnedTweet->tweet,
    ]);
});