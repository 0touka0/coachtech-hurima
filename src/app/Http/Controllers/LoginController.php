<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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
        // 認証処理
        $request->authenticate();

        // セッションの再生成
        $request->session()->regenerate();

        // ログインしたユーザーを取得
        $user = Auth::user();

        if (empty($user->address)) {
            // 住所が登録されていない場合は、プロフィール編集ページにリダイレクト
            return redirect()->route('profile.edit');
        }

        // 通常のログイン処理
        return app(LoginResponse::class);
    }
}

