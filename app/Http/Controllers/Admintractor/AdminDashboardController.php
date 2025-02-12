<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Session;

class AdminDashboardController extends Controller
{
    //
    public function __construct()
    {
        Session::put("ModuleActive", "dashboard");
    }
    function index()
    {
        $totalRevenue = Invoice::where('status', 'completed')->sum('total_amount');
        // dd($totalRevenue);
        $counts = [
            'all' => Invoice::count(),
            'pending' => Invoice::where('status', 'pending')->count(),
            'confirmed' => Invoice::where('status', 'confirmed')->count(),
            'delivering' => Invoice::where('status', 'delivering')->count(),
            'waiting_for_pickup' => Invoice::where('status', 'waiting_for_pickup')->count(),
            'picked_up' => Invoice::where('status', 'picked_up')->count(),
            'completed' => Invoice::where('status', 'completed')->count(),
            'cancelled' => Invoice::where('status', 'cancelled')->count(),
            'trash' => Invoice::where('status', 'trash')->count(),
        ];

        // Lấy các đơn hàng mới nhất (số lượng theo yêu cầu)
        $latestOrders = Invoice::orderBy('created_at', 'desc')->limit(5)->get();
        return view('backend.dashboard', compact('totalRevenue', 'counts', 'latestOrders'));
        // return view('backend.dashboard', with(['totalRevenuee' => $totalRevenue, 'counts' => $counts, 'latestOrders' => $latestOrders]));
    }
}
