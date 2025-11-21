@extends('backend.layouts.mail-layout')
@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p
        style="font-size:28px;font-weight:700;padding-bottom:20px;border-bottom:2px solid #275DB3;margin:0 0 40px; color: #275DB3;">
        Welcome to {{ config('app.name') }}!
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:18px;line-height:28px;margin:0 0 15px;color:#333;">
        Hi <span style="font-weight:700;color:#275DB3">{{ $name }}</span>,
      </p>
      <p style="font-size:18px;line-height:30px;margin:0 0 15px;color:#333;">
        We are thrilled to welcome you to the {{ config('app.name') }} Portal! Let’s get started with your journey.
      </p>
      <p style="font-size:18px;line-height:30px;margin:0 0 15px;color:#333;">
        Please use the details below to log in and explore all that we have to offer:
      </p>
      <p style="font-size:16px;line-height:24px;margin:0 0 15px;color:#555;">
        <strong>Email:</strong> {{ $email }}<br>
        <strong>Temporary Password:</strong> {{ $password }} (Please change your password after login)
      </p>
      <p style="text-align: center; margin: 25px 0;">
        <a href="{{ url('admin/login') }}"
          style="background-color: #275DB3; border-radius:5px; padding:15px 35px; font-size:18px; text-decoration:none; color:#fff;">Login
          to Your Account</a>
      </p>
      <p style="font-size:16px;line-height:24px;margin:0;color:#555;">
        If you need help logging in or have any questions, please don’t hesitate to reach out to us.
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr style="height:25px">
    <td colspan="3"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:14px;line-height:22px;margin:0 0 10px;color:#666;">
        You can contact our support team at any time via email at <a href="mailto:{{ config('app.support_email') }}"
          style="color:#275DB3; text-decoration:none;">{{ config('app.support_email') }}</a>.
      </p>
      <p style="font-size:15px;line-height:20px;margin:0 0 35px;color:#666;">
        We’re here to ensure you have the best experience possible.
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
