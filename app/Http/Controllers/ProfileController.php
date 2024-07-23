<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserProfileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->profile;
        if ($profile) {
            return new ProfileResource($profile);
        } else {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi request
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Pastikan foto di-upload
        ]);

        // Simpan foto
        $photoPath = $request->file('photo')->store('profiles', 'public');

        // Simpan atau update profil
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            ['photo' => $photoPath]
        );

        return new ProfileResource($profile);
    }


    public function update(Request $request)
    {
        // Validasi request
        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Mendapatkan instance model Eloquent
        $user = User::find(Auth::id());

        // Cek jika user ada
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update user attributes jika ada
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('no_telp')) {
            $user->no_telp = $request->no_telp;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password); // Hash password
        }

        // Simpan perubahan pada tabel users
        $user->save();

        // Update profile jika ada
        $profile = $user->profile;
        if ($profile) {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
                // Simpan foto baru
                $profile->photo = $request->file('photo')->store('profiles', 'public');
                $profile->save(); // Simpan perubahan pada tabel profiles
            }
        } else {
            // Jika profil belum ada, buat profil baru
            if ($request->hasFile('photo')) {
                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->photo = $request->file('photo')->store('profiles', 'public');
                $profile->save(); // Simpan profil baru
            }
        }

        // Mengembalikan data melalui resource
        return new UserProfileResource($user);
    }

    public function destroy()
    {
        $profile = Auth::user()->profile;
        if ($profile) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $profile->delete();
            return response()->json(['message' => 'Profile deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Profile not found'], 404);
        }
    }
}
