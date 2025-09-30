<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->bio = $request->input('bio');

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $request->user()->profile_image = $path;
        }

        if ($request->hasFile('header_image')) {
            $path = $request->file('header_image')->store('header_images', 'public');
            $request->user()->header_image = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

        /**
     * Display the specified resource.
     */
    public function show(User $user)
  {
    if (auth()->user()->is($user)) {
      $tweets = Tweet::query()
        ->where('user_id', $user->id) 
        ->orWhereIn('user_id', $user->follows->pluck('id')) 
        ->orderBy('pinned_at', 'desc')
        ->latest()
        ->paginate(10);
    } else {
      $tweets = $user
        ->tweets()
        ->orderBy('pinned_at', 'desc')
        ->latest()
        ->paginate(10);
    }
    $user->load(['follows', 'followers']);

    return view('profile.show', compact('user', 'tweets'));
  }

}
