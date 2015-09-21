<?php

namespace App\Http\Controllers;

use App\Indicator;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrganizationRequest;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class OrganizationController extends Controller
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
    public function index()
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        // Retrieve all the organizations in the database and return them
        return Organization::all();
    }

    public function store(CreateOrganizationRequest $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input) {
            $organization = Organization::create($input);

            // create automatically all indicators for the new organization
            foreach(Indicator::all() as $indicator) {
                $organization->indicators()->save($indicator, ['coefficient' => $indicator->default_coefficient]);
            }

            return $organization;
        });

        return $result;
    }

    public function show($id)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $organization = Organization::find($id);

        return $organization;
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $input = $request->all();

        $organization = Organization::find($id);

        if ($organization) {
            $organization->update($input);
        }

        return $organization;
    }

    public function destroy($id)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        return Organization::destroy($id);
    }
}
