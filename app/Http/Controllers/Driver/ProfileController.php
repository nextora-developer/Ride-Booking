<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $driver = $request->user(); // 当前登录 driver
        return view('driver.profile.show', compact('driver'));
    }
}
