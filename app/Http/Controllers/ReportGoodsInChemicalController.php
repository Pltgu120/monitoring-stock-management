<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GoodsInChemical;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class ReportGoodsInChemicalController extends Controller
{
    public function index(): View
    {
        return view('admin.master.laporan-kimia.masuk');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsinsChemical = GoodsInChemical::with('itemChemical', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsinsChemical->whereBetween('date_received_chemical', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods ins
            $goodsinsChemical = $goodsinsChemical->latest()->get();
    
            return DataTables::of($goodsinsChemical)
                ->addColumn('quantity', function ($data) {
                    // Only retrieve quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_received_chemical", function ($data) {
                    return Carbon::parse($data->date_received_chemical)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->itemChemical->part_number;
                })
                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    

}
