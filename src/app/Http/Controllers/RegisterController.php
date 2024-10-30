<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    /**
     * ユーザー登録リクエストを処理し、アカウントを作成します。
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request, CreatesNewUsers $creator)
    {
        // ユーザーのインスタンスを作成し、データベースに保存
        $user = $creator->create($request->all());

        // ユーザー登録完了のイベントを発行
        event(new Registered($user));

        return redirect('/login');
    }
}
