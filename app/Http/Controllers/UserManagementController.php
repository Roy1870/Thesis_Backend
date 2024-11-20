<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Get all user information including profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserInfo(Request $request)
    {
        // Retrieve all users with their profiles using eager loading
        $users = User::with('profile')->get();

        // Format the response to return each user's information
        $usersInfo = $users->map(function ($user) {
            return [
                'id' => $user->id, // Add ID to handle deletion and updates
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->profile ? $user->profile->phone_number : null,
                'address' => $user->profile ? $user->profile->address : null,
                'user_type' => $user->profile ? $user->profile->user_type : null, // Include user type
            ];
        });

        // Return the users' data
        return response()->json($usersInfo);
    }

    /**
     * Delete a user by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request, $id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            // Return success response
            return response()->json(['message' => 'User deleted successfully.'], 200);
        } catch (\Exception $e) {
            // Return error response if the user is not found or deletion fails
            return response()->json(['message' => 'User not found or deletion failed.'], 400);
        }
    }

    /**
     * Update the user type (e.g., change to admin/user).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeUserType(Request $request, $userId)
{
    // Find the user profile associated with the user
    $userProfile = UserProfile::where('user_id', $userId)->first();

    // If the user profile doesn't exist, return an error
    if (!$userProfile) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Validate the new user type
    $validatedData = $request->validate([
        'user_type' => 'required|string|in:admin,user', // Assuming 'admin' and 'user' are the only valid types
    ]);

    // Update the user type in the user profile
    $userProfile->user_type = $validatedData['user_type'];
    $userProfile->save();

    return response()->json(['message' => 'User type updated successfully']);
}

    
}
