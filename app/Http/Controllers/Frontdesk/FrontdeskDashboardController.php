<?php

namespace App\Http\Controllers\Frontdesk;

use App\Http\Controllers\Controller;
use App\Models\Rooms;

class FrontdeskDashboardController extends Controller
{
    public function index()
    {
        // Get all rooms (or query only the needed fields)
        $rooms = Rooms::getAll();

        // Count rooms by status
        $roomCounts = [
            'available'   => $rooms->where('status', 'available')->count(),
            'occupied'    => $rooms->where('status', 'occupied')->count(),
            'maintenance' => $rooms->where('status', 'maintenance')->count(),
            'cleaning'    => $rooms->where('status', 'cleaning')->count(),
        ];

        return view('frontdesk.dashboard', compact('roomCounts'));
    }
}