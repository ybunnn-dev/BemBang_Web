@extends('layouts.management')
@section('title', 'FAQs')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/management/faq.css') }}">
@endsection

@section('content')
    <div id="main-label">
        <img src="{{ asset('images/faq-head.svg') }}">
        <h3>FAQs</h3>
    </div>
    <div id="content-card">
        <!-- Labels Row -->
        <div class="input-labels">
            <label for="search-table" id="search-label">Search for questions?</label>
            <label for="room-type-dropdown" id="dropdown-label-roomtype">Category</label>
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
                    <li><a class="dropdown-item" href="#">Available</a></li>
                    <li><a class="dropdown-item" href="#">Occupied</a></li>
                    <li><a class="dropdown-item" href="#">Under Maintenance</a></li>
                    <li><a class="dropdown-item" href="#">Cleaning</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-light">Enter Date</button>
        </div>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">FAQ ID</th>
                        <th scope="col">QUESTION</th>
                        <th scope="col">CATEGORY</th>
                        <th scope="col">DATE CREATED</th>
                        <th scope="col">DATE MODIFIED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1001</th>
                        <td>Ikaw na ba si MR. Right?</td>
                        <td><div class="status-div">Available</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1002</th>
                        <td>Ano ang ibig sabihin ng true love?</td>
                        <td><div class="status-div">Reserved</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1003</th>
                        <td>Paano magpapatawad ng buo?</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1004</th>
                        <td>Paano ba makakamtan ang kaligayahan?</td>
                        <td><div class="status-div">Booking</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1005</th>
                        <td>Mayroon bang tunay na pagkakaibigan?</td>
                        <td><div class="status-div">Cleaning</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1006</th>
                        <td>Ano ang mga hakbang para magbago?</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1007</th>
                        <td>Makakamtan ba ang tunay na kalayaan?</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1008</th>
                        <td>Ano ang ibig sabihin ng pag-ibig?</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1009</th>
                        <td>Paano malalaman kung ikaw ay tunay na minamahal?</td>
                        <td><div class="status-div">Maintenance</div></td>
                        <td>2025-03-21<br><p style="font-size: 13px;">12:00</p></td>
                        <td>2025-03-23 <br><p style="font-size: 13px;">12:00</p></td>
                    </tr>
                    <tr>
                        <th scope="row">1010</th>
                        <td>Ano ang halaga ng pamilya sa buhay ng tao?</td>
                        <td><div class="status-div">Maintenance</div></td>
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