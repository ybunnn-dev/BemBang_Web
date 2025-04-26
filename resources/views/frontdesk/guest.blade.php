@extends('layouts.frontdesk')
@section('title', 'Guests')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <script>
        document.addEventListener("DOMContentLoaded", function (){
            console.log(@json($guests));
        });
    </script>
    <div id="main-label">
        <img src="{{ asset('images/profile.svg') }}">
        <h3>Guests</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for guests?</label>
            <label for="room-type-dropdown" id="dropdown-label-roomtype">Room Types</label>
            <label for="status-dropdown" id="dropdown-label-status">Status</label>
            <label for="status-dropdown" id="date-time-label">Date</label>
        </div>

        <!-- Input Fields Row -->
        <div class="input-group">
            <input type="text" class="form-control" id="search-table" placeholder="Search...">

            <!-- Room Types Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="room-type-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Room Types
                </button>
                <ul class="dropdown-menu" aria-labelledby="room-type-dropdown">
                    <li><a class="dropdown-item" href="#">Bembang Standard</a></li>
                    <li><a class="dropdown-item" href="#">Bembang Twin</a></li>
                    <li><a class="dropdown-item" href="#">Bembang Family</a></li>
                    <li><a class="dropdown-item" href="#">Deluxe</a></li>
                    <li><a class="dropdown-item" href="#">Suite</a></li>
                </ul>
            </div>

            <!-- Status Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="status-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Statuses
                </button>
                <ul class="dropdown-menu" aria-labelledby="status-dropdown" id="status-menu">
                    <li><a class="dropdown-item" href="#">Available</a></li>
                    <li><a class="dropdown-item" href="#">Occupied</a></li>
                    <li><a class="dropdown-item" href="#">Under Maintenance</a></li>
                    <li><a class="dropdown-item" href="#">Cleaning</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-light">Enter Date</button>
            <button type="button" class="btn btn-primary" id="scan-qr-button">
                <img src="{{ asset('images/qr2.svg') }}" width="15px" height="16px">
                Scan QR Code
            </button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="guest-id-column">GUEST ID</th>
                        <th scope="col" class="guest-name-column">GUEST NAME</th>
                        <th scope="col">MEMBERSHIP STATUS</th>
                        <th scope="col">RECENT TRANSACTION</th>
                        <th scope="col">TRANSACTION DATE</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($guests as $guest)
                    @php
                        $latestTransaction = collect($guest->transaction_details)
                            ->sortByDesc('created_at')
                            ->first();
                            // Parse the date with Carbon if it exists
                        $transactionDate = isset($latestTransaction['created_at']) 
                            ? \Carbon\Carbon::parse($latestTransaction['created_at'])
                            : null;
                    @endphp
                    <tr onclick="window.location.href='/frontdesk/current-guest/{{ $guest->_id}}'">
                        <th scope="row" class="guest-id-column2">{{ $guest->_id }}</th>
                        <td class="guest-name-column2">{{ $guest->firstName . ' ' . $guest->lastName }}</td>
                        <td>
                            <div class="membership-type">
                            <img src="{{ 
                                match(strtolower($guest->membership_details->membership_name ?? '')) {
                                    'explorer' => asset('images/explorer-icon.svg'),
                                    'regular' => asset('images/regular-icon.svg'),
                                    'expert' => asset('images/expert-icon.svg'),
                                    'prime' => asset('images/prime-icon.svg'),
                                    'elite' => asset('images/elite-icon.svg'),
                                    default => asset('images/leaf.svg') // Fallback icon
                                }
                            }}" width="20px" height="20px" alt="{{ $guest->membership_details->membership_name ?? 'Standard' }} membership">
                                <p>{{ $guest->membership_details->membership_name ?? 'Not Yet Joined' }}</p>
                            </div>
                        </td>   
                        <td>    
                            <div class="status-div">{{ $latestTransaction['transaction_type'] ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $transactionDate ? $transactionDate->format('M d, Y') : 'No date' }}<br>
                            <p style="font-size: 13px;">
                                {{ $transactionDate ? $transactionDate->format('h:i A') : 'No time' }}
                            </p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

