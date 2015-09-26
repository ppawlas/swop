<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateGroupRequest;

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

    public function store(CreateGroupRequest $request)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input) {
            $group = new Group();
            $group->name = $input['name'];

            $group->organization()->associate(Auth::user()->organization);

            $group->save();

            foreach($input['users'] as $user) {
                $group->users()->attach($user['id']);
            }

            foreach($input['indicators'] as $indicator) {
                $group->indicators()->attach($indicator['id']);
            }

            return $group;
        });

        return $result;
    }

    public function show($id)
    {
        $group = Group::with('users', 'indicators')->find($id);

        $this->authorize($group);

        return $group;
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        $input = $request->all();

        $result = DB::transaction(function($input) use($input, $id) {
            $group = Group::find($id);

            if ($group) {
                $group->name = $input['name'];

                $group->users()->detach();
                foreach($input['users'] as $user) {
                    $group->users()->attach($user['id']);
                }

                $group->indicators()->detach();
                foreach($input['indicators'] as $indicator) {
                    $group->indicators()->attach($indicator['id']);
                }
            }

            $group->save();

            return $group;
        });

        return $result;
    }

    public function destroy($id)
    {
        if (Gate::denies('managerOnly')) {
            abort(403);
        }

        return Group::destroy($id);
    }
}
