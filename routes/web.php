<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/login', function () {
        return view('user.login');
    })->name('login');
});

Route::get('/auth/github/login', function () {
    return Socialite::driver('github')
        ->scopes(['read:user', 'user:email'])
        ->redirect();
})->name('github.login');

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();
    $user = User::updateOrCreate(
        [
            'email' => $githubUser->getEmail(),
        ],
        [
            'name' => $githubUser->getName(),
            'github_id' => $githubUser->getId(),
            'github_email' => $githubUser->getEmail(),
            'github_username' => $githubUser->getNickname(),
            'github_token' => $githubUser->token ?? '',
            'github_refresh_token' => $githubUser->refreshToken ?? '',
            'github_oauth_status' => true,
            'github_oauth_timestamp' => now(),
        ]
    );
    Auth::login($user);
    return redirect(route('dashboard'));

})->name('github.redirect');

Route::middleware(['auth'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::post('/logout', 'logout')->name('logout');
    });
});
