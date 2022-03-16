<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            //login log store
            $this->storeLoginLog();
            //redirect
            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }

        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration()
    {
        return view('user.registration');
    }

    public function storeRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("login")->withSuccess('Registered successfully');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => 'customer',
            'password' => Hash::make($data['password'])
        ]);
    }

    public function storeLoginLog()
    {
        UserLoginLog::create([
            'user_id' => Auth::user()->id,
            'ip' => \Request::ip(),
            'user_agent' => \Request::server('HTTP_USER_AGENT'),
        ]);
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function list()
    {
        $users = User::all();

        return view('user/list', compact('users'));
    }

    public function add()
    {
        return view('user/add');
    }

    public function storeUser(Request $request)
    {
        //validation
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'type'      => 'required',
        ]);

        //password hash
        $request['password'] = Hash::make($request['password']);

        //store
        User::create($request->all());

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess('User added successfully');
    }

    public function editUser($id)
    {
        $user = User::whereId($id)->first() ?? abort(404, 'User not found');
        return view('user/edit', compact('user'));
    }

    public function patchUser($id, Request $request)
    {
        //check user exist?
        $user = User::whereId($id)->first() ?? abort(404, 'User not found');

        //email cannot change
        $request['email'] = $user->email;

        //validation
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|exists:users',
            'password'  => 'required|min:8',
            'type'      => 'required',
        ]);

        //password hash
        $request['password'] = Hash::make($request['password']);

        //patch
        $user->fill($request->all())->save();

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess('User updated successfully');
    }

    public function deleteUser($id)
    {
        //check user exist?
        $user = User::whereId($id)->first() ?? abort(404, 'User not found');

        //delete
        $user->delete();

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess('User deleted successfully');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
