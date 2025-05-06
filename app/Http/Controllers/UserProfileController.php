<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Show the user profile data.
     */
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }
        
        // Eager load the user's profile
        $user->load('profile'); // Ensure the profile is loaded

        // If the user doesn't have a profile, create a basic one
        if (!$user->profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->save();
            $user->refresh(); // Refresh the user to include the new profile
        } else {
            $profile = $user->profile;
        }

        // Return user and profile data
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Store or update the user profile.
     */
    public function storeOrUpdate(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        // Validate the input
        $validationRules = [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $request->validate($validationRules);

        // Update user fields if provided
        $userUpdated = false;
        
        if ($request->has('name') && $request->name !== null) {
            $user->name = $request->name;
            $userUpdated = true;
        }
        
        if ($request->has('email') && $request->email !== null) {
            $user->email = $request->email;
            $userUpdated = true;
        }
        
        if ($request->has('password') && $request->password !== null) {
            $user->password = Hash::make($request->password);
            $userUpdated = true;
        }
        
        if ($userUpdated) {
            $user->save();
        }

        // Check if the user already has a profile
        $profile = $user->profile;

        // If not, create a new profile
        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($profile->profile_picture && Storage::exists('public/' . $profile->profile_picture)) {
                Storage::delete('public/' . $profile->profile_picture);
            }

            // Store new profile picture and get the path
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            Log::info("Profile picture stored at: " . $path); // Debugging log
            $profile->profile_picture = $path;
            $profile->save();
        }

        // Return response
        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'profile' => [
                'profile_picture' => $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : null,
            ],
        ]);
    }
}