<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\LoginResponse;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return mixed
     */
    public function store(LoginRequest $request)
    {
        // 未認証の場合認証メールの再送信
        $request->authenticate();

        // セッションの再生成
        $request->session()->regenerate();

        // ログイン処理
        return app(LoginResponse::class);
    }
}

