<?php

return [
  'auth' => [
    'unauthorized' => 'Unauthorized',
    'not_registered' => ':item is not registered',
    'not_authorized' => 'You are not authorized to perform this action',
    'failed' => 'Invalid credentials',
    'suspended' => 'The account is currently suspended',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'logout' => 'Logout successfully',
    'login' => 'Login successfully',
    'token' => [
      'invalid' => 'Invalid token',
      'expired' => 'Token has expired',
      'create' => 'Failed to create token',
      'refresh' => 'Token refreshed successfully',
    ],
    'password' => [
      'forgot' => 'Check your email to reset your password.',
      'link_sent' => 'Password reset link sent successfully',
      'update' => 'Password updated successfully',
      'change' => 'Password changed successfully',
      'error' => 'Failed to update password',
      'last_password' => 'New password cannot be same as Last password',
      'same_current_password' => 'New password cannot be the same as the current password. Please enter a different password.',
    ],
  ],
  'not_found' => ':item not found',
  'error' => [
    'create' => 'Failed to create :item',
    'update' => 'Failed to update :item',
    'delete' => 'Failed to delete :item',
    'not_match' => ':item is not matching',
    'duplicate' => ':item already exists',
    'prohibited' => ':item is prohibited',
    'not_allowed' => ':item is not allowed',
    'has_associated' => ':item1 has associated :item2',
    'in_correct' => ':item is incorrect',
    'expired' => ':item is expired',
    'not_serviceable' => ':item is not serviceable',
  ],
  'success' => [
    'create' => ':item Created Successfully',
    'update' => ':item Updated Successfully',
    'delete' => ':item Deleted Successfully',
    'upload' => ':item uploaded successfully',
    'submit' => ':item submitted successfully',
    'generate' => ':item generated successfully',
    'sent' => ':item sent successfully',
    'decode' => ':item decoded successfully',
    'refresh' => ':item refreshed successfully',
    'fetch' => ':item fetched successfully',
    'remove' => ':item removed successfully',
    'add' => ':item added successfully',
    'serviceable' => ':item is serviceable',
  ],

  'otp' => [
    'error' => [
      'invalid' => 'OTP is invalid',
      'expired' => 'OTP is expired',
      'create' => 'Failed to create OTP',
      'too_many_requests' => 'Too many OTP requests. Please try again in :seconds seconds.',
    ],
    'success' => [
      'verified' => 'OTP is verified',
      'sent' => [
        'email' => 'OTP has been sent to your email address.',
        'phone' => 'OTP has been sent to your phone number.',
      ],
    ],
  ],
  'confirm' => [
    'title' => 'Are you sure to :action ?',
    'text' => 'You won\'t be able to revert this!',
  ],
  'email' => [
    'sent' => 'Email sent successfully',
    'resent' => ':item resent successfully',
    'error' => 'Failed to send email',
    'failed' => ':item sending failed',
  ],
];
