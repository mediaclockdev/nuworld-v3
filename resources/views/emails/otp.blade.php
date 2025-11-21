@extends('backend.layouts.mail-layout')
@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:25px;font-weight:700;padding-bottom:15px;border-bottom:1px solid #000;margin:0 0 35px">
        Your One-Time-Password
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:18px;line-height:28px;margin:0 0 15px">Dear <span
          style="font-weight:700;color:#275DB3">{{ $name ?? '' }}</span>,</p>
      <p style="font-size:18px;line-height:26px;margin:0;letter-spacing:-.6px">We’ve received a login request for your
        account. To complete the process, please use the One-Time Password (OTP) below:</p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td style="width:640px; text-align:center">
      <p
        style="margin:35px 0;display:inline-block;padding:46px 0 35px;width:640px;font-size:52px; background-color: #f3f2f0; letter-spacing: 10px;">
        <span>{{ $otp }}</span>
      </p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:18px;line-height:26px;margin:0 0 10px;letter-spacing:-.6px">Please note that the OTP is valid
        for the next 10 minutes, and servers as your personal gateway to your account. Please do not disclose/share this
        OTP with anyone.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr>
    <td style="width:80px"></td>
    <td>
      <p style="font-size:14px;line-height:22px;margin:20px 0 10px; opacity: 0.6;">If you didn’t request this, please
        ignore this email or contact us immediately at <a href="mailto:{{ config('app.support_email') }}"
          style="color: #275DB3;">{{ config('app.support_email') }}</a>.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
  <tr style="height:30px">
    <td colspan="3"></td>
  </tr>
@endsection
