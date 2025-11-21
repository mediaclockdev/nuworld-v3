@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      # Welcome, {{ $user->name }} 👋

      Thanks for signing up with **Mayuri**.

      We're excited to have you onboard.
      You can now log in and start exploring our collection.

      <a href="{{ route('signuplogin') }}"
        style="
          display: inline-block;
          padding: 10px 20px;
          background-color: #3490dc;
          color: white;
          text-decoration: none;
          border-radius: 5px;
          font-weight: bold;
      ">
        Login Now
      </a>

      Happy Shopping!
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
