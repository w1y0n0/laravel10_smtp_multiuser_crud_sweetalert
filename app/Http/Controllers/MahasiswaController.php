<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MahasiswaController extends Controller
{
    function index()
    {
        $data = Mahasiswa::all();
        return view('mahasiswa.index', ['data' => $data]);
    }

    function tambah()
    {
        return view('mahasiswa.tambah');
    }

    function edit($id)
    {
        $data = Mahasiswa::find($id);
        // return $data;
        return view('mahasiswa.edit', ['data' => $data]);
    }

    function hapus(Request $request)
    {
        Mahasiswa::where('id', $request->id)->delete();

        Session::flash('success', 'Berhasil Hapus Data.');

        return redirect('/mahasiswa');
    }

    function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'npm' => 'required|max:9',
            'angkatan' => 'required|min:4|max:4',
            'jurusan' => 'required',
        ], [
            'name.required' => 'Name Wajib Di isi',
            'name.min' => 'Bidang name minimal harus 3 karakter.',
            'email.required' => 'Email Wajib Di isi',
            'email.email' => 'Format Email Invalid',
            'npm.required' => 'Nim Wajib Di isi',
            'npm.max' => 'NIM max 9 Digit',
            'angkatan.required' => 'Angkatan Wajib Di isi',
            'angkatan.min' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            'angkatan.max' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            'jurusan.required' => 'Jurusan Wajib Di isi',
        ]);

        Mahasiswa::create([
            'name' => $request->name,
            'email' => $request->email,
            'npm' => $request->npm,
            'angkatan' => $request->angkatan,
            'jurusan' => $request->jurusan,
        ]);

        Session::flash('success', 'Data berhasil ditambahkan');

        return redirect('/mahasiswa')->with('success', 'Berhasil Menambahkan Data');
    }

    function change(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'npm' => 'required|min:9|max:9',
            'angkatan' => 'required|min:4|max:4',
            'jurusan' => 'required',
        ], [
            'name.required' => 'Name Wajib Di isi',
            'name.min' => 'Bidang name minimal harus 3 karakter.',
            'email.required' => 'Email Wajib Di isi',
            'email.email' => 'Format Email Invalid',
            'npm.required' => 'Nim Wajib Di isi',
            'npm.max' => 'NIM max 9 Digit',
            'npm.min' => 'NIM min 9 Digit',
            'angkatan.required' => 'Angkatan Wajib Di isi',
            'angkatan.min' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            'angkatan.max' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            'jurusan.required' => 'Jurusan Wajib Di isi',
        ]);

        $mahasiswa = Mahasiswa::find($request->id);

        $mahasiswa->name = $request->name;
        $mahasiswa->email = $request->email;
        $mahasiswa->npm = $request->npm;
        $mahasiswa->angkatan = $request->angkatan;
        $mahasiswa->jurusan = $request->jurusan;
        $mahasiswa->save();

        Session::flash('success', 'Berhasil Mengubah Data');

        return redirect('/mahasiswa');
    }
}
