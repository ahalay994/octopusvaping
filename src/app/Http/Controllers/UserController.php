<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Boolean;

class UserController extends Controller
{
    /**
     * All news.
     *
     * @return mixed
     */
    public function store()
    {
        $user = User::all();
        return Inertia::render('Admin/User/View', [
            'data' => $user
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
        return User::all();
    }

    public function addUser(\Illuminate\Http\Request $request) {
        $userRole = Role::where('slug','user')->first();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $user->roles()->attach($userRole);

        return redirect()->route('admin.user.view')->with('status', 'Пользователь добавлен');
    }

    /**
     * Edit news.
     *
     * @return
     */
    public function editUser(\Illuminate\Http\Request $request) {
        $user = User::where(['id' => $request->id]);
        $user->name = $request->name;
        $user->password = $request->password;
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

    public function editPermissionView($id) {
        $userPermission = UserPermission::where(['user_id' => $id])->all();

        return Inertia::render('Admin/User/Permission/Edit', [
            'data' => $userPermission
        ]);
    }

    public function editPermission(\Illuminate\Http\Request $request) {

    }

    public function getPermissions()
    {
        return Permission::all();
    }
}
