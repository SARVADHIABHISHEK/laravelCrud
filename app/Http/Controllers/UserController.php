<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Assuming you have a User model

class UserController extends Controller
{
    public function showusers()
    {

        $users = User::latest()->paginate(10);
        return view('users.users_list', compact('users'));
    }

    public function create(Request $request)
    {




        $rules = [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
         ];


        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->back()
               ->withErrors($validator)
               ->withInput();
        }


        if ($request->mode === 'edit') {
            $user = User::findOrFail($request->id);
            $message = 'User updated successfully!';
        } else {
            $user = new User();
            $message = 'User created successfully!';
        }

        $user->name = $request->name;
        $user->email = $request->email;



        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }


        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('users.showusers')
            ->with('success', $message);
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user_edit', compact('user'));
    }


    public function delete($id)
    {
        $user = User::findorFail($id);

        if ($user) {
            // Delete the user's image if it exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
                Storage::delete($user->image);
            }

            $user->delete();
            return redirect()->route('users.showusers')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users.showusers')->with('error', 'User not found.');
        }
    }
}
