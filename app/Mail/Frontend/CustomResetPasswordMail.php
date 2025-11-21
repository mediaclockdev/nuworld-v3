<?php

namespace App\Mail\Frontend;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class CustomResetPasswordMail extends Mailable
{
	use Queueable, SerializesModels;

	public $token;
	public $user;

	/**
	 * Create a new message instance.
	 */
	public function __construct($token, User $user)
	{
		$this->token = $token;
		$this->user = $user;
	}

	/**
	 * Build the message.
	 */
	public function build()
	{
		$resetUrl = url("/reset-password/{$this->token}?email=" . urlencode($this->user->email));

		return $this->subject('Reset Your Password')
			->view('emails.frontend.custom-reset-password')
			->with([
				'name' => $this->user->name,
				'resetUrl' => $resetUrl,
			]);
	}
}
