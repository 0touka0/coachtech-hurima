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
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request, CreatesNewUsers $creator)
    {
        // Create a new user instance
        $user = $creator->create($request->all());

        // Trigger the Registered event
        event(new Registered($user));

        // Redirect to the login page
        return redirect('/login');
    }
}
