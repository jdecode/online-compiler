<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('user.dashboard');
    }

    public function dashboard(): View
    {
        return view('user.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flash('flash-notification', 'You are now logged out!');

        return redirect(route('home'));
    }
}
