<?php

namespace App\Http\Controllers;

use App\Models\Profile;

class HomeController extends Controller
{
    public function index()
    {
        $count = Profile::where('completed', true)->count();

        return view('home', compact('count'));
    }
}
