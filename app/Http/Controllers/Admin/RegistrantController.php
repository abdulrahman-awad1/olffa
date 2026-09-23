<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\MatchService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrantController extends Controller
{
    public function show(Profile $profile)
    {
        $profile->load('user');
        $matches = MatchService::findMatches($profile);

        return view('admin.show', compact('profile', 'matches'));
    }

    public function update(Request $request, Profile $profile)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'reviewing', 'matched', 'completed'])],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $profile->update($data);

        return back()->with('success', 'تم حفظ التغييرات');
    }
}
