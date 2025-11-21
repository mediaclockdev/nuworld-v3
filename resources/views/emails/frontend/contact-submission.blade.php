@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p>Hello <b>{{ $data['name'] }}</b>,</p>
      <p>Thank you for reaching out to us! We have received your message and will get back to you as soon as possible.</p>
      <p>If you have any further questions, feel free to contact our support team.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
