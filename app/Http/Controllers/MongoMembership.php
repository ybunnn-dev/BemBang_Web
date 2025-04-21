<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MongoMembership extends Controller
{
    /**
     * Display a listing of all memberships.
     */
    public function index()
    {
        $memberships = Membership::getAll();
        \Log::info($memberships);
        return view('management.loyalty', ['memberships' => $memberships]);
    }

    /**
     * Store a newly created membership.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_name' => 'required|string|max:255',
            'membership_level' => 'required|integer',
            'check_in_threshold' => 'required|integer',
            'check_in_points' => 'required|integer',
            'booking_points' => 'required|integer',
            'reservation_points' => 'required|integer',
        ]);

        $membership = Membership::create($validated);

        return response()->json([
            'message' => 'Membership created successfully',
            'data' => $membership
        ], 201);
    }

    /**
     * Display the specified membership.
     */
    public function show(string $id)
    {
        $membership = Membership::find($id);
        
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        return response()->json($membership);
    }

    /**
     * Update the specified membership.
     */
    public function update(Request $request, string $id)
    {
        $membership = Membership::find($id);
        
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        $validated = $request->validate([
            'membership_name' => 'sometimes|string|max:255',
            'membership_level' => 'sometimes|integer',
            'check_in_threshold' => 'sometimes|integer',
            'check_in_points' => 'sometimes|integer',
            'booking_points' => 'sometimes|integer',
            'reservation_points' => 'sometimes|integer',
        ]);

        $membership->update($validated);

        return response()->json([
            'message' => 'Membership updated successfully',
            'data' => $membership
        ]);
    }

    /**
     * Remove the specified membership.
     */
    public function destroy(string $id)
    {
        $membership = Membership::find($id);
        
        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404);
        }

        $membership->delete();

        return response()->json(['message' => 'Membership deleted successfully']);
    }

    /**
     * Additional custom method - Get membership by level
     */
    public function getByLevel($level)
    {
        $memberships = Membership::where('membership_level', $level)->get();
        
        if ($memberships->isEmpty()) {
            return response()->json(['message' => 'No memberships found for this level'], 404);
        }

        return response()->json($memberships);
    }
}