<x-emails.layout>
    <h1 style="font-size:20px;color:#12414D;margin:0 0 12px;">{{ $titre }}</h1>
    <p style="font-size:14px;line-height:1.6;color:#12414D;margin:0 0 20px;">{{ $intro }}</p>

    <table role="presentation" width="100%" style="background-color:#FFF9F2;border-radius:16px;padding:4px 0;margin-bottom:20px;">
        <tr>
            <td style="padding:12px 20px;font-size:13px;color:#12414D99;">Référence de votre dossier</td>
            <td style="padding:12px 20px;font-size:15px;font-weight:700;color:#1C6E6C;text-align:right;">{{ $numeroDossier }}</td>
        </tr>
        @foreach ($lignes as $label => $valeur)
        <tr>
            <td style="padding:8px 20px;font-size:13px;color:#12414D99;border-top:1px solid #ffffff;">{{ $label }}</td>
            <td style="padding:8px 20px;font-size:13px;font-weight:600;color:#12414D;text-align:right;border-top:1px solid #ffffff;">{{ $valeur }}</td>
        </tr>
        @endforeach
    </table>

    <p style="font-size:13px;line-height:1.6;color:#12414D99;margin:0;">Cet e-mail est envoyé automatiquement à titre de confirmation. Pour toute question, contactez-nous au {{ $etab->telephone() }} ou par retour de ce message.</p>
</x-emails.layout>
