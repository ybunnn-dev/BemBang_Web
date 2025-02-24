@extends('layouts.frontdesk')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
@endsection
