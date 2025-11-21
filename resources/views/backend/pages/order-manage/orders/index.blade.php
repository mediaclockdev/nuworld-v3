@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
  <x-list-card :cardHeader="$cardHeader" :baseRoute="'admin.orders'">
    <livewire:order-manage.orders-table />
  </x-list-card>

  {{-- Chatbot UI --}}
  {{-- <div class="card mt-4">
    <div class="card-header">AI Chatbot Assistant</div>
    <div class="card-body" id="chat-box" style="height:300px; overflow-y:auto; background:#f9f9f9;">
      <div id="messages"></div>
    </div>
    <div class="card-footer d-flex">
      <input type="text" id="chat-input" class="form-control me-2" placeholder="Ask about orders or anything...">
      <button id="send-btn" class="btn btn-primary">Send</button>
    </div>
  </div> --}}
@endsection
@section('page-scripts')
  <script>
    $(document).ready(function() {
      $('#addBtn').attr('style', 'display: none !important');
      // Chatbot send message
      // $('#send-btn').on('click', function() {
      //   let msg = $('#chat-input').val();
      //   if (!msg) return;

      //   let messages = $('#messages');
      //   messages.append(`<div><b>You:</b> ${msg}</div>`);

      //   fetch("{{ route('admin.chatbot-ask') }}", {
      //       method: "POST",
      //       headers: {
      //         "Content-Type": "application/json",
      //         "X-CSRF-TOKEN": "{{ csrf_token() }}"
      //       },
      //       body: JSON.stringify({
      //         message: msg
      //       })
      //     })
      //     .then(res => res.json())
      //     .then(data => {
      //       messages.append(`<div><b>Bot:</b> ${data.reply}</div>`);
      //       $('#chat-input').val("");
      //       $('#chat-box').scrollTop(messages[0].scrollHeight);
      //     });
      // });
    });
  </script>
@endsection
