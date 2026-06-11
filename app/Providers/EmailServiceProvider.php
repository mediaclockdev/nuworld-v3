<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Models\MailSetting;
use SendGrid\Mail\Mail as SendGridMail;


class EmailServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton('EmailService', function ($app) {
      return new class {
        /**
         * Sends an email using Laravel's Mail facade.
         *
         * @param string $email The recipient's email address.
         * @param string $subject The subject of the email.
         * @param string $view The name of the view to use for the email template.
         * @param array $data An associative array of data to pass to the view.
         *
         * @return void
         */
        // public function sendEmail(string $email, string $subject, string $view, array $data = [], array $cc = [], array $bcc = []): void
        // {
        //   try {
        //     $mailSetting = MailSetting::first();

        //     if ($mailSetting) {
        //       // Dynamically configure mail settings
        //       config([
        //         'mail.mailers.smtp' => [
        //           'transport' => $mailSetting->mail_mailer,
        //           'host' => $mailSetting->mail_host,
        //           'port' => $mailSetting->mail_port,
        //           'encryption' => $mailSetting->mail_encryption,
        //           'username' => $mailSetting->mail_username,
        //           'password' => $mailSetting->mail_password,
        //         ],
        //         'mail.from' => [
        //           'address' => $mailSetting->mail_from_address,
        //           'name' => $mailSetting->mail_from_name,
        //         ],
        //       ]);
        //     }

        //     Mail::send($view, $data, function ($message) use ($email, $subject, $cc, $bcc) {
        //       $message->to($email)->subject($subject);

        //       if (!empty($cc)) {
        //         $message->cc($cc);
        //       }

        //       if (!empty($bcc)) {
        //         $message->bcc($bcc);
        //       }
        //     });
        //   } catch (\Exception $e) {
        //     throw new \Exception('Email sending failed: ' . $e->getMessage());
        //   }
        // }


        public function sendEmail(
          string $email,
          string $subject,
          string $view,
          array $data = [],
          array $cc = [],
          array $bcc = []
        ): void {
          try {

            $html = view($view, $data)->render();

            $message = new SendGridMail();

            $message->setFrom(
              env('SENDGRID_FROM_EMAIL'),
              config('app.name')
            );

            $message->setSubject($subject);
            $message->addTo($email);

            foreach ($cc as $address) {
              $message->addCc($address);
            }

            foreach ($bcc as $address) {
              $message->addBcc($address);
            }

            $message->addContent(
              "text/html",
              $html
            );

            $sendgrid = new \SendGrid(
              env('SENDGRID_API_KEY')
            );

            $response = $sendgrid->send($message);

            if ($response->statusCode() >= 400) {
              throw new \Exception($response->body());
            }
          } catch (\Exception $e) {
            throw new \Exception(
              'Email sending failed: ' . $e->getMessage()
            );
          }
        }
      };
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
