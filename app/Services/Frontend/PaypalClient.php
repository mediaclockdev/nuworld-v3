<?php

namespace App\Services\Frontend;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use App\Models\PaymentSettings;

class PaypalClient
{

  public static function client()
  {
    return new PayPalHttpClient(self::environment());
  }

  public static function environment()
  {
    $mode = PaymentSettings::where('gateway_name', 'paypal')->value('gateway_mode') ?? 'sandbox';
    $clientId = PaymentSettings::where([['gateway_name', 'paypal'], ['gateway_mode', $mode]])->value('gateway_key');
    $clientSecret = PaymentSettings::where([['gateway_name', 'paypal'], ['gateway_mode', $mode]])->value('gateway_secret');

    if ($mode === 'sandbox') {
      return new SandboxEnvironment($clientId, $clientSecret);
    }

    return new ProductionEnvironment($clientId, $clientSecret);
  }
}
