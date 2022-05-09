<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function home()
    {
        return view('welcome');
    }

    public function about()
    {
        return view('about');
    }

    public function showUser(User $user)
    {
        return view('showuser')->with('user', $user);
    }
}
