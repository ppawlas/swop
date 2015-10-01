<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use App\Report;
use App\Result;
use Carbon\Carbon;

class FakeResultsSeeder extends Seeder
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

        $faker = Faker::create();

        foreach(Report::all() as $report) {

            $coefficients = array();
            $organizationIndicators = $report->owner->organization->indicators;
            foreach($organizationIndicators as $organizationIndicator) {
                $coefficients[$organizationIndicator->id] = $organizationIndicator->pivot->coefficient;
            }

            foreach($report->users as $user) {
                foreach($report->indicators as $indicator) {
                    $value = ($indicator->type == 'value') ? $faker->randomNumber(3) : $faker->randomFloat(3, 0, 1);
                    $points = $value * $coefficients[$indicator->id];

                    Result::create([
                        'report_id' => $report->id,
                        'user_id' => $user->id,
                        'indicator_id' => $indicator->id,
                        'value' => $value,
                        'points' => $points
                    ]);
                }
                $report->evaluated_at = Carbon::now();
                $report->save();
            }
        }

        Model::reguard();
    }
}
