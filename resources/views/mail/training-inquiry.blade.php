<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nová zpráva z webu</title>
</head>
<body style="margin:0;padding:24px;background:#f7f4ef;font-family:Arial,Helvetica,sans-serif;color:#1c1914;">
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e2ded7;">
    <tr>
      <td style="background:#1b1812;padding:24px 32px;">
        <div style="color:#c0261e;font-size:11px;letter-spacing:.2em;text-transform:uppercase;font-weight:700;">JC Raion-Ryu</div>
        <div style="color:#ffffff;font-size:20px;margin-top:6px;">Nová zpráva z webu</div>
      </td>
    </tr>
    <tr>
      <td style="padding:32px;">
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="font-size:14px;line-height:1.6;">
          <tr>
            <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;width:160px;vertical-align:top;">Typ / dotaz</td>
            <td style="padding:8px 0;font-weight:bold;">{{ $data['trainingType'] }}</td>
          </tr>
          @if (!empty($data['date']))
            <tr>
              <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;vertical-align:top;">Preferovaný termín</td>
              <td style="padding:8px 0;">{{ \Illuminate\Support\Carbon::parse($data['date'])->translatedFormat('l j. n. Y') }}</td>
            </tr>
          @endif
          <tr>
            <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;vertical-align:top;">Jméno</td>
            <td style="padding:8px 0;">{{ $data['name'] }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;vertical-align:top;">E-mail</td>
            <td style="padding:8px 0;"><a href="mailto:{{ $data['email'] }}" style="color:#c0261e;">{{ $data['email'] }}</a></td>
          </tr>
          @if (!empty($data['phone']))
            <tr>
              <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;vertical-align:top;">Telefon</td>
              <td style="padding:8px 0;">{{ $data['phone'] }}</td>
            </tr>
          @endif
          @if (!empty($data['message']))
            <tr>
              <td style="padding:8px 0;color:#8c8680;text-transform:uppercase;font-size:11px;letter-spacing:.1em;vertical-align:top;">Zpráva</td>
              <td style="padding:8px 0;white-space:pre-wrap;">{{ $data['message'] }}</td>
            </tr>
          @endif
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding:16px 32px;background:#f0ede8;color:#8c8680;font-size:11px;">
        Odesláno z webu judopraha.eu · odpovězte přímo na tento e-mail pro kontakt s odesílatelem.
      </td>
    </tr>
  </table>
</body>
</html>
