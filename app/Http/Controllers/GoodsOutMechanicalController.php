<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ItemMechanical;
use Yajra\DataTables\DataTables;
use App\Models\GoodsInMechanical;
use Illuminate\Http\JsonResponse;
use App\Models\GoodsOutMechanical;
use Illuminate\Support\Facades\Log;

class GoodsOutMechanicalController extends Controller
{
    public function index():View
    {
        $in_status_mechanical = ItemMechanical::where('active','true')->count();
        // $customers = Customer::all();
        return view('admin.master.gudang-har-mekanik.transaksi.keluar',compact('in_status_mechanical'));
    }

    public function list(Request $request): JsonResponse
    {
        $goodsoutsMechanical = GoodsOutMechanical::with('itemMechanical', 'user')->latest()->get();
        if ($request->ajax()) {
            return DataTables::of($goodsoutsMechanical)

                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_keluar_mekanik/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })

                ->addColumn('quantity', function ($data) {
                    // Hapus referensi ke 'unit'
                    $itemMechanical = ItemMechanical::find($data->itemMechanical->id);
                    return $data->quantity; // Hanya tampilkan quantity tanpa satuan unit
                })
                ->addColumn('date_received_mechanical', function ($data) {
                    return Carbon::parse($data->date_received_mechanical)->format('d F Y');
                })
                ->addColumn('part_number', function ($data) {
                    return $data->itemMechanical->part_number;
                })
                ->addColumn('part_name', function ($data) {
                    return $data->itemMechanical->part_name;
                })
                ->addColumn('unit_name', function ($data) {
                    return $data->itemMechanical->unit_name;
                })
                ->addColumn('brand_name', function ($data) {
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
        // Mendapatkan bulan dan tahun dari tanggal keluar
        $currentMonth = date('m', strtotime($request->date_out));
        $currentYear = date('Y', strtotime($request->date_out));
    
        // Menghitung total barang masuk dan keluar pada bulan ini
        $goodsInThisMonth = GoodsInMechanical::whereMonth('date_received_mechanical', $currentMonth)
            ->whereYear('date_received_mechanical', $currentYear)
            ->sum('quantity');
    
        $goodsOutThisMonth = GoodsOutMechanical::whereMonth('date_out', $currentMonth)
            ->whereYear('date_out', $currentYear)
            ->sum('quantity');
    
        // Cek total quantity dari item
        $itemMechanical = ItemMechanical::find($request->item_mechanical_id);
        if (!$itemMechanical) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }
    
        // Total quantity item saat ini
        $totalQuantity = $itemMechanical->quantity;
    
        // Cek apakah jumlah barang yang dikeluarkan lebih besar dari total stock
        if ($request->quantity > $totalQuantity) {
            return response()->json([
                "message" => __("insufficient stock")
            ])->setStatusCode(400);
        }
    
        // Cek apakah ada gambar yang diupload
        $imageName = null;
        if ($request->hasFile('image')) {
            // Menyimpan gambar ke folder public/barang_keluar_mekanik
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/barang_keluar_mekanik', $imageName);
        }
    
        // Menyimpan transaksi keluar
        $data = [
            'item_mechanical_id' => $request->item_mechanical_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'invoice_number' => $request->invoice_number,
            'date_out' => $request->date_out,
            'image' => $imageName, // Simpan hanya nama file
        ];
    
        $goodsOutMechanical = GoodsOutMechanical::create($data);
    
        // Mengurangi quantity pada model Item
        $itemMechanical->quantity -= $request->quantity; // Mengurangi quantity
        $itemMechanical->save(); // Simpan perubahan
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }
    

    //update
    public function update(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsOutMechanical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }
    
        $data->user_id = $request->user_id;
        $data->date_out = $request->date_out;
        $data->quantity = $request->quantity;
        $data->invoice_number = $request->invoice_number;
        $data->item_mechanical_id = $request->item_mechanical_id;
    
        // Jika file gambar ada, simpan ke direktori dan tambahkan nama file ke $data
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_keluar_mekanik/', $image->hashName());
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
    
    // Ambil data GoodsOut berdasarkan ID tanpa memuat relasi 'customer'
    $data = GoodsOutMechanical::where('id', $id)->first();
    
    // Cek jika data ditemukan
    if (!$data) {
        return response()->json(["message" => __("Goods out not found")], 404);
    }

    // Ambil barang berdasarkan item_mechanical_id
    $barangMechanical = ItemMechanical::find($data->item_mechanical_id);
    
    // Cek jika barang ditemukan
    if (!$barangMechanical) {
        return response()->json(["message" => __("Item not found")], 404);
    }

    // Siapkan data untuk dikirim dalam response
    $data['part_number'] = $barangMechanical->part_number;
    $data['part_name'] = $barangMechanical->part_name;
    $data['id_barang'] = $barangMechanical->id;
    $data['unit_name'] = $barangMechanical->unit_name;  // Pastikan unit_name masih valid
    $data['brand_name'] = $barangMechanical->brand_name;

    // Kembalikan response dengan data
    return response()->json(
        ["data" => $data]
    )->setStatusCode(200);
}

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        // Mencari transaksi keluar berdasarkan ID
        $data = GoodsOutMechanical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        // Mendapatkan item yang terkait dengan transaksi keluar
        $itemMechanical = ItemMechanical::find($data->item_mechanical_id);
        if ($itemMechanical) {
            // Menambahkan kembali quantity yang dihapus
            $itemMechanical->quantity += $data->quantity; // Mengembalikan quantity
            $itemMechanical->save(); // Simpan perubahan pada item
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

