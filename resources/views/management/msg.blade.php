@extends('layouts.management')
@section('title', 'Messages')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/msg.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/frontdesk-dashboard.js') }}"></script>
@endsection

@section('content')  
    <div class="main-container">
        <div class="main-card">
            <div id="main-label">
                <img src="{{ asset('images/message-icons/msg.svg') }}">
                <h3>Messages</h3>
            </div>
            <div class="search-holder">
                <input type="text" class="form-control" id="search-msg" placeholder="Search...">
            </div>
            <div class="message-holder">
                <table class="message-table">
                    <tr>
                        <td>
                            <div class="message-content" id="msgc">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/kim.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Beki Boxer</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Please accept mo na ako lods.</p>
                                        <p class="time">- 1m</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/beki.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Ron Peter Mortega</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Amag ka, ata gamit mo pic ko?.</p>
                                        <p class="time">- 30m</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/wally.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Wally Bayola</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Mapapanot din kayo tandaan niyo yan.</p>
                                        <p class="time">- 1h</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/lana.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Biokid Del Rey</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Pass sayo, radiohead fan ka.</p>
                                        <p class="time">- 1h</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/beki.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Aquaflask De los Reyes</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Pass sayo, radiohead fan ka.</p>
                                        <p class="time">- 1h</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/beki.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Aquaflask De los Reyes</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Pass sayo, radiohead fan ka.</p>
                                        <p class="time">- 1h</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="message-content">
                                <div class="msg-img-holder">
                                    <img src="{{ asset('images/beki.jpg') }}">
                                </div>
                                <div class="text-messages">
                                    <h5>Aquaflask De los Reyes</h5>
                                    <div class="lower-msg">
                                        <p class="msg-peek">Pass sayo, radiohead fan ka.</p>
                                        <p class="time">- 1h</p>
                                    </div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="conversation-card">
            <div class="conversation-head">
                <div class="convo-profile-holder">
                    <img src="{{ asset('images/kim.jpg') }}">
                </div>
                <h4>Beki Boxer</h4>
                <p>Enquiry #1234</p>
            </div>
            <hr>
            <div class="d-conversation">
                <p id="sample-msg1">hi takt kb sa six?</p>
                <p id="sample-msg2">six tau</p>
                <p id="sample-msg3">Hello! This is John Peter, your Bembang Agent. Just to clarify, we don’t promote indecency for hotel bookings and reservations! Let me know how I can assist you.</p>
            </div>
            <div class="chat-input-container">
                <textarea id="chat-input" placeholder="Type your message..." rows="1"></textarea>
                <button id="send-btn"> <img src="{{ asset('images/message-icons/send.svg') }}"></button>
            </div>
            
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="{{ asset('js/management/side-nav.js') }}"></script>
    <script src="{{ asset('js/management/room.js') }}"></script>
@endsection

