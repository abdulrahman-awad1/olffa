<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // valid() بيرجّع الفلاتر السليمة بس، وأي قيمة غلط في الرابط بتتتجاهل بدل ما تعمل redirect.
        $filters = Validator::make($request->query(), [
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'status' => ['nullable', Rule::in(array_keys(Profile::STATUS_LABELS))],
            'nationality' => ['nullable', 'string', 'max:100'],
        ])->valid();

        $registrants = Profile::query()
            ->with('user')
            ->completed()
            ->filter($filters)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $nationalities = Profile::query()
            ->whereNotNull('nationality')
            ->distinct()
            ->orderBy('nationality')
            ->pluck('nationality');

        return view('admin.dashboard', compact('registrants', 'nationalities'));
    }
}
