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
            //agent, customer
            if (Auth::user()->type === 'admin') {
                return Redirect()->route('dashboard.admin')->withSuccess(__('messages.signed_in'));
            } elseif(Auth::user()->type === 'agent') {
                return Redirect()->route('dashboard.agent')->withSuccess(__('messages.signed_in'));
            } elseif(Auth::user()->type === 'customer') {
                return Redirect()->route('dashboard.customer')->withSuccess(__('messages.signed_in'));
            }
        }

        return redirect("login")->withSuccess(__('messages.login_details_are_not_valid'));
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

        return redirect("login")->withSuccess(__('messages.registered_successfully'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'type' => 'required',
        ]);

        //password hash
        $request['password'] = Hash::make($request['password']);

        //store
        User::create($request->all());

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess(__('messages.user_added_successfully'));
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
            'name' => 'required',
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8',
            'type' => 'required',
        ]);

        //password hash
        $request['password'] = Hash::make($request['password']);

        //patch
        $user->fill($request->all())->save();

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess(__('messages.user_updated_successfully'));
    }

    public function deleteUser($id)
    {
        //check user exist?
        $user = User::whereId($id)->first() ?? abort(404, 'User not found');

        //delete
        $user->delete();

        //redirect
        return Redirect()->route('admin.user.list')->withSuccess(__('messages.user_deleted_successfully'));
    }

    public function changePassword()
    {
        return view('user/change-password');
    }

    public function patchChangePassword(Request $request)
    {
        //validation
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|same:new_password_confirmation',
            'new_password_confirmation' => 'required|min:8',
        ]);

        //check password
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->withErrors(__('messages.your_current_password_does_not_matches_with_the_password'));
        }

        //patch user
        $user = Auth::user();
        $user->password = Hash::make($request['new_password']);
        $user->save();

        //redirect
        return redirect()->back()->withSuccess(__('messages.user_updated_successfully'));
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
