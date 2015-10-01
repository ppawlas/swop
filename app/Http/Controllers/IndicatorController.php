<?php

namespace App\Http\Controllers;

use App\Indicator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IndicatorController extends Controller
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
    public function adminIndex()
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        // Retrieve all the indicators in the database and return them
        return Indicator::all();
    }

    public function managerIndex()
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        // Retrieve all the indicators defined for the organization of the currently authenticated manager
        return Auth::user()->organization->indicators;
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input, $id) {
            $indicator = Indicator::find($id);

            if ($indicator) {
                $organizationId = Auth::user()->organization->id;
                $newCoefficient = $input['pivot']['coefficient'];
                $indicator->organizations->find($organizationId)->pivot->coefficient = $newCoefficient;
            }

            $indicator->organizations->find($organizationId)->pivot->save();

            return $indicator;
        });

        return $result;
    }
}
