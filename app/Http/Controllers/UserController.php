<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login', [
            'active' => 'profile',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('register', [
            'active' => '',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->password != $request->re_password) {
            return back()->with('errorRegister', "Password doesn't match");
        }

        $validatedUser = $request->validate([
            'username' => ['required', 'max:20', 'unique:Users'],
            'password' => ['required']
        ]);
        $validatedUser['password'] = bcrypt($validatedUser['password']);
        User::create($validatedUser);

        Profile::create([
            'id_user' => User::where('username', $request->username)->first()->id,
            'nama_lengkap' => $request->username,
            'kelas' => 'X',
            'jurusan' => 'TR',
            'jenis_kelamin' => 'l'
        ]);

        return redirect()->intended('/user')->with('successRegister', 'Success create account');
    }

    public function login(Request $request)
    {
        $validatedUser = $request->validate([
            'username' => ['required', 'max:20'],
            'password' => ['required']
        ]);

        if (Auth::attempt($validatedUser)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('errorLogin', 'Username or password wrong');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
