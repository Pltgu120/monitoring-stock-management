<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GoodsOutChemical;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class ReportGoodsOutChemicalController extends Controller
{
    public function index():View 
    {
        return view('admin.master.laporan-kimia.keluar');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsoutsChemical = GoodsOutChemical::with('itemChemical', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsoutsChemical->whereBetween('date_out', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods outs
            $goodsoutsChemical = $goodsoutsChemical->latest()->get();
    
            return DataTables::of($goodsoutsChemical)
                ->addColumn('quantity', function ($data) {
                    // Return quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_out", function ($data) {
                    return Carbon::parse($data->date_out)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->itemChemical->part_number;
                })

                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    
}
