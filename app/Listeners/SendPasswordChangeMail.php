<?php

namespace App\Listeners;

use App\Events\OnPasswordChange;
use App\Mail\PasswordChangeEmail;
use App\Models\InfoBipModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPasswordChangeMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OnPasswordChange $event): void
    {
        $data = $event->data;
        //Mail::to($data["email"])->send(new PasswordChangeEmail($data));
        $html = view("mails.passwordChanged",compact('data'))->render();
        // InfoBipModel::SendEmail($data["email"],$html,"Your password has been changed");
    }
}
