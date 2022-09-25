<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

class UserController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('user.dashboard');
    }

    public function dashboard(Request $request): View
    {
        return view(
            'user.dashboard',
            $this->runCode($request)
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flash('flash-notification', 'You are now logged out!');

        return redirect(route('home'));
    }

    #[ArrayShape(['language' => "mixed|string", 'code' => "mixed", 'output' => "string"])]
    private function runCode(Request $request): array
    {
        $language = $request->input('language');
        $code = $request->input('code', '');
        $filename = uniqid();
        $output = '';

        if ($language === 'php') {
            $filename .= '.php';
            file_put_contents($filename, $code);
            exec('php ' . $filename, $output);
            unlink($filename);
        } elseif ($language === 'python') {
            $filename .= '.py';
            file_put_contents($filename, $code);
            exec('python3 ' . $filename, $output);
            unlink($filename);
        } elseif ($language === 'javascript') {
            $filename .= '.js';
            file_put_contents($filename, $code);
            exec('node ' . $filename, $output);
            unlink($filename);
        } elseif ($language === 'bash') {
            $filename .= '.sh';
            file_put_contents($filename, $code);
            exec('bash ' . $filename, $output);
            unlink($filename);
        } elseif ($language === 'java') {
            $filename_sans_ext = $filename;
            $filename .= '.java';
            file_put_contents($filename, $code);
            exec('javac ' . $filename, $output);
            exec('java ' . $filename_sans_ext, $output);
            unlink($filename);
        } else {
            $output = 'Execution results would be shown here';
        }

        return [
            'language' => !empty($language) ? $language : 'php',
            'code' => $code,
            'output' => is_array($output) ? implode(PHP_EOL, $output) : $output
        ];
    }
}
