<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('role_user')->delete();

        $roleId = Role::where('name', config('helpdesk.roles.manager'))->first()->id;

        $user = User::create([
            'name' => 'Great Manager',
            'email' => config('helpdesk.initManagerEmail'),
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach($roleId);
    }
}
