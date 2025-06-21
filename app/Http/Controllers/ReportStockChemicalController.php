<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\ItemChemical;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class ReportStockChemicalController extends Controller
{
    public function index():View
    {
        return view('admin.master.laporan-kimia.stok');
    }

    public function list(Request $request): JsonResponse
    {
        if($request->ajax()){
            $data = ItemChemical::with('goodsInChemicals','goodsOutChemicals');
    
            // Apply date filtering only if start_date and end_date are provided
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $data->whereHas('goodsOutChemicals', function($query) use ($request) {
                    $query->whereBetween('date_out', [$request->start_date, $request->end_date]);
                });
            }
    
            $data = $data->latest()->get();
            return DataTables::of($data)
                ->addColumn('jumlah_masuk', function ($item) {
                    return $item->goodsInChemicals->sum('quantity');
                })
                ->addColumn("jumlah_keluar", function ($item) {
                    return $item->goodsOutChemicals->sum('quantity');
                })
                ->addColumn("part_number", function ($item) {
                    return $item->part_number;
                })
                ->addColumn("part_name", function ($item) {
                    return $item->part_name;
                })
                ->addColumn("stok_awal", function ($item) {
                    return $item->initial_qty;
                })
                ->addColumn("total", function ($item) {
                    $result = $item->quantity;
                    return $result == 0 
                        ? "<span class='text-red font-weight-bold'>{$result}</span>"
                        : "<span class='text-success font-weight-bold'>{$result}</span>";
                })
                ->rawColumns(['total'])
                ->make(true);
        }
    }
}

