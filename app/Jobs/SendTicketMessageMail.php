<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TicketMessage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Str;

class SendTicketMessageMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticketMessage = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticketMessage)
    {
        $this->ticketMessage = $ticketMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*prepare data*/
        $ticketMessage = TicketMessage::whereId($this->ticketMessage)->first();
        $emailAddress = Str::replace('guest:','',$ticketMessage->ticket->user->email);
        $ticketTitle = $ticketMessage->ticket->title . ' | ' . env('APP_NAME');
        $ticketLastMessage = $ticketMessage->message;
        $ticketLastMessageDatetime = $ticketMessage->created_at;

        $emailBody = view('mail/ticket', [
            'ticketTitle' => $ticketTitle,
            'ticketLastMessageDatetime' => $ticketLastMessageDatetime,
            'ticketLastMessage' => $ticketLastMessage
        ]);
        /*prepare data finish*/

        /*send email*/
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_ADDRESS'));
            $mail->addAddress($emailAddress);
            $mail->isHTML(true);// Set email content format to HTML

            $mail->Subject = $ticketTitle . ' | ##' . $ticketMessage->ticket->id . '##';
            $mail->Body = $emailBody;
            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {
                //var_dump($mail->ErrorInfo);
            } else {
                //return back()->with("success", "Email has been sent.");
            }

        } catch (Exception $e) {
            //return back()->with('error','Message could not be sent.');
            echo $e->getMessage();
        }
        /*send email finish*/
    }
}
