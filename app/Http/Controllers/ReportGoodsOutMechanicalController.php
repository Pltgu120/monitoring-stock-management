<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Models\GoodsOutMechanical;

class ReportGoodsOutMechanicalController extends Controller
{
    public function index():View 
    {
        return view('admin.master.laporan-mekanik.keluar');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsoutsMechanical = GoodsOutMechanical::with('itemMechanical', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsoutsMechanical->whereBetween('date_out', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods outs
            $goodsoutsMechanical = $goodsoutsMechanical->latest()->get();
    
            return DataTables::of($goodsoutsMechanical)
                ->addColumn('quantity', function ($data) {
                    // Return quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_out", function ($data) {
                    return Carbon::parse($data->date_out)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->itemMechanical->part_number;
                })

                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    
}
