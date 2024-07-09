@extends('emails.layouts.default')

@section('content')
    <p>Olá {{ $user->first_name }}</p>
    <p>Obrigado por se registrar! Por favor, clique no botão abaixo para confirmar seu registro:</p>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
            <tr>
                <td align="left">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td> <a href="http://htmlemail.io" target="_blank">Call To
                                        Action</a> </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <p>Se você não se registrou em nosso site, ignore este email.</p>
    <p>Obrigado,
       Sua Equipe
    </p>
@endsection
