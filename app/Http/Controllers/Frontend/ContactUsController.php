<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactUsRequest;
use App\Models\SiteSetting;
use App\Models\Support;
use Illuminate\Support\Facades\RateLimiter;

class ContactUsController extends Controller
{
  public function saveContactInformation(ContactUsRequest $request)
  {

    $key = 'contact-us:' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 3)) {
      return response()->json([
        'success' => false,
        'message' => 'Too many attempts. Please try again later.'
      ], 429);
    }

    RateLimiter::hit($key, 60);

    $validated = $request->validated();
    $name = $validated['first_name'] . ($validated['last_name'] ? ' ' . $validated['last_name'] : '');
    $sanitizedMessage = strip_tags($validated['message']);

    Support::create([
      'first_name' => $validated['first_name'],
      'last_name'  => $validated['last_name'],
      'email'      => $validated['email'],
      'message'    => $sanitizedMessage,
    ]);

    // Prepare data for the email
    $data = [
      'name'    => $name,
      'email'   => $validated['email'],
      'message' => $sanitizedMessage,
    ];

    app('EmailService')->sendEmail(
      $validated['email'],
      'Thank You for Contacting Us!',
      'emails.frontend.contact-submission',
      ['data' => $data],
      [],
      adminMailsByRoleID([SiteSetting::where('key', 'order_copy_to_id')->value('value') ?? 1])
    );

    return response()->json([
      'success' => true,
      'message' => 'Your message has been sent successfully!'
    ]);
  }
}
