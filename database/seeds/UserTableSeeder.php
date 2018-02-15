<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
	    $role_superadmin  = Role::where('name', 'superadmin')->first();
	    

	    $admin = new User();
	    $admin->name = 'admin';
	    $admin->email = 'admin@admin.com';  #change this to real email
	    $admin->password = bcrypt('admin'); #change this to a strong password
	    $admin->save();
		$admin->roles()->attach($role_admin);

	    $superadmin = new User();
	    $superadmin->name = 'superadmin';
	    $superadmin->email = 'superadmin@superadmin.com'; #change this to real email
	    $superadmin->password = bcrypt('superadmin'); #change this to a strong password
	    $superadmin->save();
	    $superadmin->roles()->attach($role_superadmin);
    }
}
