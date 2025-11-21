@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p>Hello {{ $user->name }},</p>
      <p>You requested a password reset. Click the link below to reset your password:</p>
      <p>
        <a href="{{ url('/reset-password/' . $token) . '?email=' . urlencode($user->email) }}"
          style="color: #fff; background-color: #222; padding: 10px 15px; text-decoration: none;">
          Reset Password
        </a>
      </p>
      <p><strong>Important:</strong> This password reset link will expire in 60 minutes for your security.</p>
      <p>If you didn't request this, please ignore this email.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
