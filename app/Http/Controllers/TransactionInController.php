<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\GoodsIn;
use App\Models\Supplier;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionInController extends Controller
{
    public function index(): View
    {
        return view('admin.master.gudang-har-elektrik.transaksi.masuk');
    }
    

    public function list(Request $request): JsonResponse
    {
        $goodsins = GoodsIn::with('item', 'user')->latest()->get();
        
        if ($request->ajax()) {
            return DataTables::of($goodsins)

                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_masuk/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })

                ->addColumn('quantity', function ($data) {
                    // Hapus referensi ke 'unit'
                    return $data->quantity;
                })
                ->addColumn("date_received", function ($data) {
                    return Carbon::parse($data->date_received)->format('d F Y');
                })
                ->addColumn("part_number", function ($data) {
                    return $data->item->part_number;
                })
                ->addColumn("part_name", function ($data) {
                    return $data->item->part_name;
                })

                ->addColumn("unit_name", function ($data) {
                    return $data->item->unit_name;
                })

                ->addColumn("brand_name", function ($data) {
                    return $data->item->brand_name;
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
            'date_received' => $request->date_received,
            'quantity' => $request->quantity,
            'invoice_number' => $request->invoice_number,
            'item_id' => $request->item_id,
        ];
    
        // Jika file gambar ada, simpan ke direktori dan tambahkan nama file ke $data
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_masuk/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        // Create new GoodsIn entry
        GoodsIn::create($data);
    
        // Find the related item
        $item = Item::find($request->item_id);
    
        // Update the item's quantity by adding the quantity of the newly received goods
        $item->quantity += $request->quantity;
        $item->active = "true";
        $item->save();
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    //update
    public function update(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsIn::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        $item = Item::find($data->item_id);
        if ($item) {
            $item->quantity -= $data->quantity;
            if ($item->quantity < 0) {
                return response()->json(
                    ["message" => __("quantity cannot be negative")]
                )->setStatusCode(400);
            }
            $item->save();
        }

        $data->quantity = $request->quantity;
        $data->invoice_number = $request->invoice_number;
        $data->item_id = $request->item_id;
        $data->date_received = $request->date_received;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            Storage::delete('public/barang_masuk/' . $data->image);
            $image->storeAs('public/barang_masuk/', $image->hashName());
            $data->image = $image->hashName();
        }

        $data->save();

        $item = Item::find($request->item_id);
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        }

        return response()->json([
            "message" => __("data updated successfully")
        ])->setStatusCode(200);
    }
    


    public function detail(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsIn::where('id', $id)->first();  // Removed with('supplier')
        $barang = Item::find($data->item_id);  // Removed unit relation
        
        $data['part_number'] = $barang->part_number;
        $data['part_name'] = $barang->part_name;
        $data['id_barang'] = $barang->id;
        $data['unit_name'] = $barang->unit_name;  // Ensure that unit_name is still a valid field
        $data['brand_name'] = $barang->brand_name;
    
        return response()->json(
            ["data" => $data]
        )->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        // Mencari transaksi barang masuk berdasarkan ID
        $data = GoodsIn::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        // Mendapatkan item yang terkait dengan transaksi masuk
        $item = Item::find($data->item_id);
        if ($item) {
            // Mengembalikan quantity yang dihapus
            $item->quantity -= $data->quantity; // Mengurangi quantity
            // Jika quantity menjadi negatif, Anda dapat menangani logika ini sesuai kebutuhan
            if ($item->quantity < 0) {
                return response()->json(
                    ["message" => __("quantity cannot be negative")]
                )->setStatusCode(400);
            }
            $item->save(); // Simpan perubahan pada item
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
        $items = Item::where('active', 'true')->latest()->get();  // Hapus relasi 'unit'
        if ($request->ajax()) {
            return DataTables::of($items)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
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