<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Mail\Mailer;
use Illuminate\Auth\Events\Registered;

class RegisteredListener
{
    private $mailer;
    private $eloquent;

    public function __construct(Mailer $mailer, User $eloquent)
    {
        $this->mailer = $mailer;
        $this->eloquent = $eloquent;
    }

    public function handle(Registered $event)
    {
        $user = $this->eloquent->findOrfail($event->user->getAuthIdentifier());
        $this->mailer->raw('registered successfully!', function ($message) use ($user){
            $message->subject('your registration')->to($user->email);
        });
    }
}