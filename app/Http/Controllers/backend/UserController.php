<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{
    public function profileUser(){
        // Get the currently authenticated user as a single model instance
        $user = Auth::user();

        // If not authenticated, redirect to login (or handle as you prefer)
        if (!$user) {
            return redirect()->route('login');
        }

        // Pass a single $user to the view
        return view('users.profile', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            $path = public_path('uploads/avatars');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file->move($path, $filename);

            // Correct column name is photo_user
            $user->photo_user = 'uploads/avatars/' . $filename;
            /** @var \App\Models\User $user */
            $user->save();

            return response()->json([
                'success' => true,
                'avatar_url' => asset($user->photo_user)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }
}
