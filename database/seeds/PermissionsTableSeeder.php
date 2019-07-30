<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'create-application',
            'view-all-merchant-info',
            'edit-merchant-info',
            'create-new-merchant',
            'change-terms',
            'create-application',
            'save-application',
            'submit-application'
        ];

        foreach($permissions as $perm) {

            App\Permission::create(['name' => $perm]);

        } 
    }
}
