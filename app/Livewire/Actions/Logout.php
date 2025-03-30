<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
class Logout
{
    /**
     * Log the current user out of the application.
     */
    // public function __invoke(): void
    // {
    //     Auth::guard('web')->logout();

    //     Session::invalidate();
    //     Session::regenerateToken();
    // }
    public function __invoke(): RedirectResponse
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/'); 
    }
}
