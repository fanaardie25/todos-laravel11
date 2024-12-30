<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordform()
    { 
        return view('user.forgot-password');
    }

    public function doForgotPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            ],[
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email yang anda masukan tidak terdaftar',
            ]);
            // hapus email lama di password_reset_token

            UserVerify::where('email', $request->input('email'))->delete();
            $token = Str::uuid();
            $data = [
                'email' => $request->input('email'),
                'token' => $token,
            ];

            UserVerify::create($data);

            Mail::send('user.email-reset-password',['token' => $token],function($message) use ($request){
                $message->to($request->input('email'));
                $message->subject('reset password');
            });

            return redirect()->route('forgotpassword')->with('success', 'Email berisikan instruksi reset password sudah dikirimkan,silakan cek terlebih dahulu')->withInput();
    }

    public function resetPassword($token)
    {
        $data = ['token' => $token];
        return view('user.reset-password', compact('token'));
    }

    public function doResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'password-confirmation' => 'required_with:password|same:password',
        ],[
            'password.required' => 'Kolom Password wajib diisi',
            'password.string' => 'hanya string yang diperbolehkan',
            'password.min' => 'minimum karakter untuk password adalah 6 karakter',
            'password-confirmation.required_with' => 'Password konfirmation harus diisi',
            'password-confirmation.same' => 'Password konfirmation tidak sama dengan password yang diisikan '
        ]);

        $datauser = UserVerify::where('token', $request->input('token'))->first();

        if (!$datauser) {
            return redirect()->back()->withErrors('token tidak valid');
        }

        $email = $datauser->email;

        $data = [
            'password' => bcrypt($request->input('password')),
            'email_verified_at' => Carbon::now()
        ];

        User::where('email',$email)->update($data);

        UserVerify::where('email',$email)->delete();

        return redirect()->route('login')->with('success','password sudah berganti, silakan login menggunakan password baru anda');
    }
}
