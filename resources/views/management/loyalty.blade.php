@extends('layouts.management')
@section('title', 'Loyalty Program')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/loyalty.css') }}">
@endsection

@section('content')
    <div id="main-label">
        <img src="{{ asset('images/loyalty.svg') }}">
        <h3>Loyalty Program</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for memberships?</label>
            <label for="status-dropdown" id="dropdown-label-status">Status</label>
            <label for="status-dropdown" id="date-time-label">Date and Time</label>
        </div>

        <!-- Input Fields Row -->
        <div class="input-group">
            <input type="text" class="form-control" id="search-table" placeholder="Search...">

            <!-- Status Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="status-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Statuses
                </button>
                <ul class="dropdown-menu" aria-labelledby="status-dropdown" id="status-menu">
                    <li><a class="dropdown-item" href="#">Active</a></li>
                    <li><a class="dropdown-item" href="#">Ended</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-light">Enter Date</button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">MEMBERSHIP ID</th>
                        <th scope="col">MEMBERSHIP NAME</th>
                        <th scope="col">MEMBERSHIP LEVEL</th>
                        <th scope="col">CHECK IN THRESHOLD</th>
                        <th scope="col">DATE CREATED</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($memberships as $member)
                    <tr>
                        <th scope="row" id="wrapId"><p>{{ $member->_id }}</p></th>
                        <td>{{ $member->membership_name }}</td>
                        <td>{{ $member->membership_level }}</td>
                        <td>{{ $member->check_in_threshold }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($member->createdAt->toDateTime())->format('Y-m-d') }}<br>
                            <p style="font-size: 13px;">{{ \Carbon\Carbon::parse($member->createdAt->toDateTime())->format('H:i') }}</p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection