<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketMessage;

class TicketController extends Controller
{
    public function store(Request $request){
        /*validation*/
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'importance'    => 'required',
            'title'         => 'required',
            'message'       => 'required',
            'name'          => 'required',
            'email'         => 'required|email',
        ]);

        if ( $validator->fails() ) {
            $responseArr['status']  = false;
            $responseArr['message'] = $validator->errors();
            return response()->json($responseArr);
        }
        /*validation finish*/

        /*check guest is exist? if is not, add*/
        //check
        $userEmail = 'guest:'.$request['email'];
        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request['name'],
                'email' => $userEmail,
                'type' => 'guest',
                'password' => 'none',
            ]);
        }
        /*check guest is exist? if is not, add finish*/

        /*store ticket and message*/
        //store
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'department_id' => $request['department_id'],
            'status' => 'active',
            'importance' => $request['importance'],
            'title' => $request['title'],
        ]);
        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request['message'],
        ]);
        /*store ticket and message finish*/

        /*return success message*/
        $responseArr['status']      = true;
        return response()->json($responseArr);
        /*return success message end*/
    }
}
