<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminCampaignController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = Campaign::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate(10)->withQueryString();
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Program kebutuhan berhasil ditambahkan.');
    }

    public function update(Request $request, Campaign $campaign)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'deadline' => 'nullable|date',
            'status' => 'required|in:active,completed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Program kebutuhan berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Program kebutuhan berhasil dihapus.');
    }
}
