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
        $output = !$request->isMethod('post')
            ?
            [
                'language' => 'c',
                'code' => '',
                'output' => 'Execution results would be shown here',
                'error_in_code' => '',
                'command' => ''
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

    #[ArrayShape([
        'language' => "mixed|string",
        'code' => "mixed",
        'output' => "string",
        'error_in_code' => "int|mixed|null",
        'command' => "string|null"
    ])]
    private function runCode(Request $request): array
    {
        $language = $request->input('language');
        $code = $request->input('code', '');
        $filename = "/tmp/".uniqid();
        $output = '';
        $error_in_code = null;
        $command = '';

        switch ($language) {
            case 'php':
                $filename .= '.php';
                $this->executioner($filename, $language, $code, $output, $error_in_code, $command);
                break;
            case 'python':
                $filename .= '.py';
                $this->executioner($filename, $language, $code, $output, $error_in_code, $command);
                break;
            case 'node':
                $filename .= '.js';
                $this->executioner($filename, $language, $code, $output, $error_in_code, $command);
                break;
            case 'bash':
                $filename .= '.sh';
                file_put_contents($filename, trim(str_replace("\r", "\n", $code)) . PHP_EOL);
                $command = 'bash ' . $filename . ' 2>&1';
                exec($command, $output, $error_in_code);
                unlink($filename);
                break;
            case 'gcc':
                $filename_sans_ext = $filename;
                $filename .= '.c';
                file_put_contents($filename, $code);
                $command = "gcc $filename -o $filename_sans_ext 2>&1";
                exec($command, $output, $error_in_code);
                if ($error_in_code === 0) {
                    $command .= " && $filename_sans_ext 2>&1";
                    exec("$filename_sans_ext 2>&1", $output, $error_in_code);
                    unlink($filename_sans_ext);
                }
                unlink($filename);
                break;
        }
        return [
            'language' => !empty($language) ? $language : 'php',
            'code' => $code,
            'output' => is_array($output) ? implode(PHP_EOL, $output) : $output,
            'error_in_code' => $error_in_code,
            'command' => $command
        ];
    }

    private function executioner($filename, $language, $code, &$output, &$error_in_code, &$command)
    {
        file_put_contents($filename, $code);
        $command = $language . ' ' . $filename . ' 2>&1';
        exec($command, $output, $error_in_code);
        unlink($filename);
    }
}
