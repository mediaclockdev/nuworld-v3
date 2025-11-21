<?php

namespace Database\Seeders;

use App\Models\PaymentSettings;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $gateways = [
      ['gateway_name' => 'Stripe', 'gateway_key' => 'pk_test_51RjaO64IdgNOYm4BrVkVyEsmWCBU5DIPAMBhBgJ2R0yYhJQLdPOqwP4cilDRBHXyN39GJvO0iQTjyJqQ4GnSWbMI00i8WPbEgb', 'gateway_secret' => 'sk_test_51RjaO64IdgNOYm4B1FSLQscKoNZqucqwdVVaQRwST72GokpjGAnGkoQcy427DFpG4MO0dTW4GuP9Jlkq9Ybc6zwM00MdHqPIfL', 'logo' => 'stripe.png', 'updated_by' => 1,  'gateway_mode' => 'test'],
      ['gateway_name' => 'Stripe', 'gateway_key' => '', 'gateway_secret' => '', 'logo' => 'stripe.png', 'updated_by' => 1,  'gateway_mode' => 'live'],
      ['gateway_name' => 'Paypal', 'gateway_key' => 'AXfR-_WeEyS7NKfXAChNFZ9ULi8XCzXNklzqZx6TRNqoLFj84nduJrPRmFsi7-r3ki6V55t_-lT5AeDv', 'gateway_secret' => 'EPT46qrz25vJzVXN8Nb0Kk0KWDa3wfMka3IcL5y8mGmo03QL3p5QylrTCqHOhkwMJ4GXmskDFIPDtOxv', 'logo' => 'paypal.png', 'updated_by' => 1, 'gateway_mode' => 'sandbox'],
      ['gateway_name' => 'Paypal', 'gateway_key' => '', 'gateway_secret' => '', 'logo' => 'paypal.png', 'updated_by' => 1, 'gateway_mode' => 'live'],
      ['gateway_name' => 'Razorpay', 'gateway_key' => '', 'gateway_secret' => '', 'logo' => 'razorpay.png', 'updated_by' => 1, 'gateway_mode' => 'test'],
      ['gateway_name' => 'Razorpay', 'gateway_key' => '', 'gateway_secret' => '', 'logo' => 'razorpay.png', 'updated_by' => 1, 'gateway_mode' => 'live'],
      ['gateway_name' => 'Cash On Delivery', 'gateway_key' => '', 'gateway_secret' => '', 'logo' => 'cod.png', 'updated_by' => 1, 'gateway_mode' => 'cod'],
    ];

    PaymentSettings::insert($gateways);
  }
}
