<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $data = [
            'active_ticket_count' => $this->activeTicketCount(),
            'ticket_count_last_7days' => $this->ticketCountLast7days(),
            'message_count_last_7days' => $this->messageCountLast7days(),
            'customer_count' => $this->customerCount(),
            'guest_count' => $this->guestCount(),
            'agent_count' => $this->agentCount(),
            'last_30_days_ticket_counts' => $this->getLast30DaysTicketCounts(),
            'last_30_days_ticket_message_counts' => $this->getLast30DaysTicketMessageCounts(),
        ];
        return view('dashboard/admin', compact('data'));
    }

    public function agent()
    {
        $data = [
            'active_ticket_count' => $this->activeTicketCount(),
            'ticket_count_last_7days' => $this->ticketCountLast7days(),
            'message_count_last_7days' => $this->messageCountLast7days(),
        ];
        return view('dashboard/agent', compact('data'));
    }

    public function customer()
    {
        $data = [
            'active_ticket_count' => $this->activeTicketCount(),
            'ticket_count_last_7days' => $this->ticketCountLast7days(),
            'message_count_last_7days' => $this->messageCountLast7days(),
        ];
        return view('dashboard/customer', compact('data'));
    }

    private function activeTicketCount()
    {
        if (Auth::user()->type === 'admin') {
            $ticketCount = Ticket::where('status','active')->count();
        } elseif (Auth::user()->type === 'agent') {
            Auth::user()->department;
            $userDepartmentIdArray = array();
            foreach (Auth::user()->department as $userDepartment) {
                $userDepartmentIdArray[] = $userDepartment->id;
            }
            $ticketCount = Ticket::whereIn('department_id', $userDepartmentIdArray)->where('status','active')->count();
        } elseif (Auth::user()->type === 'customer') {
            $ticketCount = Ticket::where('status','active')->where('user_id',Auth::user()->id)->count();
        }
        return $ticketCount;
    }

    private function ticketCountLast7days()
    {
        if (Auth::user()->type === 'admin') {
            $ticketCount = Ticket::where('created_at', '>=', self::dateBeforeDays(7))->count();
        } elseif (Auth::user()->type === 'agent') {
            Auth::user()->department;
            $userDepartmentIdArray = array();
            foreach (Auth::user()->department as $userDepartment) {
                $userDepartmentIdArray[] = $userDepartment->id;
            }
            $ticketCount = Ticket::where('created_at', '>=', self::dateBeforeDays(7))
                ->whereIn('department_id', $userDepartmentIdArray)->count();
        } elseif (Auth::user()->type === 'customer') {
            $ticketCount = Ticket::where('user_id',Auth::user()->id)->count();
        }
        return $ticketCount;
    }

    private function messageCountLast7days()
    {
        if (Auth::user()->type === 'admin') {
            $messageCount = TicketMessage::where('created_at', '>=', self::dateBeforeDays(7))->count();
        } elseif (Auth::user()->type === 'agent') {
            $messageCount = TicketMessage::where('created_at', '>=', self::dateBeforeDays(7))
                ->where('user_id',Auth::user()->id)->count();
        } elseif (Auth::user()->type === 'customer') {
            $messageCount = TicketMessage::where('created_at', '>=', self::dateBeforeDays(7))
                ->where('user_id',Auth::user()->id)->count();
        }
        return $messageCount;
    }

    private function customerCount()
    {
        return User::where('type','customer')->count();
    }

    private function guestCount()
    {
        return User::where('type','guest')->count();
    }

    private function agentCount()
    {
        return User::where('type','agent')->count();
    }

    private function getLast30DaysTicketCounts()
    {
        $dataDayByDay = $this->createArray30Days();

        $datas = Ticket::select(DB::raw('Date(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', self::dateBeforeDays(30))
            ->groupBy(DB::raw('Date(created_at)'))
            ->get();

        foreach ($datas as $data)
        {
            $dataDayByDay['data'][$data->date] = $data->count;
        }

        return $dataDayByDay;
    }

    private function getLast30DaysTicketMessageCounts()
    {
        $dataDayByDay = $this->createArray30Days();

        $datas = TicketMessage::select(DB::raw('Date(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', self::dateBeforeDays(30))
            ->groupBy(DB::raw('Date(created_at)'))
            ->get();

        foreach ($datas as $data)
        {
            $dataDayByDay['data'][$data->date] = $data->count;
        }

        return $dataDayByDay;
    }

    private function dateBeforeDays(int $number)
    {
        return Carbon::now()->subDay($number)->toDateString();
    }

    private function createArray30Days()
    {
        $dataDayByDay = array();
        for($i=29;$i>=0;$i--){
            $dataDayByDay['data'][$this->dateBeforeDays($i)] = 0;
            $dataDayByDay['date'][] = $this->dateBeforeDays($i);
        }
        return $dataDayByDay;
    }
}
