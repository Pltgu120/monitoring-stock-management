<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\GoodsOut;
use App\Models\Item;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ReportGoodsOutController extends Controller
{
    public function index():View 
    {
        return view('admin.master.laporan-elektrik.keluar');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsouts = GoodsOut::with('item', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsouts->whereBetween('date_out', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods outs
            $goodsouts = $goodsouts->latest()->get();
    
            return DataTables::of($goodsouts)
                ->addColumn('quantity', function ($data) {
                    // Return quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_out", function ($data) {
                    return Carbon::parse($data->date_out)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->item->part_number;
                })
                // ->addColumn("customer_name", function ($data) {
                //     return $data->customer->name;
                // })

                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    
}
