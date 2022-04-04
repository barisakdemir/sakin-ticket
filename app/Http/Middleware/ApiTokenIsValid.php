<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;

class ApiTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isTokenInvalid = false;

        /*check token is valid?*/
        $token = ApiToken::where('token', $request->_token)->first();

        if (!$token) {
            $isTokenInvalid = true;
        }
        /*check token is valid? end*/

        /*return error message*/
        if($isTokenInvalid == true) {
            $responseArr['status']  = false;
            $responseArr['message'] = 'Invalid Token!';
            return response()->json($responseArr);
        }
        /*return error message end*/

        return $next($request);
    }
}
