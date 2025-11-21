<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
  public function boot()
  {
    // Fetch mail settings from the database
    if (Schema::hasTable('mail_settings')) {
      $mailSetting = \App\Models\MailSetting::first();
      // Use the cached config to avoid hitting the database every time
      config(['mail.mailers.smtp' => $mailSetting ? $mailSetting->only([
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
      ]) : config('mail.mailers.smtp')]);
    }
  }

  public function register()
  {
    //
  }
}
