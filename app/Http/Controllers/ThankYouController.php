<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function __invoke(Request $request): View
    {
        // حسابات الأدمن مالهاش profile، فمالهاش صفحة شكر.
        $profile = $request->user()->profile;

        abort_unless($profile, 404);

        return view('thankyou', ['profile' => $profile]);
    }
}
