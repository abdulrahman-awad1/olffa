<?php

namespace App\Http\Controllers;

use App\Actions\RegisterUser;
use App\Enums\Gender;
use App\Http\Requests\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Route: /register/{gender} — لو القيمة مش male/female، Laravel بيرجّع 404 تلقائيًا.
    public function create(Gender $gender): View
    {
        return view('register', ['gender' => $gender->value]);
    }

    public function store(RegisterRequest $request, Gender $gender, RegisterUser $registerUser): RedirectResponse
    {
        $user = $registerUser->handle($gender, $request->validated());

        Auth::login($user);

        return redirect()->route('thankyou');
    }
}
