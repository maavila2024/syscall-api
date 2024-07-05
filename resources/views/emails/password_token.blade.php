@extends('emails.layouts.default')

@section('content')
    <p>Olá {{ $user->first_name }}</p>
    <p>Seu código para resetar a senha é: {{ $token }} .</p>
@endsection
