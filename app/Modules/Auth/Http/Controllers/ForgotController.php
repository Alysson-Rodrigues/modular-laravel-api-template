<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Bootstrap\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Modules\Auth\Http\Requests\PasswordResetRequest;
use App\Modules\Reports\Mails\Notification;
use Inertia\Inertia;
use Exception;

class ForgotController extends Controller
{
    public function index()
    {
        return Inertia::render('auth/forgot');
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        try {
            $user = User::where('forget_token', $request->token)->first();
            $user->update(
                [
                    'forget_token' => null,
                    'password' => $request->password
                ]
            );

            return redirect('/login')
                ->withSuccess(
                    'Senha alterada com sucesso'
                );
        } catch (Exception $error) {
            return back()->withError('Falha na alteração da senha, tente novamente');
        }
    }

    public function getSend(Request $request)
    {
        $token = $request->token;
        $hasToken = User::where('forget_token', $token)->first();

        if (!$hasToken) {
            return abort(404);
        }

        return Inertia::render('auth/reset', compact('token'));
    }

    public function sendEmail(Request $request)
    {
        try {
            $token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16);
            $user = User::where('email', $request->get('email'))->firstOrFail();

            $user->update([
                'forget_token' => $token
            ]);

            $email = array(
                'subject' => "Recuperação de Senha",
                'title'   => "Recuperação de Senha",
                'message' => view('auth::partials._forgot', compact('user')),
                'view'    => 'mails.notify',
            );

            Mail::to($user->email)
                ->send(new Notification($email));

            return redirect()->to('login');
        } catch (Exception $error) {
            return redirect()->back()->with('message', 'E-mail não cadastrado');
        }
    }
}
