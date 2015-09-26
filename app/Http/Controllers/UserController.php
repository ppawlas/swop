<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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

        // Retrieve all the users in the database and return them
        return User::with('organization')->get();
    }

    public function managerIndex()
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        // Retrieve all the users defined for the organization of the currently authenticated manager
        return Auth::user()->organization->users;
    }
}
