<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class Usercontroller extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->latest()->get();

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

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

            Alert::success('Hore!', 'User created successfully!');
            return redirect()->route('users.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('users.index');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all()->pluck('name');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
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
            DB::transaction(function () use ($request, $user) {
                $user->name = $request->name;
                $user->email = $request->email;
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->nip = $request->nip;
                $user->position = $request->position;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->is_active = $request->is_active;
                $user->save();

                $user->syncRoles($request->role);
            });

            Alert::success('Hore!', 'User updated successfully!');
            return redirect()->route('users.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('users.index');
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                $user->delete();
            });
            Alert::success('Hore!', 'User deleted successfully!');
            return redirect()->route('users.index');
        } catch (\Throwable $th) {
                        return redirect()->route('users.index');
                        return redirect()->route('users.index');
        }
    }

    public function is_active(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        Alert::success('Hore!', 'Status changed successfully!');
        return redirect()->route('users.index');
    }
}

