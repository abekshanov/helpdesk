<?php


namespace App\Classes\Services;


use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public static function getManagers(): Collection
    {
       return User::whereHas('roles', function ($query) {
           $query->where('name', config('helpdesk.roles.manager'));
       })->get();
    }

    public static function getUserById(Int $id): User
    {
        return User::find($id);
    }

    public static function isManager(Int $userId): Bool
    {
        if (User::find($userId)->roles()->where('name', config('helpdesk.roles.manager'))->count()){
            return true;
        }
        return false;
    }

    public static function isClient(Int $userId): Bool
    {
        if (User::find($userId)->roles()->where('name', config('helpdesk.roles.client'))->count()){
            return true;
        }
        return false;
    }

    public static function assignDefaultRole(User $user): Void
    {
        $roleId = Role::where('name', config('helpdesk.default.role'))->first()->id;
        $user->roles()->attach($roleId);
    }

}
