<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\UserPreference;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {

        $categories = Category::all();
        $preferences = UserPreference::where('user_id', Auth::id())->select('id', 'cat_id')->get();
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'preferences' => $preferences,
            'categories' => $categories,
        ]);
    }

    /**
     * Add new preference
     */

    public function addUserPreference(Request $request, $id)
    {
        // $validated = $request->validate([
        //     'id' => 'required',
        // ]);

        $exists = UserPreference::where('cat_id', $id)->exists();

        if (!$exists) {
            $user = Auth::user();
            $preference = new UserPreference();
            $preference->user_id = $user->id;
            $preference->cat_id = $id;

            $preference->save();
        }
        return Redirect::route('profile.edit');
    }

    /**
     * Delete user Preference
     */

    public function deleteUserPreference($id)
    {
        $preference = UserPreference::where('id', $id)->first();

        if ($preference) {
            $preference->delete();
        }


        return Redirect::route('profile.edit');
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

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
