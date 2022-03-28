<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function departmentView(Request $request)
    {
        $departments = Department::orderBy('name', 'asc')->get();

        //form data
        $form['start_date'] = Carbon::now()->toDateString();
        $form['finish_date'] = Carbon::now()->toDateString();
        $form['department'] = 'all';

        //calculating report data
        if (isset($request['start_date']) and isset($request['finish_date']) and isset($request['department'])) {
            //form data
            $form['start_date'] = $request['start_date'];
            $form['finish_date'] = $request['finish_date'];
            $form['department'] = $request['department'];

            //variable sets
            $startDate = Carbon::parse($request['start_date']);
            $finishDate = Carbon::parse($request['finish_date']);
            $totalDays = $finishDate->diffInDays($startDate);

            /*calculate data day by day*/
            $reportData = array();
            for ($i = 0; $i <= $totalDays; $i++) {
                //department choice
                if ($request->department !== 'all') {
                    Department::whereId($request->department)->first() ?? abort(404, 'Department not found');
                }

                //calculate datas
                $dayDate = Carbon::parse($request['start_date'])->addDays($i)->format('Y-m-d');
                $ticketCount = self::getTicketCountByDepartment($dayDate, 'all', $request->department);
                $activeTicketCount = self::getTicketCountByDepartment($dayDate, 'active', $request->department);
                $closedTicketCount = self::getTicketCountByDepartment($dayDate, 'closed', $request->department);
                $messageCount = self::getMessageCountByDepartment($dayDate, $request->department);
                if ($ticketCount > 0) {
                    $ratioTicketCount = ($closedTicketCount / $ticketCount) * 100;
                } else {
                    $ratioTicketCount = 0;
                }

                /*return data*/
                $reportData[] = [
                    'date' => $dayDate,
                    'ticket_count' => $ticketCount,
                    'active_ticket_count' => $activeTicketCount,
                    'closed_ticket_count' => $closedTicketCount,
                    'ratio_ticket_count' => $ratioTicketCount,
                    'message_count' => $messageCount,
                ];
                /*return data finish*/
            }
            /*calculate data day by day finish*/

            //return $reportData;
            return view('report/department', compact('departments', 'form', 'reportData', 'request'));
        }

        return view('report/department', compact('departments', 'form'));
    }

    public function agentView(Request $request)
    {
        $agents = User::where('type', 'agent')->orderBy('name', 'asc')->get();

        //form data
        $form['start_date'] = Carbon::now()->toDateString();
        $form['finish_date'] = Carbon::now()->toDateString();
        $form['agent'] = 'all';

        //calculating report data
        if (isset($request['start_date']) and isset($request['finish_date']) and isset($request['agent'])) {
            //form data
            $form['start_date'] = $request['start_date'];
            $form['finish_date'] = $request['finish_date'];
            $form['department'] = $request['agent'];

            //variable sets
            $startDate = Carbon::parse($request['start_date']);
            $finishDate = Carbon::parse($request['finish_date']);
            $totalDays = $finishDate->diffInDays($startDate);

            /*calculate data day by day*/
            $reportData = array();
            for ($i = 0; $i <= $totalDays; $i++) {
                //department choice
                if ($request->agent !== 'all') {
                    User::whereId($request->agent)->where('type', 'agent')->first() ?? abort(404, 'Agent not found');
                }

                //calculate datas
                $dayDate = Carbon::parse($request['start_date'])->addDays($i)->format('Y-m-d');
                $ticketCount = self::getTicketCountByAgent($dayDate, 'all', $request->agent);
                $activeTicketCount = self::getTicketCountByAgent($dayDate, 'active', $request->agent);
                $closedTicketCount = self::getTicketCountByAgent($dayDate, 'closed', $request->agent);
                $messageCount = self::getMessageCountByAgent($dayDate, $request->agent);
                if ($ticketCount > 0) {
                    $ratioTicketCount = ($closedTicketCount / $ticketCount) * 100;
                } else {
                    $ratioTicketCount = 0;
                }

                /*return data*/
                $reportData[] = [
                    'date' => $dayDate,
                    'ticket_count' => $ticketCount,
                    'active_ticket_count' => $activeTicketCount,
                    'closed_ticket_count' => $closedTicketCount,
                    'ratio_ticket_count' => $ratioTicketCount,
                    'message_count' => $messageCount,
                ];
                /*return data finish*/
            }
            /*calculate data day by day finish*/

            //return $reportData;
            return view('report/agent', compact('agents', 'form', 'reportData', 'request'));
        }

        return view('report/agent', compact('agents', 'form'));
    }

    private function getTicketCountByDepartment($date, $status = 'all', $department)
    {
        //date
        $ticket = Ticket::where('created_at', 'like', $date . '%');
        //ticket status
        if ($status == 'active') {
            $ticket->where('status', 'active');
        } elseif ($status == 'closed') {
            $ticket->where('status', 'closed');
        }
        //department
        if ($department !== 'all') {
            $ticket->where('department_id', $department);
        }
        return $ticket->count();
    }

    private function getMessageCountByDepartment($date, $department)
    {
        if ($department !== 'all') {
            return TicketMessage::join('tickets', 'tickets.id', '=', 'ticket_messages.ticket_id')
                ->where('department_id', $department)
                ->where('ticket_messages.created_at', 'like', $date . '%')->count();
        } else {
            return TicketMessage::where('created_at', 'like', $date . '%')->count();
        }
    }

    private function getTicketCountByAgent($date, $status = 'all', $agent)
    {
        //date
        $ticket = Ticket::select(DB::raw('count(*) as total'))->join('ticket_messages', 'ticket_messages.ticket_id', '=', 'tickets.id')
            ->where('ticket_messages.created_at', 'like', $date . '%');
        //ticket status
        if ($status == 'active') {
            $ticket->where('status', 'active');
        } elseif ($status == 'closed') {
            $ticket->where('status', 'closed');
        }
        //department
        if ($agent !== 'all') {
            $ticket->where('ticket_messages.user_id', $agent);
        }
        $ticket->groupBy('ticket_messages.ticket_id');
        return $ticket->get()->count();
    }

    private function getMessageCountByAgent($date, $agent)
    {
        if ($agent !== 'all') {
            return TicketMessage::where('user_id', $agent)
                ->where('ticket_messages.created_at', 'like', $date . '%')->count();
        } else {
            return TicketMessage::where('created_at', 'like', $date . '%')->count();
        }
    }
}
