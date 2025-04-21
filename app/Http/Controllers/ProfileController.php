<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


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
    /*public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }*/
    public function update(Request $request)
    {
        Log::info("Request data:", $request->all());
        
        $user = auth()->user();
    
        $validated = $request->validate([
            'email' => 'sometimes|nullable|string',
            'mobileNum' => 'sometimes|nullable|string',
            'address' => 'sometimes|nullable|string',
            'pass' => 'required|string',
        ]);

        if (!Hash::check($validated['pass'], $user->password)) {
            return response()->json(['error' => 'The provided password does not match your current password.'], 422);
        }
    
        // Remove 'pass' and filter out null values
        $fieldsToUpdate = array_filter($validated, function ($value, $key) {
            return $key !== 'pass' && $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    
        $user->fill($fieldsToUpdate)->save();
    
        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
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
}
