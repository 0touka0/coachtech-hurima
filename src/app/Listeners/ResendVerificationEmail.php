<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Attempting;

class ResendVerificationEmail
{
    /**
     * Handle the event.
     */
    public function handle(Attempting $event)
    {
        $user = User::where('email', $event->credentials['email'])->first();

        // ユーザーが認証済みかチェック
        if (!$user->hasVerifiedEmail()) {
            // 認証メールを再送する
            $user->sendEmailVerificationNotification();
        }
    }
}