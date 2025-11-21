@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p>Hello Guest,</p>
      <p>Thank you for subscribing to our newsletter!</p>
      <p>
        <a href="{{ $confirmationUrl }}"
          style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none;">
          Confirm Subscription
        </a>
      </p>
      <p>This link will expire in 60 minutes. If you didn’t request this, please ignore it.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
