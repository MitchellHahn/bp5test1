@extends('layouts.master')

@section('content')

    <h1>Test</h1>

    @foreach($users as $user)

        {{$user->name}} <br />

    @endforeach

@endsection
