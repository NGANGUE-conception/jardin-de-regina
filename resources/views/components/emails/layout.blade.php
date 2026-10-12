<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'Le Jardin de Régina' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#FFF9F2;font-family:-apple-system,Segoe UI,Helvetica,Arial,sans-serif;color:#12414D;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFF9F2;padding:32px 16px;">
<tr><td align="center">
<table role="presentation" width="100%" style="max-width:560px;background-color:#ffffff;border-radius:24px;overflow:hidden;">
    <tr>
        <td style="background-color:#217B79;padding:28px 32px;text-align:center;">
            <span style="color:#ffffff;font-size:20px;font-weight:700;">🌿 Le Jardin de Régina</span>
        </td>
    </tr>
    <tr>
        <td style="padding:32px;">
            {{ $slot }}
        </td>
    </tr>
    <tr>
        <td style="background-color:#E3F2F1;padding:20px 32px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#12414D99;">Le Jardin de Régina · {{ $etab->adresse() }} · {{ $etab->telephone() }}</p>
        </td>
    </tr>
</table>
</td></tr>
</table>
</body>
</html>
