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

class DemoSeeder extends Seeder
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

        $faker = Faker::create('pl_PL');

        $indicators = array(
            [
                'name' => 'Wydane skierowania',
                'function_name' => 'wyd_skier_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba skierowań wydanych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Obsłużone wizyty',
                'function_name' => 'obsluz_wiz_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba wizyt obsłużonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Udzielone ind. porady zawodowe',
                'function_name' => 'ind_por_zaw_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba indywidualnych porad zawodowych udzielonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Udzielone grupowe porady zawodowe',
                'function_name' => 'grup_por_zaw_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba grupowych porad zawodowych udzielonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Udzielone indywidualne inf. zawodowe',
                'function_name' => 'ind_inf_zaw_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba indywidualnych informacji zawodowych udzielonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Zorganizowane grupowe inf. zawodowe',
                'function_name' => 'grup_inf_zaw_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba grupowych informacji zawodowych zorganizowanych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Badania kwest. do profilowania',
                'function_name' => 'bad_kwest_prof_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba badań kwestionariuszem do profilowania przeprowadzonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Sporządzone IPD',
                'function_name' => 'sporz_ipd_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba indywidualnych planów działania sporządzonych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Zarejestrowani kontrahenci',
                'function_name' => 'zarej_kontr_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba kontrahentów zarejestrowanych przez pracownika w badanym okresie.'
            ],
            [
                'name' => 'Kontakty z kontrahentami',
                'function_name' => 'kont_kontr_001',
                'type' => 'value',
                'default_coefficient' => 1,
                'description' => 'Liczba kontaktów z kontrahentami zrealizowanych przez pracownika w badanym okresie.'
            ]
        );

        foreach($indicators as $indicator) {
            Indicator::create($indicator);
        }

        $organizations = array(
            ['code' => 'demo', 'name' => 'Powiatowy Urząd Pracy Demo', 'active' => true],
            ['code' => '24780', 'name' => 'Powiatowy Urząd Pracy w Zabrzu', 'active' => true]
        );

        foreach($organizations as $organization) {
            $newOrganization = Organization::create($organization);

            foreach(Indicator::all() as $indicator) {
                $newOrganization->indicators()->attach($indicator->id, ['coefficient' => $faker->randomFloat(3, 0, 10)]);
            }

        }

        foreach(range(1, 50) as $index) {
            $organization = Organization::all()->random(1);

            $gender = $faker->randomElement(['male', 'female']);
            $lastName = $faker->lastName($gender);

            $user = User::create([
                'login' => $organization->code . '_' . $faker->numerify($lastName . '###'),
                'name' => $faker->firstName($gender),
                'surname' => $lastName,
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
                    'name' => $faker->numerify('Grupa testowa ####')
                ]);

                $users = $organization->users()->orderBy(DB::raw('random()'))->take(rand(5,10))->get();
                foreach($users as $user) {
                    $group->users()->attach($user->id);
                }

                $indicators = Indicator::all()->random(rand(3,7));
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
                $endDate = $faker->dateTimeThisDecade();
                $report = Report::create([
                    'owner_id' => $manager->id,
                    'name' => $faker->numerify('Raport testowy ####'),
                    'start_date' => $faker->dateTimeThisDecade($endDate),
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
