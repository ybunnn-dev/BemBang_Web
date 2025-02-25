<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function redirectBasedOnRole()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        switch ($user->role_id) {
            case 1:
                return redirect()->route('management.dashboard');
            case 2:
                return redirect()->route('frontdesk.dashboard');
            default:
                return redirect()->route('home')->with('error', 'Unauthorized role.');
        }
    }
}
