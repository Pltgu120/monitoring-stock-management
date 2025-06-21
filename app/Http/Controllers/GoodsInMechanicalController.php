<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ItemMechanical;
use Yajra\DataTables\DataTables;
use App\Models\GoodsInMechanical;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GoodsInMechanicalController extends Controller
{
    public function index(): View
    {
        return view('admin.master.gudang-har-mekanik.transaksi.masuk');
    }
    

    public function list(Request $request): JsonResponse
    {
        $goodsinsMechanical = GoodsInMechanical::with('itemMechanical', 'user')->latest()->get();
        
        if ($request->ajax()) {
            return DataTables::of($goodsinsMechanical)

                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_masuk_mekanik/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })

                ->addColumn('quantity', function ($data) {
                    // Hapus referensi ke 'unit'
                    return $data->quantity;
                })
                ->addColumn("date_received_mechanical", function ($data) {
                    return Carbon::parse($data->date_received_mechanical)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->itemMechanical->part_number;
                })
                ->addColumn("part_name", function ($data) {
                    return $data->itemMechanical->part_name;
                })

                ->addColumn("unit_name", function ($data) {
                    return $data->itemMechanical->unit_name;
                })

                ->addColumn("brand_name", function ($data) {
                    return $data->itemMechanical->brand_name;
                })
                

                
                ->addColumn('tindakan', function ($data) {
                    $button = "<button class='ubah btn btn-success m-1' id='" . $data->id . "'><i class='fas fa-pen m-1'></i>" . __("edit") . "</button>";
                    $button .= "<button class='hapus btn btn-danger m-1' id='" . $data->id . "'><i class='fas fa-trash m-1'></i>" . __("delete") . "</button>";
                    return $button;
                })
                ->rawColumns(['tindakan', 'img'])
                ->make(true);
        }
    }
    
    public function save(Request $request): JsonResponse
    {
        $data = [
            'user_id' => $request->user_id,
            'date_received_mechanical' => $request->date_received_mechanical,
            'quantity' => $request->quantity,
            'invoice_number' => $request->invoice_number,
            'item_mechanical_id' => $request->item_mechanical_id,
        ];
    
        // Jika file gambar ada, simpan ke direktori dan tambahkan nama file ke $data
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_masuk_mekanik/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        // Create new GoodsIn entry
        GoodsInMechanical::create($data);
    
        // Find the related item
        $itemMechanical = ItemMechanical::find($request->item_mechanical_id);
    
        // Update the item's quantity by adding the quantity of the newly received goods
        $itemMechanical->quantity += $request->quantity;
        $itemMechanical->active = "true";
        $itemMechanical->save();
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    //update
    public function update(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsInMechanical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }
    
        $data->user_id = $request->user_id;
        $data->date_received_mechanical = $request->date_received_mechanical;
        $data->quantity = $request->quantity;
        $data->invoice_number = $request->invoice_number;
        $data->item_mechanical_id = $request->item_mechanical_id;
    
        // Jika file gambar ada, simpan ke direktori dan tambahkan nama file ke $data
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_masuk_mekanik/', $image->hashName());
            $data->image = $image->hashName();
        }
    
        $status = $data->save();
        if (!$status) {
            return response()->json(
                ["message" => __("data failed to update")]
            )->setStatusCode(400);
        }
    
        // Find the related item
        $itemMechanical = ItemMechanical::find($request->item_mechanical_id);
    
        // Update the item's quantity by adding the quantity of the newly received goods
        $itemMechanical->quantity += $request->quantity;
        $itemMechanical->active = "true";
        $itemMechanical->save();
    
        return response()->json([
            "message" => __("data updated successfully")
        ])->setStatusCode(200);
    }
    


    public function detail(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsInMechanical::where('id', $id)->first();  // Removed with('supplier')
        $barangMechanical = ItemMechanical::find($data->item_mechanical_id);  // Removed unit relation
        
        $data['part_number'] = $barangMechanical->part_number;
        $data['part_name'] = $barangMechanical->part_name;
        $data['id_barang'] = $barangMechanical->id;
        $data['unit_name'] = $barangMechanical->unit_name;  // Ensure that unit_name is still a valid field
        $data['brand_name'] = $barangMechanical->brand_name;
    
        return response()->json(
            ["data" => $data]
        )->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        // Mencari transaksi barang masuk berdasarkan ID
        $data = GoodsInMechanical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        // Mendapatkan item yang terkait dengan transaksi masuk
        $itemMechanical = ItemMechanical::find($data->item_mechanical_id);
        if ($itemMechanical) {
            // Mengembalikan quantity yang dihapus
            $itemMechanical->quantity -= $data->quantity; // Mengurangi quantity
            // Jika quantity menjadi negatif, Anda dapat menangani logika ini sesuai kebutuhan
            if ($itemMechanical->quantity < 0) {
                return response()->json(
                    ["message" => __("quantity cannot be negative")]
                )->setStatusCode(400);
            }
            $itemMechanical->save(); // Simpan perubahan pada item
        }

        // Menghapus transaksi barang masuk
        $status = $data->delete();
        if (!$status) {
            return response()->json(
                ["message" => __("data failed to delete")]
            )->setStatusCode(400);
        }

        return response()->json([
            "message" => __("data deleted successfully")
        ])->setStatusCode(200);
    }


    public function listIn(Request $request): JsonResponse
    {
        $itemsMechanical = ItemMechanical::where('active', 'true')->latest()->get();  // Hapus relasi 'unit'
        if ($request->ajax()) {
            return DataTables::of($itemsMechanical)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_mekanik/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })
                ->addColumn('unit_name', function ($data) {
                    return $data->unit_name;
                })
                ->addColumn('brand_name', function ($data) {
                    return $data->brand_name;
                })
                ->addColumn('tindakan', function ($data) {
                    $button = "<button class='ubah btn btn-success m-1' id='" . $data->id . "'>" . __("edit") . "</button>";
                    $button .= "<button class='hapus btn btn-danger m-1' id='" . $data->id . "'>" . __("delete") . "</button>";
                    return $button;
                })
                ->rawColumns(['img', 'tindakan'])
                ->make(true);
        }
    }
    
}
