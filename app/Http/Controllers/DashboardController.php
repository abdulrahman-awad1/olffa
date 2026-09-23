<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        return view('dashboard', compact('profile'));
    }
}
