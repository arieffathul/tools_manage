<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\BorrowReturn;
use App\Models\Tool;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login.show');
        }

        $user = Auth::user();
        $role = $user->role ?? null;
        $name = $user->name;

        $stats = [
            'total_tools' => Tool::count(),
            'active_borrows_count' => Borrow::where('is_completed', 0)->count(),

            'unreturned_items_count' => DB::table('borrow_details')
                ->join('borrows', 'borrow_details.borrow_id', '=', 'borrows.id')
                ->where('borrows.is_completed', 0)
                ->where('borrow_details.is_complete', 0)
                ->sum('borrow_details.quantity'),
            'today_returns' => BorrowReturn::whereDate('created_at', today())->count(),

            'returned_items_count' => DB::table('return_details')
                ->join('borrow_returns', 'return_details.borrow_return_id', '=', 'borrow_returns.id')
                ->whereDate('borrow_returns.created_at', today())
                ->sum('return_details.quantity'),
        ];

        $chartData = $this->getWeeklyChartData();

        $recentActivities = $this->getRecentActivities(6);

        //         $recentActivities = Activity::with('user', 'borrow')
        //     ->latest()
        //     ->limit(6)
        //     ->get();

        // // Popular tools - ambil hanya top 5
        // $popularTools = Tool::withCount('borrows')
        //     ->orderByDesc('borrows_count')
        //     ->limit(5)
        //     ->get(['id', 'name', 'code', 'current_quantity']);

        return view('master.dashboard', compact('role', 'name', 'stats', 'chartData', 'recentActivities'));
    }

    private function getWeeklyChartData()
    {
        $dateRange = [];
        $labels = [];

        Carbon::setLocale('id');

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateRange[] = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('l');
        }

        $borrowData = Borrow::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $returnData = BorrowReturn::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $borrowCounts = [];
        $returnCounts = [];
        foreach ($dateRange as $date => $defaultValue) {
            $borrowCounts[] = $borrowData[$date] ?? $defaultValue;
            $returnCounts[] = $returnData[$date] ?? $defaultValue;
        }

        return compact('labels', 'borrowCounts', 'returnCounts');
    }

    // DashboardController.php
    private function getRecentActivities($limit = 6)
    {
        // Cara 1: Simple tapi 2 query
        $activities = collect();

        // Ambil peminjaman terbaru
        $borrows = Borrow::with(['engineer:id,name'])
            ->latest()
            ->limit($limit)
            ->get(['id', 'engineer_id', 'job_reference', 'created_at']);

        foreach ($borrows as $borrow) {
            $activities->push([
                'type' => 'borrow',
                'id' => $borrow->id,
                'name' => $borrow->engineer->name ?? 'Unknown',
                'job_reference' => $borrow->job_reference,
                'time' => $borrow->created_at,
                'icon' => 'bi-box-arrow-in-right',
                'color' => 'primary',
                'title' => 'Peminjaman Baru',
                'description' => 'meminjam tools',
            ]);
        }

        // Ambil pengembalian terbaru
        $returns = BorrowReturn::with(['returner:id,name'])
            ->latest()
            ->limit($limit)
            ->get(['id', 'returner_id', 'job_reference', 'created_at']);

        foreach ($returns as $return) {
            $activities->push([
                'type' => 'return',
                'id' => $return->id,
                'name' => $return->returner->name ?? 'Unknown',
                'job_reference' => $return->job_reference,
                'time' => $return->created_at,
                'icon' => 'bi-box-arrow-left',
                'color' => 'success',
                'title' => 'Pengembalian Tool',
                'description' => 'mengembalikan tools',
            ]);
        }

        // Urutkan dan ambil $limit terbaru
        return $activities->sortByDesc('time')->take($limit);
    }
}
