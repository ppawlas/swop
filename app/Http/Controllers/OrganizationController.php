<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Retrieve all the organizations in the database and return them
        $organizations = Organization::all();
        return $organizations;
    }
}
