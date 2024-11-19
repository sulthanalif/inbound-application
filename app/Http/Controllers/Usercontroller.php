<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Usercontroller extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->paginate(5);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all()->pluck('name');

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nip' => 'nullable|string',
            'role' => 'required|string',
            'position' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $newUser = new User();
                $newUser->name = $request->name;
                $newUser->email = $request->email;
                $newUser->password = Hash::make($request->password);
                $newUser->nip = $request->nip;
                $newUser->position = $request->position;
                $newUser->phone = $request->phone;
                $newUser->address = $request->address;
                $newUser->is_active = $request->is_active;
                $newUser->save();

                $newUser->assignRole($request->role);
            });

            return redirect()->route('users.index')->with('success', 'User created successfully!');
        } catch (\Throwable $th) {
            return redirect()->route('users.index')->with('error', $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                $user->delete();
            });
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Throwable $th) {
            return redirect()->route('users.index')->with('error', $th->getMessage());
        }
    }

    public function is_active(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Status changed successfully!');
    }
}
