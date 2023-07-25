<?php

namespace App\Http\Controllers;

use App\Mail\AuthMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    function index()
    {
        return view('auth.login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->email_verified_at != null) {
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin')->with('success', 'Halo Admin, Anda berhasil login');
                } else if (Auth::user()->role === 'user') {
                    return redirect()->route('user')->with('success', 'Anda berhasil login');
                }
            } else {
                Auth::logout();
                return redirect()->route('auth')->withErrors('Akun anda belum aktif. Harap verifikasi terlebih dahulu');
            }
            // return "Login Success";
        } else {
            return redirect()->route('auth')->withErrors('Email dan/atau password salah.');
        }
    }

    function create()
    {
        return view('auth.register');
    }

    function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|min:5',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'foto' => 'required|image|file|mimes:png,jpg,jpeg',
        ], [
            'fullname.required' => 'Fullname wajib diisi',
            'fullname.min' => 'Fullname minimal 5 karakter',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email telah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'foto.required' => 'Foto wajib diisi',
            'foto.image' => 'Foto harus berupa image',
            'foto.file' => 'Foto harus berupa file ',
            'foto.mimes' => 'Foto harus berupa file PNG/JPG/JPEG',
        ]);

        $foto_file = $request->file('foto');
        $foto_ekstensi = $foto_file->extension();
        $foto_nama = date('ymdHis') . "." . $foto_ekstensi;
        $foto_file->move(public_path('foto/akun'), $foto_nama);

        $str_key = Str::random(100);

        $info_register = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $request->password,
            'foto' => $foto_nama,
            'verify_key' => $str_key
        ];

        User::create($info_register);

        $details = [
            'nama' => $info_register['fullname'],
            'role' => 'user',
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'Laravel 10 - Pendaftaran SMTP + Multiuser + CRUD + Sweetalert',
            'url' => 'http://' . request()->getHttpHost() . "/" . "verify/" . $info_register['verify_key'],
        ];

        Mail::to($info_register['email'])->send(new AuthMail($details));

        return redirect()->route('auth')->with('success', 'Link verifikasi telah dikirim ke email anda. Cek email untuk melakukan verifikasi');
    }

    function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')
            ->where('verify_key', $verify_key)
            ->exists();

        if ($keyCheck) {
            $user = User::where('verify_key', $verify_key)->update(['email_verified_at' => date('Y-m-d H:i:s')]);

            return redirect()->route('auth')->with('success', 'Verifikasi berhasil. Akun anda sudah aktif.');
        } else {
            return redirect()->route('auth')->withErrors('Keys tidak valid. Pastikan anda telah melakukan registrasi.')->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
