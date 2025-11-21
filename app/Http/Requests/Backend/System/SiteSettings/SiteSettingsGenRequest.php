<?php

namespace App\Http\Requests\Backend\System\SiteSettings;

use App\Http\Requests\BaseRequest;

class SiteSettingsGenRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'siteurl' => 'required|url',
      'sitename' => 'required|max:255',
      'site_email' => 'required|email',
      'site_primary_phone' => 'required',
      'site_secondary_phone' => 'nullable',
      'tax_no' => 'nullable',
      'address1' => 'nullable',
      'address2' => 'nullable',
      'landmark' => 'nullable',
      'city' => 'nullable',
      'state' => 'nullable',
      'country' => 'nullable',
      'zip_code' => 'nullable',
      'currency' => 'nullable',
      'order_copy_to' => 'nullable',
      'threshold_mails' => 'nullable',
      'facebook_link' => 'nullable|url',
      'x_link' => 'nullable|url',
      'instagram_link' => 'nullable|url',
      'youtube_link' => 'nullable|url',
      'linkedin_link' => 'nullable|url',
      'google_map' => 'nullable',
      'google_play_link' => 'nullable|max:200',
      'apple_app_link' => 'nullable|max:200',
      'two_factor_auth' => 'nullable|in:1,2',
      'payment_gateway' => 'nullable|in:1,2',
    ];
  }

  public function messages(): array
  {
    return [
      'siteurl.required' => __('validation.required', ['attribute' => 'Site URL']),
      'siteurl.url' => __('validation.invalid', ['attribute' => 'Site URL']),
      'sitename.required' => __('validation.required', ['attribute' => 'Site Name']),
      'site_email.required' => __('validation.required', ['attribute' => 'Email Address']),
      'site_email.email' => __('validation.email', ['attribute' => 'Email Address']),
      'site_primary_phone.required' => __('validation.required', ['attribute' => 'Primary Phone']),
      'facebook_link.url' => __('validation.invalid', ['attribute' => 'Facebook Link']),
      'x_link.url' => __('validation.invalid', ['attribute' => 'X Link']),
      'instagram_link.url' => __('validation.invalid', ['attribute' => 'Instagram Link']),
      'youtube_link.url' => __('validation.invalid', ['attribute' => 'Youtube Link']),
      'linkedin_link.url' => __('validation.invalid', ['attribute' => 'Linkedin Link']),
      'google_play_link.max' => __('validation.maxlength', ['attribute' => 'Google Play Link', 'max' => 200]),
      'apple_app_link.max' => __('validation.maxlength', ['attribute' => 'App Store Link', 'max' => 200]),
    ];
  }
}
