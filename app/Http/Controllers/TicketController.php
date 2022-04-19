<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Department;
use App\Models\TicketAnsweringAgent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Jobs\SendTicketMessageMail;

class TicketController extends Controller
{
    public function customerList()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(20);

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
        return Redirect()->route('customer.ticket.list')->withSuccess(__('messages.ticket_added_successfully'));
    }

    public function customerView($id)
    {
        $ticket = Ticket::whereId($id)->where('user_id', Auth::user()->id)->first() ?? abort(404, 'Ticket not found');
        return view('ticket/customer/view', compact('ticket'));
    }

    public function customerMessageStore($id, Request $request)
    {
        //check exist?
        $ticket = Ticket::whereId($id)->where('user_id', Auth::user()->id)->first() ?? abort(404, 'Ticket not found');

        //store
        $ticket->status = 'active';
        $ticket->save();
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
        ]);

        //redirect
        return Redirect()->route('customer.ticket.list')->withSuccess(__('messages.message_sent_successfully'));
    }

    public function agentList()
    {
        Auth::user()->department;
        $userDepartmentIdArray = array();
        foreach (Auth::user()->department as $userDepartment) {
            $userDepartmentIdArray[] = $userDepartment->department_id;
        }
        $tickets = Ticket::whereIn('department_id', $userDepartmentIdArray)->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(20);
        $expiresDateTime = Carbon::now()->addMinutes(-15);

        return view('ticket/agent/list', compact('tickets','expiresDateTime'));
    }

    public function agentView($id)
    {
        $ticket = Ticket::whereId($id)->first() ?? abort(404, 'Ticket not found');

        /*check any agent is answering?*/
        $expiresDateTime = Carbon::now()->addMinutes(-15);
        $ticketAnsweringAgent = $ticket->ticketAnsweringAgent
            ->where('created_at', '>', $expiresDateTime)
            ->where('user_id', '!=', Auth::user()->id)
            ->first();

        if ($ticketAnsweringAgent) {
            //another agent answering
            return Redirect()->back()->withErrors(__('messages.another_agent_answering'));
        } else {
            //no one answering
            TicketAnsweringAgent::create([
                'ticket_id' => $id,
                'user_id' => Auth::user()->id,
            ]);
        }
        /*check any agent is answering? finish*/

        return view('ticket/agent/view', compact('ticket'));
    }

    public function agentMessageStore($id, Request $request)
    {
        //check exist?
        $ticket = Ticket::whereId($id)->first() ?? abort(404, 'Ticket not found');

        //store
        $ticket->status = 'closed';
        $ticket->save();
        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->id,
            'message' => $request['message'],
        ]);

        //job for email
        SendTicketMessageMail::dispatch($ticketMessage->id);

        //redirect
        return Redirect()->route('agent.ticket.list')->withSuccess(__('messages.message_sent_successfully'));
    }

    public function readEmailCron()
    {
        $inbox = imap_open("{".env('MAIL_HOST').":143/imap/notls}INBOX", env('MAIL_USERNAME'), env('MAIL_PASSWORD')) or die('Cannot connect to email: ' . imap_last_error());
        $emails = imap_search($inbox, 'ALL');

        if ($emails) {
            rsort($emails);
            foreach ($emails as $msg_number) {
                $header = imap_headerinfo($inbox, $msg_number);
                $message = imap_body($inbox, $msg_number);

                /*fetch info for ticket*/
                //get from email
                $emailAddress = $header->sender[0]->mailbox . '@' . $header->sender[0]->host;

                //prepare
                $subject = $this->parseEmailSubject($header->subject);
                $ticket = $this->findTicketIfExist($this->parseEmailSubject($header->subject), $emailAddress);
                $message = $this->parseEmailBody($message);
                /*fetch info for ticket finish*/

                /*store*/
                if ($ticket !== false) {
                    //ticket exist; update ticket status and add message only
                    $ticket->status = 'active';
                    $ticket->save();
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $ticket->user_id,
                        'message' => $message,
                    ]);
                } else {
                    //new ticket
                    $ticketUser = User::where('email', $emailAddress)->first();
                    if ($ticketUser) {
                        //user exist; add ticket and add message
                        $ticket = Ticket::create([
                            'user_id' => $ticketUser->id,
                            'department_id' => 2,
                            'status' => 'active',
                            'importance' => 5,
                            'title' => $subject,
                        ]);
                        $ticketMessage = TicketMessage::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $ticketUser->id,
                            'message' => $message,
                        ]);
                    } else {
                        //new user; add user, add ticket and add message
                        $newUser = User::create([
                            'name' => $emailAddress,
                            'email' => $emailAddress,
                            'type' => 'guest',
                            'password' => '12345678',
                        ]);
                        $ticket = Ticket::create([
                            'user_id' => $newUser->id,
                            'department_id' => 2,
                            'status' => 'active',
                            'importance' => 5,
                            'title' => $subject,
                        ]);
                        $ticketMessage = TicketMessage::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $newUser->id,
                            'message' => $message,
                        ]);
                    }
                }
                /*store finish*/

                /*delete email*/
                imap_delete($inbox, $msg_number);
                imap_expunge($inbox);
                /*delete email finish*/
            }
        }
    }

    private function parseEmailBody($emailBody)
    {
        /*decode if encode with base64*/
        if (str_contains($emailBody, 'Content-Transfer-Encoding: base64') !== false) {
            $emailBodyParsed = explode('Content-Transfer-Encoding: base64', $emailBody);
            $emailBodyParsed = explode('==', $emailBodyParsed[1]);
            $emailBodyParsed = base64_decode(trim($emailBodyParsed[0]) . '==');
        } elseif (str_contains($emailBody, 'Content-Transfer-Encoding: quoted-printable') !== false) {
            $emailBodyParsed = explode('Content-Transfer-Encoding: quoted-printable', $emailBody);
            $emailBodyParsed = explode('--', $emailBodyParsed[1]);
            $emailBodyParsed = quoted_printable_decode(trim($emailBodyParsed[0]));
        } elseif (str_contains($emailBody, 'Content-Transfer-Encoding: 8bit') !== false) {
            $emailBodyParsed = explode('Content-Transfer-Encoding: 8bit', $emailBody);
            $emailBodyParsed = trim($emailBodyParsed[1]);
        } else {
            $emailBodyParsed = $emailBody;
        }
        /*decode if encode with base64 finish*/

        /*clean wrote line*/
        if (str_contains($emailBodyParsed, ":\r\n>") !== false or str_contains($emailBodyParsed, ":\r\n\n>") !== false) {
            if (str_contains($emailBodyParsed, ":\r\n\r\n>") !== false) {
                $emailBodyParsed = explode(":\r\n\r\n>", $emailBodyParsed);
            } elseif (str_contains($emailBodyParsed, ":\r\n>") !== false) {
                $emailBodyParsed = explode(":\r\n>", $emailBodyParsed);
            }
            $emailBodyParsed = trim($emailBodyParsed[0]);

            //remove last line
            $emailBodyParsed = explode("\n", $emailBodyParsed);
            $lastLine = sizeof($emailBodyParsed) - 1;
            unset($emailBodyParsed[$lastLine]);
            $emailBodyParsed = implode("\n", $emailBodyParsed);
        }
        /*clean wrote line finish*/

        return trim($emailBodyParsed);
    }

    private function parseEmailSubject($emailSubject)
    {
        /*decode if encode with imap utf8*/
        if (str_contains($emailSubject, '?UTF-8?Q?') !== false) {
            $emailSubjectParsed = imap_utf8($emailSubject);
        } else {
            $emailSubjectParsed = $emailSubject;
        }
        /*decode if encode with imap utf8 finish*/

        /*remove unnecessary things*/
        $unnecessaryWordsArray = array('Fwd:', 'Re:');
        $emailSubjectParsed = str_replace($unnecessaryWordsArray, '', $emailSubjectParsed);
        /*remove unnecessary things finish*/

        return $emailSubjectParsed;
    }

    private function findTicketIfExist($emailSubject, $fromEmail)
    {
        //parse
        $emailSubjectParsed = explode('##', $emailSubject);
        if (isset($emailSubjectParsed[1])) {
            $ticketId = $emailSubjectParsed[1];

            /*check fromemail and ticket is match*/
            $ticketUser = User::where('email', $fromEmail)->first();
            $ticket = Ticket::whereId($ticketId)->first();

            if (!$ticketUser or !$ticket) {
                return false;
            }

            if ($ticketUser->id !== $ticket->user_id) {
                return false;
            } else {
                return $ticket;
            }
            /*check fromemail and ticket is match finish*/
        } else {
            return false;
        }
    }
}
