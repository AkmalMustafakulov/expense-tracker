<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|max:255|confirmed'
        ]);
        $user = User::create($request->all());
        Auth::login($user);
        return $user;
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required|max:255'
        ]);
        $user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);
        if($user) {
            return "Welcome: ". Auth::user()->name;
        } else {
            return 'Wrong user';
        }
    }
}
