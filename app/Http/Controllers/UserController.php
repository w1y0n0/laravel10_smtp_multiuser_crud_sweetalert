<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    function index()
    {
        return view('akses.user.index');
    }
}
