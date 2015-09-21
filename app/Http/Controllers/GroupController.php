<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
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
        return Auth::user()->organization->groups;
    }

    public function show($id)
    {
        $group = Group::with('users', 'indicators')->find($id);

        $this->authorize($group);

        return $group;
    }
}
