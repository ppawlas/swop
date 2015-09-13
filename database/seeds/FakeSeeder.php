<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use App\User;
use App\Organization;
use App\Role;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('users')->delete();
        DB::table('organizations')->delete();
        DB::table('roles')->delete();

        $roles = array(
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'manager', 'display_name' => 'Manager'],
            ['name' => 'employee', 'display_name' => 'Employee'],
        );

        foreach($roles as $role) {
            Role::create($role);
        }

        $faker = Faker::create();
        foreach(range(1, 10) as $index) {
            Organization::create([
                'code' => $faker->randomNumber(6),
                'name' => $faker->company,
                'active' => $faker->boolean()
            ]);
        }

        foreach(range(1, 100) as $index) {
            $organization = Organization::all()->random(1);

            $user = User::create([
                'login' => $organization->code . '_' . $faker->userName,
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->email,
                'password' => Hash::make('secret'),
                'active' => $faker->boolean(),
                'organization_id' => $organization->id
            ]);

            $role = Role::all()->random(1);
            $user->attachRole($role);
        }


        Model::reguard();
    }
}
