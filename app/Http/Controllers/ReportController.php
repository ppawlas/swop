<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

        // Retrieve all the groups defined for the organization of the currently authenticated manager
        return Auth::user()->myReports;
    }

    public function show($id)
    {
        $report = Report::with('users', 'indicators')->find($id);

        $this->authorize($report);

        return $report;
    }
}
