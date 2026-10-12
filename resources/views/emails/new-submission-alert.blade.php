<x-emails.layout>
    <h1 style="font-size:20px;color:#12414D;margin:0 0 12px;">{{ $type }}</h1>
    <p style="font-size:14px;line-height:1.6;color:#12414D;margin:0 0 20px;">Une nouvelle demande vient d'être déposée sur le site. Voici le récapitulatif :</p>

    <table role="presentation" width="100%" style="background-color:#FFF9F2;border-radius:16px;padding:4px 0;margin-bottom:20px;">
        <tr>
            <td style="padding:12px 20px;font-size:13px;color:#12414D99;">Référence</td>
            <td style="padding:12px 20px;font-size:15px;font-weight:700;color:#C13B1A;text-align:right;">{{ $numeroDossier }}</td>
        </tr>
        @foreach ($lignes as $label => $valeur)
        <tr>
            <td style="padding:8px 20px;font-size:13px;color:#12414D99;border-top:1px solid #ffffff;">{{ $label }}</td>
            <td style="padding:8px 20px;font-size:13px;font-weight:600;color:#12414D;text-align:right;border-top:1px solid #ffffff;">{{ $valeur }}</td>
        </tr>
        @endforeach
    </table>

    @if ($lienAdmin)
    <table role="presentation" style="margin:0 auto;">
        <tr>
            <td style="border-radius:999px;background-color:#C13B1A;">
                <a href="{{ $lienAdmin }}" style="display:inline-block;padding:12px 28px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;">Consulter dans l'espace admin</a>
            </td>
        </tr>
    </table>
    @endif
</x-emails.layout>
