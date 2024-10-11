<?php

namespace App\Http\Controllers;

// use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, CreatesNewUsers $creator)
    {
        $user = $creator->create($request->all());
        Auth::login($user);
        // 確認メールを送信するため
        // event(new Registered($user));

        return redirect()->route('profile.edit');
    }
}
