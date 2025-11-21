<div dir="ltr"
  style="margin:0px;width:100%;background-color:#f3f2f0;padding:0px;padding-top:18px;padding-bottom:18px;">
  <table border="0" cellpadding="0" cellspacing="0"
    style="width:800px;margin:0 auto;font-family: 'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,Segoe UI,sans-serif;line-height:1.2; font-size: 18px; background: #fff;">
    <tbody>
      @include('backend.includes.mail-header')
      @yield('content')
      @include('backend.includes.mail-footer')
    </tbody>
  </table>
</div>
