<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
      body {margin: 0; padding: 0; min-width: 100%!important;}
      img {height: auto;}
      .content {width: 100%; max-width: 600px;}
      .header {padding: 40px 30px 20px 30px;}
      .innerpadding {padding: 30px 30px 30px 30px;}
      .borderbottom {border-bottom: 1px solid #f2eeed;}
      .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 0px;}
      .h1, .h2, .bodycopy {color: #FFF; font-family: sans-serif;}
      .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
      .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
      .bodycopy {font-size: 16px; line-height: 22px;}
      .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
      .button a {color: #ffffff; text-decoration: none;}
      .footer {padding: 20px 30px 15px 30px;}
      .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
      .footercopy a {color: #ffffff; text-decoration: underline;}
      .admin_info{    background-color: rgba(230, 230, 230, 0.4);}

      @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
        body[yahoo] .hide {display: none!important;}
        body[yahoo] .buttonwrapper {background-color: transparent!important;}
        body[yahoo] .button {padding: 0px!important;}
        body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
        body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
      }

      @media only screen and (min-device-width: 601px) {
        .content {width: 600px !important;}
        .col425 {width: 425px!important;}
        .col380 {width: 380px!important;}
      }	

    </style>
  </head>

  <body yahoo bgcolor="#ffffff">
    <table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td bgcolor="#3F51B5" class="header">
                <div class="subhead" style="padding: 0 0 0 3px; color: white;">
                  apartamentosierranevada.net
                </div>
                <h1 style="font-weight: bold;color: #fff;margin: 5px 0;font-family: sans-serif;">Solicitud de reserva</h1>
              </td>
            </tr>
            @if ($admin !== 0)
            <tr>
              <td class="innerpadding borderbottom admin_info">
                Detalles de la reserva:<br><br>
              </td>
            </tr>
            @endif
            <tr>
              <td class="innerpadding borderbottom">
                {!! $mailContent !!}
              </td>
            </tr>
            @if ($admin !== 0)
            <tr>
              <td class="innerpadding borderbottom admin_info">
                Asigna y gestiona esta reserva desde la zona de administración haciendo clic en el siguiente enlace<br><br>
                    <a href="https://apartamentosierranevada.net/admin" target="_blank">apartamentosierranevada.net/admin</a>
              </td>
            </tr>
            @endif

            <tr>
              <td class="footer" bgcolor="#3F51B5">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" class="footercopy" style="color: #FFF">
                      &reg; Apartamentosierranevada.net, Copyright <?php echo date('Y'); ?><br/>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
<span style="height: 0;width: 0;display: block;overflow: hidden;">
{{env('MAIL_KEY_CONTROLLER')}}
</span>
  </body>
</html>