<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Department;

class TicketController extends Controller
{
    public function customerList()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('status','asc')->orderBy('created_at','desc')->paginate(20);

        return view('ticket/customer/list', compact('tickets'));
    }

    public function customerAdd()
    {
        $departments = Department::get();
        return view('ticket/customer/add', compact('departments'));
    }

    public function customerStore(Request $request)
    {
        //validation
        $request->validate([
            'title' => 'required',
            'department' => 'required',
            'importance' => 'required',
            'message' => 'required',
        ]);

        //store
        $ticket = Ticket::create([
            'user_id' => Auth::user()->id,
            'department_id' => $request['department'],
            'status' => 'active',
            'importance' => $request['importance'],
            'title' => $request['title'],
        ]);
        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
        ]);

        //redirect
        return Redirect()->route('customer.ticket.list')->withSuccess('Ticket added successfully');
    }

    public function customerView($id)
    {
        $ticket = Ticket::whereId($id)->where('user_id',Auth::user()->id)->first() ?? abort(404, 'Ticket not found');
        return view('ticket/customer/view', compact('ticket'));
    }

    public function customerMessageStore($id, Request $request)
    {
        //check exist?
        $ticket = Ticket::whereId($id)->where('user_id',Auth::user()->id)->first() ?? abort(404, 'Ticket not found');

        //store
        $ticket->status = 'active';
        $ticket->save();
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
        ]);

        //redirect
        return Redirect()->route('customer.ticket.list')->withSuccess('Message sent successfully');
    }

    public function agentList()
    {
        Auth::user()->department;
        $userDepartmentIdArray = array();
        foreach (Auth::user()->department as $userDepartment) {
            $userDepartmentIdArray[] = $userDepartment->id;
        }
        $tickets = Ticket::whereIn('department_id', $userDepartmentIdArray)->orderBy('status','asc')->orderBy('created_at','desc')->paginate(20);

        return view('ticket/agent/list', compact('tickets'));
    }

    public function agentView($id)
    {
        $ticket = Ticket::whereId($id)->first() ?? abort(404, 'Ticket not found');
        return view('ticket/agent/view', compact('ticket'));
    }

    public function agentMessageStore($id, Request $request)
    {
        //check exist?
        $ticket = Ticket::whereId($id)->first() ?? abort(404, 'Ticket not found');

        //store
        $ticket->status = 'closed';
        $ticket->save();
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
        ]);

        //redirect
        return Redirect()->route('agent.ticket.list')->withSuccess('Message sent successfully');
    }
}
