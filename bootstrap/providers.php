<?php

return [
  App\Providers\AppServiceProvider::class,
  App\Providers\ImageServiceProvider::class,
  App\Providers\MailConfigServiceProvider::class, // Sets up the mail config
  App\Providers\EmailServiceProvider::class, // Email service provider
  App\Providers\SendEmailServiceProvider::class, // Send email based on method call
  Jenssegers\Agent\AgentServiceProvider::class,
  Mews\Captcha\CaptchaServiceProvider::class,
];
