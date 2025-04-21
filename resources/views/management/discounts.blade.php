@extends('layouts.management')
@section('title', 'Discounts')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/discount.css') }}">
@endsection

@section('content')
    <div id="main-label">
        <img src="{{ asset('images/discount.svg') }}">
        <h3>Discounts</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for discounts?</label>
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
                        <th scope="col">DISCOUNT ID</th>
                        <th scope="col">DISCOUNT NAME</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">VALUE</th>
                        <th scope="col">START-DATE</th>
                        <th scope="col">END-DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1001</th>
                        <td>Bembarkada!</td>
                        <td><div class="status-div">Active</div></td>
                        <td>P 100</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Active</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Active</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Bembang Twin</td>
                        <td><div class="status-div">Ended</div></td>
                        <td>15%</td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection