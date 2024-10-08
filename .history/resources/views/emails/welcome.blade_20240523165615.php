@extends('emails.layouts.default')

@section('content')
    <p>Hi there</p>
    <p>Sometimes you just want to send a simple HTML email with a simple design and clear
        call to action. This is it.</p>
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
    <p>This is a really simple email template. It's sole purpose is to get the recipient to
        click the button with no distractions.</p>
    <p>Good luck! Hope it works.</p>
@endsection
