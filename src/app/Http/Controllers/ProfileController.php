<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile_edit', compact('user'));
    }

    /**
     * プロフィール画像と住所情報を更新
     *
     * @param \App\Http\Requests\ProfileRequest $profileRequest
     * @param \App\Http\Requests\AddressRequest $addressRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        $updateData = [];

        // プロフィール画像の保存処理
        if ($profileRequest->hasFile('image')) {
            // 既存の画像があれば削除
            if ($user->image_path && Storage::disk('public')->exists($user->image_path)) {
                Storage::disk('public')->delete($user->image_path);
            }

            // 画像を保存してパスを取得
            $path = $profileRequest->file('image')->store('profile_images', 'public');
            $updateData['image_path'] = Storage::url($path);
        }

        // 住所データの保存処理
        if ($addressRequest->has('address')) {
            $addressData = $addressRequest->validated();
            $updateData = array_merge($updateData, $addressData);
        }

        // データが更新された場合のみ保存
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return redirect('/');
    }
}
