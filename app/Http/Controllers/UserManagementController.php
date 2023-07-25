<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    function index()
    {
        return view('user.index');
    }
}
