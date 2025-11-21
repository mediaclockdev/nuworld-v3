<div dir="ltr"
  style="margin:0px;width:100%;background-color:#f3f2f0;padding:0px;padding-top:18px;padding-bottom:18px;">
  <table border="0" cellpadding="0" cellspacing="0"
    style="width:800px;margin:0 auto;font-family: 'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,Segoe UI,sans-serif;line-height:1.2;font-size:18px;background:#fff;">
    <tbody>
      <tr style="height:112px; text-align:center">
        <td colspan="3">
          {{-- Encode to base64 --}}
          @php
            $logoPath = str_replace('.svg', '.png', siteLogo());
          @endphp
          <img src="{{ $logoPath }}" alt="E-Commerce." />
        </td>
      </tr>

      <tr style="height:35px">
        <td colspan="3"></td>
      </tr>

      @yield('content')

      <tr valign="top">
        <td style="width:80px"></td>
        <td>
          <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
            <tbody>
              <tr>
                <td colspan="2">
                  <hr style="border:0; border-top:1px solid #ddd; margin: 0 0 25px;">
                </td>
              </tr>
              <tr>
                <td>
                  <p style="font-size:18px;line-height:26px;margin:0 0 5px;">Warm regards,</p>
                  <p style="font-size:18px;line-height:24px;margin:0;font-weight:bold;">{{ config('app.name') }}</p>
                </td>
                <td style="font-size:14px;vertical-align:bottom;padding-bottom:5px;text-align:right;">
                  <span style="font-family:arial; font-size:14px;">&copy;</span>
                  {{ date('Y') }} - {{ config('app.name') }}
                </td>
              </tr>
            </tbody>
          </table>
        </td>
        <td style="width:80px"></td>
      </tr>

      <tr style="height:50px">
        <td colspan="3"></td>
      </tr>
    </tbody>
  </table>
</div>
