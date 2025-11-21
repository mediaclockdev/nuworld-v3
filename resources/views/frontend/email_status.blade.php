<div style="text-align:center; padding:50px;">
  <h2>Email Confirmation</h2>

  <div
    style="
      display: inline-block;
      padding: 15px 25px;
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      border-radius: 6px;
      font-size: 16px;
      max-width: 500px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-bottom: 30px;
  ">
    {{ $message }}
  </div>

  {{-- Subscribe button (disabled or informative in confirmation) --}}
  <button
    style="
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 25px;
      font-size: 16px;
      border-radius: 5px;
      cursor: not-allowed;
      margin-bottom: 15px;
  "
    disabled>
    Subscribed
  </button>
  <br>

  {{-- Home Page button --}}
  <a href="{{ url('/') }}"
    style="
      display: inline-block;
      padding: 10px 25px;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s;
  "
    onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
    Go to Home Page
  </a>
</div>
