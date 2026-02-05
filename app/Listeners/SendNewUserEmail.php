<?php

namespace App\Listeners;

use App\Events\OnNewUserCreation;
use App\Mail\NewUserWelcomeEmail;
use App\Models\InfoBipModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewUserEmail
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */

    /**
     * Handle the event.
     */
    public function handle(OnNewUserCreation $event): void
    {

        $data = $event->data;
       // Mail::to($data["email"])->send(new NewUserWelcomeEmail($data));
       $html = view("mails.welcome",compact('data'))->render();
    //    InfoBipModel::SendEmail($data["email"],$html,"Welcome Pm247");
    //   InfoBipModel::SendEmail($data["email"],$html,"Agent Assigned");
    }
}
