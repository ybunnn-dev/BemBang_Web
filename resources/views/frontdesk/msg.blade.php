@extends('layouts.frontdesk')
@section('title', 'Messages')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/reservations.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <div id="main-label">
        <img src="{{ asset('images/reserve2.svg') }}">
        <h3>Messages</h3>
    </div>
@endsection


