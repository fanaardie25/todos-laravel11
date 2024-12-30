<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    function login()
    {
        return view('user.login');
    }

    function doLogin(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if(Auth::attempt($data)){
            if(Auth::user()->email_verified_at == ''){
                Auth::logout();
                return redirect()->route('login')->withErrors('Email belum terverifikasi silakan cek email anda kembali')->withInput();
            }else{
                return redirect()->route('todo');
            }
            return redirect()->route('todo');
        }else{
            return redirect()->route('login')->withErrors('Username dan password tidak sesuai')->withInput();
        }

    }

    function register()
    {
        return view('user.register');
    }

    function doRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|max:100|unique:users,email',
            'name' => 'required|min:3|max:25',
            'password' => 'required|string|min:6',
            'password-confirmation' => 'required_with:password|same:password',
        ],[
            'email.required' => 'Email tidak boleh kosong',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 100 karakter',
            'email.unique' => 'Email sudah digunakan',
            'name.required' => 'Kolom Nama wajib diisi',
            'name.min' => 'minimum karakter untuk nama adalah 5 karakter',
            'name.max' => 'maximum karakter untuk nama adalah 25 karakter',
            'password.required' => 'Kolom Password wajib diisi',
            'password.string' => 'hanya string yang diperbolehkan',
            'password.min' => 'minimum karakter untuk password adalah 6 karakter',
            'password-confirmation.required_with' => 'Password konfirmation harus diisi',
            'password-confirmation.same' => 'Password konfirmation tidak sama dengan password yang diisikan '
        ]);

        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
        ];

        User::create($data);
        $cekToken = UserVerify::where('email', $request->input('email'))->first();
        if($cekToken){
            UserVerify::where('email', $request->input('email'))->delete();
        }

        $token = Str::uuid();
        $data = [
            'email' => $request->input('email'),
            'token' => $token
        ];

        UserVerify::create($data);

        Mail::send('user.email-verification',['token' => $token],function($message) use ($request){
            $message->to($request->input('email'));
            $message->subject('Verifikasi Email');
        });

        return redirect()->route('register')->with('success','Email verifikasi telah dikirimkan silakan cek terlebih dahulu')->withInput();
    }


    function updateData()
    {
        return view('user.update-data');
    }
    function doUpdateData(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:25',
            'password' => 'nullable|string|min:6',
            'password-confirmation' => 'required_with:password|same:password',
        ],[
            'name.required' => 'Kolom Nama wajib diisi',
            'name.min' => 'minimum karakter untuk nama adalah 5 karakter',
            'name.max' => 'maximum karakter untuk nama adalah 25 karakter',
            'password.string' => 'hanya string yang diperbolehkan',
            'password.min' => 'minimum karakter untuk password adalah 6 karakter',
            'password-confirmation.required_with' => 'Password konfirmation harus diisi',
            'password-confirmation.same' => 'Password konfirmation tidak sama dengan password yang diisikan '
        ]);

        $data = [
            'name' => $request->input('name'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : Auth::user()->password

        ];

        User::where('id',Auth::user()->id)->update($data);

        return redirect()->route('user.update-data')->with('success','Berhasil update data');
    }

    function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    function verifyAccount($token){

        $checkuser = UserVerify::where('token', $token)->first();
        if(!is_null($checkuser)){
            $email = $checkuser->email;

            $datauser = User::where('email', $email)->first();
           if($datauser->email_verified_at){
            $message = "akun anda sudah terverifikasi sebelumnya";
           }else{
            $data = [
                'email_verified_at' => Carbon::now(),
            ];

            User::where('email' ,$email)->update($data);

            UserVerify::where('email',$email)->delete();
            $message = "akun anda sudah terverifikasi , silakan login";
           }

           return redirect()->route('login')->with('success', $message);

        }else{
            return redirect()->route('login')->withErrors('Link token tidak valid');
        }
    }
}
