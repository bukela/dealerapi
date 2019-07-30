<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role_admin = new Role();
        $role_admin->roleid = 1;
        $role_admin->name = 'Super User';
        $role_admin->code = 'super_user';
        $role_admin->save();
        
        $role_donator = new Role();
        $role_donator->roleid = 2;
        $role_donator->name = 'Broker';
        $role_donator->code = 'broker';
        $role_donator->save();
    
        $role_organization = new Role();
        $role_organization->roleid = 3;
        $role_organization->name = 'Credit Analyst';
        $role_organization->code = 'credit_analyst';
        $role_organization->save();

        $role_user = new Role();
        $role_user->roleid = 4;
        $role_user->name = 'Merchant';
        $role_user->code = 'merchant';
        $role_user->save();

        $role_user = new Role();
        $role_user->roleid = 5;
        $role_user->name = 'Analyst';
        $role_user->code = 'analyst';
        $role_user->save();
    
    }
}
