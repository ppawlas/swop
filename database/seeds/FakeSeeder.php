<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use App\User;
use App\Organization;
use App\Role;
use App\Indicator;
use App\Group;
use App\Report;

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

        DB::table('results')->delete();
        DB::table('group_indicator')->delete();
        DB::table('group_user')->delete();
        DB::table('indicator_organization')->delete();
        DB::table('indicator_report')->delete();
        DB::table('report_user')->delete();
        DB::table('users')->delete();
        DB::table('organizations')->delete();
        DB::table('roles')->delete();
        DB::table('indicators')->delete();
        DB::table('groups')->delete();
        DB::table('reports')->delete();

        $roles = array(
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'manager', 'display_name' => 'Manager'],
            ['name' => 'employee', 'display_name' => 'Employee'],
        );

        foreach($roles as $role) {
            Role::create($role);
        }

        $faker = Faker::create();


        foreach(range(1, 5) as $index) {
            Indicator::create([
                'name' => $faker->sentence(3),
                'function_name' => $faker->numerify($faker->sentence(3) . '###'),
                'type' => $faker->randomElement(['value', 'ratio']),
                'default_coefficient' => $faker->randomFloat(3, 0, 1),
                'description' => $faker->text()
            ]);
        }

        foreach(range(1, 5) as $index) {
            $organization = Organization::create([
                'code' => $faker->randomNumber(6),
                'name' => $faker->company,
                'active' => $faker->boolean()
            ]);

            foreach(Indicator::all() as $indicator) {
                $organization->indicators()->attach($indicator->id, ['coefficient' => $faker->randomFloat(3, 0, 10)]);
            }

        }

        foreach(range(1, 100) as $index) {
            $organization = Organization::all()->random(1);

            $user = User::create([
                'login' => $organization->code . '_' . $faker->userName,
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'password' => Hash::make('secret'),
                'active' => $faker->boolean(),
                'organization_id' => $organization->id
            ]);

            $role = Role::all()->random(1);
            $user->attachRole($role);
        }

        foreach(Organization::all() as $organization) {
            foreach(range(1, rand(5, 10)) as $index) {
                $group = Group::create([
                    'organization_id' => $organization->id,
                    'name' => $faker->sentence(3)
                ]);

                $users = $organization->users()->orderBy(DB::raw('random()'))->take(rand(5,10))->get();
                foreach($users as $user) {
                    $group->users()->attach($user->id);
                }

                $indicators = Indicator::all()->random(rand(2,5));
                foreach ($indicators as $indicator) {
                    $group->indicators()->attach($indicator->id);
                }
            }
        }

        $managers = User::whereHas('roles', function($role) {
            $role->where('name', 'manager');
        })->get();

        foreach($managers as $manager) {
            foreach(range(1, rand(3, 6)) as $index) {
                $endDate = $faker->date('Y-m-d');
                $report = Report::create([
                    'owner_id' => $manager->id,
                    'name' => $faker->sentence(4),
                    'start_date' => $faker->date('Y-m-d', $endDate),
                    'end_date' => $endDate
                ]);

                $indicators = Indicator::all()->random(rand(2,5));
                foreach ($indicators as $indicator) {
                    $report->indicators()->attach($indicator->id, ['show_value' => $faker->boolean(), 'show_points' => $faker->boolean()]);
                }

                $users = $manager->organization->users()->orderBy(DB::raw('random()'))->take(rand(5,10))->get();
                foreach($users as $user) {
                    $report->users()->attach($user->id, ['view_self' => $faker->boolean(), 'view_all' => $faker->boolean()]);
                }
            }
        }


        Model::reguard();
    }
}
