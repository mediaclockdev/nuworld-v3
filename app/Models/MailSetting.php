<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class MailSetting extends Model
{
  protected $fillable = [
    'mail_mailer',
    'mail_host',
    'mail_port',
    'mail_username',
    'mail_password',
    'mail_encryption',
    'mail_from_address',
    'mail_from_name',
  ];

  public static function updateMailConfig($data): bool
  {
    $id = self::value('id');
    $model = self::updateOrCreate(['id' => $id], $data);

    return $model ? true : false;
  }
}
