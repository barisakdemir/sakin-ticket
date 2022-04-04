<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiToken;
use Hash;
use Str;

class ApiTokenController extends Controller
{
    public function list()
    {
        $tokens = ApiToken::all();
        return view('api-token/list', compact('tokens'));
    }

    public function generate()
    {
        //generate token
        $token = Hash::make(Str::random(64));

        //store token
        ApiToken::create([
            'token' => $token
        ]);

        //redirect
        return Redirect()->route('admin.api.token.list')->withSuccess('Token added successfully');
    }

    public function delete($id)
    {
        //check user exist?
        $token = ApiToken::whereId($id)->first() ?? abort(404, 'Token not found');

        //delete
        $token->delete();

        //redirect
        return Redirect()->route('admin.api.token.list')->withSuccess('Token deleted successfully');
    }
}
