<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthenticateController extends Controller
{

    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        // also the list of publicly available organizations is possible
        // to retrieve
        $this->middleware('jwt.auth', ['except' => ['getAvailableOrganizations', 'authenticate']]);
    }

    public function getAvailableOrganizations()
    {
        return Organization::available()->get();
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('login', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found', 404]);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired', $e->getStatusCode()]);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid', $e->getStatusCode()]);
        } catch (JWTException $e) {
            return response()->json(['token_absent', $e->getStatusCode()]);
        }

        // since we have found a user, lets fetch his organization and roles,
        // so they can be also attached to the response
        $user->load('organization', 'roles');
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

}
