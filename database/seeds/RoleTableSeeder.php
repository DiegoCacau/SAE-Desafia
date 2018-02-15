<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
		$role_employee = new Role();
		$role_employee->name = 'user';
		$role_employee->description = 'A Normal User';
		$role_employee->save();
		
		$role_manager = new Role();
		$role_manager->name = 'admin';
		$role_manager->description = 'A Administrator User';
		$role_manager->save();

		$role_manager = new Role();
		$role_manager->name = 'superadmin';
		$role_manager->description = 'A Super-Administrator User';
		$role_manager->save();
	}
}
