<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\GoodsIn;
use App\Models\Customer;
use App\Models\GoodsOut;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TransactionOutController extends Controller
{
    public function index():View
    {
        $in_status = Item::where('active','true')->count();
        // $customers = Customer::all();
        return view('admin.master.gudang-har-elektrik.transaksi.keluar',compact('in_status'));
    }

    public function list(Request $request): JsonResponse
    {
        $goodsouts = GoodsOut::with('item', 'user')->latest()->get();
        if ($request->ajax()) {
            return DataTables::of($goodsouts)

                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_keluar/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })

                ->addColumn('quantity', function ($data) {
                    // Hapus referensi ke 'unit'
                    $item = Item::find($data->item->id);
                    return $data->quantity; // Hanya tampilkan quantity tanpa satuan unit
                })
                ->addColumn('date_received', function ($data) {
                    return Carbon::parse($data->date_received)->format('d F Y');
                })
                ->addColumn('part_number', function ($data) {
                    return $data->item->part_number;
                })
                ->addColumn('part_name', function ($data) {
                    return $data->item->part_name;
                })
                ->addColumn('unit_name', function ($data) {
                    return $data->item->unit_name;
                })
                ->addColumn('brand_name', function ($data) {
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
        // Mendapatkan bulan dan tahun dari tanggal keluar
        $currentMonth = date('m', strtotime($request->date_out));
        $currentYear = date('Y', strtotime($request->date_out));
    
        // Menghitung total barang masuk dan keluar pada bulan ini
        $goodsInThisMonth = GoodsIn::whereMonth('date_received', $currentMonth)
            ->whereYear('date_received', $currentYear)
            ->sum('quantity');
    
        $goodsOutThisMonth = GoodsOut::whereMonth('date_out', $currentMonth)
            ->whereYear('date_out', $currentYear)
            ->sum('quantity');
    
        // Cek total quantity dari item
        $item = Item::find($request->item_id);
        if (!$item) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }
    
        // Total quantity item saat ini
        $totalQuantity = $item->quantity;
    
        // Cek apakah jumlah barang yang dikeluarkan lebih besar dari total stock
        if ($request->quantity > $totalQuantity) {
            return response()->json([
                "message" => __("insufficient stock")
            ])->setStatusCode(400);
        }
    
        // Cek apakah ada gambar yang diupload
        $imageName = null;
        if ($request->hasFile('image')) {
            // Menyimpan gambar ke folder public/barang_keluar
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/barang_keluar', $imageName); // Simpan ke storage
        }
    
        // Menyimpan transaksi keluar
        $data = [
            'item_id' => $request->item_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'invoice_number' => $request->invoice_number,
            'date_out' => $request->date_out,
            'image' => $imageName, // Menyimpan hanya nama file
        ];
    
        $goodsOut = GoodsOut::create($data);
    
        // Mengurangi quantity pada model Item
        $item->quantity -= $request->quantity; // Mengurangi quantity
        $item->save(); // Simpan perubahan
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    //update
    public function update(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsOut::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        $item = Item::find($data->item_id);
        if ($item) {
            $item->quantity += $data->quantity;
            $item->quantity -= $request->quantity;
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
        $data->date_out = $request->date_out;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/barang_keluar', $imageName);
            $data->image = $imageName;
        }

        $status = $data->save();
        if (!$status) {
            return response()->json(
                ["message" => __("data failed to update")]
            )->setStatusCode(400);
        }

        return response()->json([
            "message" => __("data updated successfully")
        ])->setStatusCode(200);
    }
    



public function detail(Request $request): JsonResponse
{
    $id = $request->id;
    
    // Ambil data GoodsOut berdasarkan ID tanpa memuat relasi 'customer'
    $data = GoodsOut::where('id', $id)->first();
    
    // Cek jika data ditemukan
    if (!$data) {
        return response()->json(["message" => __("Goods out not found")], 404);
    }

    // Ambil barang berdasarkan item_id
    $barang = Item::find($data->item_id);
    
    // Cek jika barang ditemukan
    if (!$barang) {
        return response()->json(["message" => __("Item not found")], 404);
    }

    // Siapkan data untuk dikirim dalam response
    $data['part_number'] = $barang->part_number;
    $data['part_name'] = $barang->part_name;
    $data['id_barang'] = $barang->id;
    $data['unit_name'] = $barang->unit_name;  // Pastikan unit_name masih valid
    $data['brand_name'] = $barang->brand_name;

    // Kembalikan response dengan data
    return response()->json(
        ["data" => $data]
    )->setStatusCode(200);
}

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        // Mencari transaksi keluar berdasarkan ID
        $data = GoodsOut::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        // Mendapatkan item yang terkait dengan transaksi keluar
        $item = Item::find($data->item_id);
        if ($item) {
            // Menambahkan kembali quantity yang dihapus
            $item->quantity += $data->quantity; // Mengembalikan quantity
            $item->save(); // Simpan perubahan pada item
        }

        // Menghapus transaksi keluar
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


}
