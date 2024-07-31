<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserProfileResource;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with('profile')->find($id);

        if ($user) {
            return new UserProfileResource($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
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


    public function update(Request $request, $id)
    {
        Log::info('Update method called for user ID: ' . $id);

        // Cari user berdasarkan id yang diteruskan ke fungsi
        $user = User::findOrFail($id);
        Log::info('User before update: ', $user->toArray());

        // Validasi request
        $validator = Validator::make($request->all(), [
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        Log::info('Validated data: ', $validatedData);

        // Hanya update field yang ada di input
        $user->fill(array_filter([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => $request->password ? bcrypt($request->password) : null,
        ]));

        $userSaved = $user->isDirty() ? $user->save() : false;
        Log::info('User updated: ', ['success' => $userSaved, 'data' => $user->toArray()]);

        // Update profile jika ada
        $profile = $user->profile;
        if ($profile) {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                    Log::info('Old photo deleted: ' . $profile->photo);
                }
                // Simpan foto baru
                $profile->photo = $request->file('photo')->store('profiles', 'public');
                $profileSaved = $profile->save();
                Log::info('Profile updated: ', ['success' => $profileSaved, 'data' => $profile->toArray()]);
            }
        } else {
            // Jika profil belum ada, buat profil baru
            if ($request->hasFile('photo')) {
                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->photo = $request->file('photo')->store('profiles', 'public');
                $profileSaved = $profile->save();
                Log::info('Profile created: ', ['success' => $profileSaved, 'data' => $profile->toArray()]);
            }
        }
        
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
