<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    /**
     * Show the user profile.
     */
    public function show(User $user)
    {
    	return view('profiles.show', [
    		'profileUser' => $user,
    		'activities' => Activity::feed($user),
    	]);
    }
}
