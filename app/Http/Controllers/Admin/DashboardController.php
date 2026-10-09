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
    /** الأدمن ممكن يكتب الكود بأرقام عربية أو فارسية، فنحوّلها لأرقام إنجليزية. */
    private const DIGITS = [
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
        '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
        '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
    ];

    public function index(Request $request): View
    {
        // valid() بيرجّع الفلاتر السليمة بس، وأي قيمة غلط في الرابط بتتتجاهل بدل ما تعمل redirect.
        $validator = Validator::make($this->normalizedQuery($request), [
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'status' => ['nullable', Rule::in(array_keys(Profile::STATUS_LABELS))],
            'nationality' => ['nullable', 'string', 'max:100'],
            'code' => ['nullable', 'digits_between:1,8'],
        ], [
            'code.digits_between' => 'الكود أرقام فقط (من 1 إلى 8 أرقام)',
        ]);

        $filters = $validator->valid();

        // كود مكتوب غلط = نتيجة فاضية مع رسالة، مش كل المسجلين (عشان ما يتلخبطش الأدمن).
        $searchError = $validator->errors()->first('code');

        $registrants = Profile::query()
            ->with('user')
            ->completed()
            ->filter($filters)
            ->when($searchError, fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $nationalities = Profile::query()
            ->whereNotNull('nationality')
            ->distinct()
            ->orderBy('nationality')
            ->pluck('nationality');

        return view('admin.dashboard', compact('registrants', 'nationalities', 'searchError'));
    }

    /** @return array<string, mixed> */
    private function normalizedQuery(Request $request): array
    {
        $query = $request->query();

        if (isset($query['code']) && is_string($query['code'])) {
            $query['code'] = preg_replace('/\s+/u', '', strtr($query['code'], self::DIGITS));
        }

        return $query;
    }
}
