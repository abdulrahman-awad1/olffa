<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\MatchService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrantController extends Controller
{
    public function show(Profile $profile): View
    {
        $profile->load('user');
        $matches = MatchService::findMatches($profile);

        return view('admin.show', compact('profile', 'matches'));
    }

    public function update(Request $request, Profile $profile): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Profile::STATUS_LABELS))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        // forceFill: الأدمن هو الوحيد المسموح له يغيّر status/admin_notes،
        // فمش محتاجين نسيبهم في $fillable (ينفع نشيلهم من هناك بعد كده).
        $profile->forceFill($data)->save();

        // الصفحة بتبعت الفورم بـ fetch وبتحدّث نفسها من الرد (من غير reload).
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'تم حفظ التغييرات',
                'status' => $profile->status,
                'status_label' => $profile->statusLabel(),
            ]);
        }

        return back()->with('success', 'تم حفظ التغييرات');
    }
}
