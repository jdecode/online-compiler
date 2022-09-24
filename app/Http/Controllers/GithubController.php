<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public array $curlGithubHeaders = [];

    public function __construct()
    {
        $this->setCurlGithubHeaders('User-Agent: ' . env('GITHUB_USERNAME'));
        $this->setCurlGithubHeaders('accept: application/vnd.github.v3+json');
    }

    public function setCurlGithubHeaders(string $header)
    {
        $this->curlGithubHeaders[] = $header;
    }

    private function updateUserWithGithubData($githubUser): User
    {
        $user = User::where('github_id', $githubUser->getId())->first();
        if (!$user) {
            $user = User::where('email', $githubUser->getEmail())->first();
        }
        if (!$user) {
            $user = new User();
        }
        $user->email = $githubUser->getEmail();
        $user->name = $githubUser->getName();
        $user->github_id = $githubUser->getId();
        $user->github_email = $githubUser->getEmail();
        $user->github_username = $githubUser->getNickname();
        $user->github_token = $githubUser->token ?? '';
        $user->github_refresh_token = $githubUser->refreshToken ?? '';
        $user->github_oauth_status = true;
        $user->github_oauth_timestamp = now();
        $user->save();
        return $user;
    }

    public function callback(): Redirector|Application|RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();
        Auth::login($this->updateUserWithGithubData($githubUser));

        return redirect('/dashboard');
    }
}
