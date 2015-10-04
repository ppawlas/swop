<?php

namespace App\Http\Controllers;

use App\Report;
use App\Result;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateReportRequest;
use App\Repositories\ExcelRepository;
use App\Repositories\ReportRepository;

use Faker\Factory as Faker;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function managerIndex()
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        // Retrieve all the report defined for the organization of the currently authenticated manager
        return Auth::user()->myReports;
    }

    public function store(CreateReportRequest $request)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input) {
            $report = new Report();
            $report->name = $input['name'];
            $report->start_date = $input['start_date'];
            $report->end_date = $input['end_date'];

            $report->owner()->associate(Auth::user());

            $report->save();

            foreach($input['users'] as $user) {
                $report->users()->attach(
                    $user['id'], [
                        'view_self' => $user['pivot']['view_self'],
                        'view_all' => $user['pivot']['view_all']
                    ]
                );
            }

            foreach($input['indicators'] as $indicator) {
                $report->indicators()->attach(
                    $indicator['id'], [
                        'show_value' => $indicator['pivot']['show_value'],
                        'show_points' => $indicator['pivot']['show_points'],
                    ]
                );
            }

            return $report;
        });

        return $result;
    }

    public function show($id)
    {
        $report = Report::with('users', 'indicators')->find($id);

        $this->authorize($report);

        return $report;
    }

    public function results(ReportRepository $reportRepository, $id)
    {
        $report = Report::with('users', 'indicators')->find($id);

        $this->authorize($report);

        $results = $reportRepository->getResultsTable($report);

        return $results;
    }

    public function reset($id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $result = DB::transaction(function($id) use($id) {
            $report = Report::find($id);

            if ($report) {

                if ($report->evaluated_at) {
                    Result::whereHas('report', function($report) use($id) {
                        $report->where('id', $id);
                    })->delete();
                    $report->evaluated_at = null;
                }

                $report->save();
            }

            return $report;
        });

        return $result;
    }

    public function evaluate($id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $result = DB::transaction(function($id) use($id) {
            $report = Report::find($id);

            if ($report) {

                if ($report->evaluated_at) {
                    Result::whereHas('report', function($report) use($id) {
                        $report->where('id', $id);
                    })->delete();
                    $report->evaluated_at = null;
                }

                $coefficients = array();
                $organizationIndicators = $report->owner->organization->indicators;
                foreach($organizationIndicators as $organizationIndicator) {
                    $coefficients[$organizationIndicator->id] = $organizationIndicator->pivot->coefficient;
                }

                $faker = Faker::create();

                foreach($report->users as $user) {
                    foreach($report->indicators as $indicator) {
                        $value = ($indicator->type == 'value') ? $faker->randomNumber(2) : $faker->randomNumber(3, 0, 1);
                        $points = $value * $coefficients[$indicator->id];

                        $result = new Result();
                        $result->report_id = $report->id;
                        $result->user_id = $user->id;
                        $result->indicator_id = $indicator->id;
                        $result->value = $value;
                        $result->points = $points;
                        $result->save();
                    }
                }

                $report->evaluated_at = Carbon::now();
                $report->save();
            }

            return $report;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input, $id) {
            $report = Report::find($id);

            if ($report) {
                $report->name = $input['name'];
                $report->start_date = $input['start_date'];
                $report->end_date = $input['end_date'];

                $report->users()->detach();
                foreach($input['users'] as $user) {
                    $report->users()->attach(
                        $user['id'], [
                            'view_self' => $user['pivot']['view_self'],
                            'view_all' => $user['pivot']['view_all']
                        ]
                    );
                }

                $report->indicators()->detach();
                foreach($input['indicators'] as $indicator) {
                    $report->indicators()->attach(
                        $indicator['id'], [
                            'show_value' => $indicator['pivot']['show_value'],
                            'show_points' => $indicator['pivot']['show_points'],
                        ]
                    );
                }
            }

            $report->save();

            return $report;
        });

        return $result;
    }

    public function destroy($id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        return Report::destroy($id);
    }

    public function excel(ReportRepository $reportRepository, ExcelRepository $excelRepository, $id)
    {
        $report = Report::with('users', 'indicators')->find($id);

        $this->authorize($report);

        $results = $reportRepository->getResultsTable($report);

        return $excelRepository->getExcel($results);
    }

    public function pdf(ReportRepository $reportRepository, ExcelRepository $excelRepository, $id)
    {
        $report = Report::with('users', 'indicators')->find($id);

        $this->authorize($report);

        $results = $reportRepository->getResultsTable($report);

        return $excelRepository->getPdf($results);
    }
}
