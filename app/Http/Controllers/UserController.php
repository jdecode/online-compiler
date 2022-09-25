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

    public function dashboard(Request $request): View
    {
        $output = !$request->isMethod('post')
            ?
            [
                'language' => 'c',
                'code' => '',
                'output' => 'Execution results would be shown here',
                'error_in_code' => ''
            ]
            :
            $this->runCode($request)
        ;
        return view(
            'user.dashboard',
            $output
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

    private function runCode(Request $request): array
    {
        $language = $request->input('language');
        $code = $request->input('code', '');
        $filename = uniqid();
        $output = '';
        $error_in_code = null;

        if ($language === 'php') {
            $filename .= '.php';
            file_put_contents($filename, $code);
            exec('php ' . $filename, $output, $error_in_code);
            unlink($filename);
        } elseif ($language === 'python') {
            $filename .= '.py';
            file_put_contents($filename, $code);
            exec('python ' . $filename . ' 2>&1', $output, $error_in_code);
            unlink($filename);
        } elseif ($language === 'c') {
            $filename_sans_ext = $filename;
            $filename .= '.c';
            file_put_contents($filename, $code);
            exec("gcc $filename -o $filename_sans_ext 2>&1", $output, $error_in_code);
            if ($error_in_code === 0) {
                exec("./$filename_sans_ext 2>&1", $output, $error_in_code);
                unlink($filename_sans_ext);
            }
            unlink($filename);
        } elseif ($language === 'javascript') {
            $filename .= '.js';
            file_put_contents($filename, $code);
            exec('node ' . $filename, $output, $error_in_code);
            unlink($filename);
        } elseif ($language === 'bash') {
            $filename .= '.sh';
            file_put_contents($filename, trim(str_replace("\r", "\n", $code)) . PHP_EOL);
            exec('bash ' . $filename . ' 2>&1', $output, $error_in_code);
            unlink($filename);
        }

        return [
            'language' => !empty($language) ? $language : 'php',
            'code' => $code,
            'output' => is_array($output) ? implode(PHP_EOL, $output) : $output,
            'error_in_code' => $error_in_code
        ];
    }
}
