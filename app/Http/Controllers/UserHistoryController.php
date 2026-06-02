<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\VisitRequest;

class UserHistoryController extends Controller
{
    /**
     * Display the personal history dashboard for the authenticated user.
     */
    public function index()
    {
        $userId = Auth::id();

        // Fetch user donations
        $donations = Donation::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        // Calculate total approved donation for this user
        $totalDonasiSaya = Donation::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('jumlah_donasi');

        // Fetch user visit requests
        $visits = VisitRequest::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('user.history', compact('donations', 'visits', 'totalDonasiSaya'));
    }
}
