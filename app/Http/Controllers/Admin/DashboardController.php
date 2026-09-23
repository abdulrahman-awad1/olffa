<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::query()->with('user')->where('completed', true);

        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('nationality')) {
            $query->where('nationality', $request->string('nationality'));
        }

        $registrants = $query->latest()->paginate(20)->withQueryString();

        $nationalities = Profile::query()
            ->whereNotNull('nationality')
            ->distinct()
            ->orderBy('nationality')
            ->pluck('nationality');

        return view('admin.dashboard', compact('registrants', 'nationalities'));
    }
}
