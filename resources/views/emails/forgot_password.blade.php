@extends('backend.layouts.mail-layout')
@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:25px; font-weight:700; padding-bottom:15px; border-bottom:1px solid #000; margin:0 0 35px;">
        Forgot Password | {{ config('app.name') }}
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:18px; line-height:28px; margin:0 0 15px;">
        Dear <span style="font-weight:700; color:#275DB3;">{{ $name }}</span>,
      </p>
      <p style="font-size:18px; line-height:26px; margin:0 0 35px;">
        We’ve received a request to reset your password for the {{ config('app.name') }} Portal. To proceed, please click
        the link below:
      </p>
      <p style="font-size:18px; line-height:26px; margin:0 0 35px; word-break:break-all; text-align: center">
        <a href={{ $resetUrl }}
          style="background-color:#275DB3 !important; border-radius:0px; margin-top:15px; padding:15px 35px; font-size:18px; text-decoration:none; color:#fff;">
          Reset Password
        </a>
      </p>

      <p style="font-size:18px; line-height:26px; margin:35px 0 0;">
        This link will take you to the password reset page, where you can create a new password. The link is valid for the
        next 1 hour.
      </p>

      <p style="font-size:18px; line-height:26px; margin:35px 0 35px;">
        Once you’ve reset your password, please visit the login page and sign in using your email and the newly created
        password.
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:18px; line-height:26px; margin:0; letter-spacing:-0.6px;">
        <strong>Activity Details:</strong>
      </p>
      <table style="width:100%; border-collapse:collapse; margin:20px 0;">
        <tr>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">Date and Time</td>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
            {{ $date_time }} (UTC)
          </td>
        </tr>
        <tr>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">Device</td>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
            {{ ucfirst($device) }}
          </td>
        </tr>
        <tr>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">Platform</td>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
            {{ $os }}
          </td>
        </tr>
        <tr>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">Browser</td>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
            {{ $browser }}
          </td>
        </tr>
        <tr>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">IP Address</td>
          <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
            {{ $ip_address }}
          </td>
        </tr>
        @if ($location)
          <tr>
            <td style="padding:8px; font-size:16px; border:1px solid #ddd; font-weight:bold;">Location</td>
            <td style="padding:8px; font-size:16px; border:1px solid #ddd;">
              {{ $location }}
            </td>
          </tr>
        @endif
      </table>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr style="height:10px">
    <td colspan="3"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:14px;line-height:22px;margin:0 0 10px; opacity: 0.6;">If you didn’t request a password reset,
        please ignore this email or contact us at <a href="mailto:{{ config('app.support_email') }}"
          style="color: #275DB3;">{{ config('app.support_email') }}</a> for assistance.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
