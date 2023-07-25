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
}
