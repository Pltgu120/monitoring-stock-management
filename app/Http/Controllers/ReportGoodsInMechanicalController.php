<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\GoodsInMechanical;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class ReportGoodsInMechanicalController extends Controller
{
    public function index(): View
    {
        return view('admin.master.laporan-mekanik.masuk');
    }

    public function list(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            // Initialize the query
            $goodsinsMechanical = GoodsInMechanical::with('itemMechanical', 'user');
    
            // Apply date filtering if provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $goodsinsMechanical->whereBetween('date_received_mechanical', [$request->start_date, $request->end_date]);
            }
    
            // Get the latest goods ins
            $goodsinsMechanical = $goodsinsMechanical->latest()->get();
    
            return DataTables::of($goodsinsMechanical)
                ->addColumn('quantity', function ($data) {
                    // Only retrieve quantity without unit name
                    return $data->quantity; // Removed the unit reference
                })
                ->addColumn("date_received_mechanical", function ($data) {
                    return Carbon::parse($data->date_received_mechanical)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->itemMechanical->part_number;
                })
                ->make(true);
        }
    
        return response()->json(['message' => __('Invalid request')], 400);
    }
    

}
