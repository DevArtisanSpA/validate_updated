<?php

namespace App\Mail;

use App\Models\User;

use URL;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailWelcome extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * The User instance.
   *
   * @var User
   */
  public $user;
  public $url;
  public $expire_minutes;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($token, User $user)
  {
    $base_url = URL::to('/');
    $this->url = $base_url . "/password/reset/" . $token . '?email=' . $user->email;
    $this->user = $user;
    $this->expire_minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
}

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject(ucwords(strtolower($this->user->name)) . " te damos la bienvenida a validate.cl")
      ->view('emails.mail_welcome');
  }
}
