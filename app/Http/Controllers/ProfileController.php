<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request, User $user)
    {
        // return response()->json([
        //     'data_update' => $request->all(),
        //     'user' => $user
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company' => 'required|string',
            'nip' => 'nullable|string',
            'position' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Oops!', 'Please fill all the required fields');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->company = $request->company;
                $user->nip = $request->nip;
                $user->position = $request->position;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->save();
            });

            Alert::success('Hore!', 'Profile updated successfully!');
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error('Oops!', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'newpassword' => 'required|min:6',
            'renewpassword' => 'required|min:6',
        ]);

        if($validator->fails()) {
            Alert::error('Oops!', 'Please fill all the required fields');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->newpassword != $request->renewpassword) {
            Alert::error('Oops!', 'New password and re-new password not match');
            return redirect()->back();
        }

        if (!Hash::check($request->password, $user->password)) {
            Alert::error('Oops!', 'Wrong password');
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($request, $user) {
                    $user->password = Hash::make($request->newpassword);
                    $user->save();
                    Alert::success('Hore!', 'Password updated successfully!');
                    return redirect()->back();
            });

            Alert::success('Hore!', 'Password updated successfully!');
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error('Oops!', 'Something went wrong');
            return redirect()->back();
        }
    }
}
