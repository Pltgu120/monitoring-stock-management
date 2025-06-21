<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\GoodsIn;
use App\Models\Item;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ReportGoodsInController extends Controller
{
    public function index(): View
    {
        return view('admin.master.laporan-elektrik.masuk');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsins = GoodsIn::with('item', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsins->whereBetween('date_received', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods ins
            $goodsins = $goodsins->latest()->get();
    
            return DataTables::of($goodsins)
                ->addColumn('quantity', function ($data) {
                    // Only retrieve quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_received", function ($data) {
                    return Carbon::parse($data->date_received)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->item->part_number;
                })
                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    

}
