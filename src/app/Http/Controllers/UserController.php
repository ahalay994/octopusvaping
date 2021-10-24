<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use \Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * All news.
     *
     * @return mixed
     */
    public function store()
    {
        return Inertia::render('Admin/User/View', [
            'data' => self::getUsers()
        ]);
    }

    public function addUserView() {
        return Inertia::render('Admin/User/User/Add');
    }

    public function editUserView($id) {
        $user = User::where(['id' => $id])->first();

        return Inertia::render('Admin/User/User/Edit', [
            'data' => $user
        ]);
    }

    public function getUsers()
    {
        $usersDB = User::all();
        $users = [];
        $roles = Role::pluck('name', 'id');
        $userRoles = UserRole::pluck('role_id', 'user_id');

        foreach ($usersDB as $user) {
            array_push($users, [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'role' => $roles[$userRoles[$user['id']]],
                'role_id' => $userRoles[$user['id']],
            ]);
        }

        return $users;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addUser(Request $request) {
        $userRole = Role::where('slug','user')->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->roles()->attach($userRole);

        return redirect()->route('admin.user.view')->with('status', 'Пользователь добавлен');
    }

    /**
     * Edit news.
     *
     * @return
     */
    public function editUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where(['id' => $request->id])->first();
        $user->name = $request->name;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->update();

        return redirect()->route('admin.user.view')->with('status', 'Пользователь изменён');
    }

    /**
     * Delete news.
     *
     * @return false|\Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id) {
        try {
            if ($id !== 1) {
                return User::where(['id' => $id])->delete();
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception->errorInfo;
        }

        return false;
    }

    public function editRoleView($id) {
        $userRole = UserRole::where(['user_id' => $id])->first();
        $user = User::where(['id' => $userRole->user_id])->first();
        $rolesDB = Role::all();
        $roles = [];
        foreach ($rolesDB as $role) {
            array_push($roles, [
                'value' => $role['id'],
                'label' => $role['name'],
            ]);
        }

        return Inertia::render('Admin/User/Role/Edit', [
            'data' => [
                'user' => $user,
                'role' => $userRole->role_id,
                'roles' => $roles
            ]
        ]);
    }

    public function editRole(\Illuminate\Http\Request $request) {
        $userRole = UserRole::where(['user_id' => $request->user['id']])->first();
        $userRole->role_id = $request->role;
        $userRole->update();

        return redirect()->route('admin.user.view')->with('status', 'Роль изменена');
    }

    public function getPermissions()
    {
        return Permission::all();
    }
}
