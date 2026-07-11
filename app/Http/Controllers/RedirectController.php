<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function home(Request $request)
    {
        return redirect()->route('dashboard.index');
    }
}

