<?php

namespace App\Http\Controllers;

use App\Mail\AuthMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserManagementController extends Controller
{
    function index()
    {
        $data = User::all();
        return view('user_management.index', ['uc' => $data]);
    }

    function tambah()
    {
        return view('user_management.tambah');
    }

    function create(Request $request)
    {
        $str = Str::random(100);
        $foto = '';

        $request->validate([
            'fullname' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'fullname.required' => 'Full Name Wajib Di isi',
            'fullname.min' => 'Bidang Full Name minimal harus 4 karakter.',
            'email.required' => 'Email Wajib Di isi',
            'email.email' => 'Format Email Invalid',
            'password.required' => 'Password Wajib Di isi',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);


        if ($request->hasFile('foto')) {

            $request->validate(['foto' => 'mimes:jpeg,jpg,png,gif|image|file|max:1024']);

            $foto_file = $request->file('foto');
            $foto_ekstensi = $foto_file->extension();
            $nama_foto = date('ymdhis') . "." . $foto_ekstensi;
            $foto_file->move(public_path('foto/akun'), $nama_foto);
            $foto = $nama_foto;
        } else {
            $foto = "user.jpeg";
        }

        $accounts = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $request->password,
            'verify_key' => $str,
            'foto' => $foto,
        ]);

        $details = [
            'nama' => $accounts->fullname,
            'role' => 'user',
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'Laravel10 - Pendaftaran melalui SMTP + Multiuser + CRUD + Sweetalert',
            'url' => 'http://' . request()->getHttpHost() . "/" . "verify/" . $accounts->verify_key,
        ];

        Mail::to($request->email)->send(new AuthMail($details));

        Session::flash('success', 'User berhasil ditambahkan , Harap verifikasi akun sebelum di gunakan.');

        return redirect('/user-management');
    }

    function edit($id)
    {
        $data = User::where('id', $id)->get();
        return view('user_management.edit', ['uc' => $data]);
    }

    function change(Request $request)
    {
        $request->validate([
            'foto' => 'image|file|max:1024',
            'fullname' => 'required|min:4',
        ], [
            'foto.image' => 'File Wajib Image',
            'foto.file' => 'Wajib File',
            'foto.max' => 'Bidang foto tidak boleh lebih besar dari 1024 kilobyte',
            'fullname.required' => 'Nama Wajib Di isi',
            'fullname.min' => 'Bidang nama minimal harus 4 karakter.',
        ]);

        $user = User::find($request->id);

        if ($request->hasFile('foto')) {
            $foto_file = $request->file('foto');
            $foto_ekstensi = $foto_file->extension();
            $nama_foto = date('ymdhis') . "." . $foto_ekstensi;
            $foto_file->move(public_path('foto/akun'), $nama_foto);
            $user->foto = $nama_foto;

            $foto_lama = User::where('id', $request->id)->first();
            File::delete(public_path('foto/akun') . '/' . $foto_lama->foto);
        }

        $user->fullname = $request->fullname;
        $user->password = $request->password;
        $user->save();

        Session::flash('success', 'User berhasil diedit');

        return redirect('/user-management');
    }

    function hapus(Request $request)
    {
        $data = User::where('id', $request->id)->first();
        File::delete(public_path('foto/akun') . '/' . $data->foto);

        User::where('id', $request->id)->delete();

        Session::flash('success', 'Data berhasil dihapus');

        return redirect('/user-management');
    }
}
