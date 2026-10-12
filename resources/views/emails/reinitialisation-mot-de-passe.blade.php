<x-emails.layout>
    <h1 style="font-size:20px;color:#12414D;margin:0 0 12px;">Réinitialisation de votre mot de passe</h1>
    <p style="font-size:14px;line-height:1.6;color:#12414D;margin:0 0 12px;">Bonjour {{ $nom }},</p>
    <p style="font-size:14px;line-height:1.6;color:#12414D;margin:0 0 24px;">Une demande de réinitialisation du mot de passe de votre espace admin vient d'être faite. Pour choisir un nouveau mot de passe, cliquez sur le bouton ci-dessous.</p>

    <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 24px;">
        <tr>
            <td style="background-color:#C13B1A;border-radius:999px;">
                <a href="{{ $lien }}" style="display:inline-block;padding:14px 28px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;">Choisir un nouveau mot de passe</a>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" style="background-color:#FFF9F2;border-radius:16px;margin-bottom:20px;">
        <tr>
            <td style="padding:14px 20px;font-size:13px;line-height:1.6;color:#12414D;">
                <strong>Ce lien est valable {{ $dureeMinutes }} minutes</strong> et ne peut être utilisé qu'une seule fois.<br>
                Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email : votre mot de passe actuel reste inchangé.
            </td>
        </tr>
    </table>

    <p style="font-size:12px;line-height:1.6;color:#12414D99;margin:0;">Si le bouton ne fonctionne pas, copiez cette adresse dans votre navigateur :<br><span style="word-break:break-all;color:#1C6E6C;">{{ $lien }}</span></p>
</x-emails.layout>
