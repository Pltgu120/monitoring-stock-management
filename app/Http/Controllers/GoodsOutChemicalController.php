<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\ItemChemical;
use Illuminate\Http\Request;
use App\Models\GoodsInChemical;
use App\Models\GoodsOutChemical;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GoodsOutChemicalController extends Controller
{
    public function index():View
    {
        $in_status_chemical = ItemChemical::where('active','true')->count();
        // $customers = Customer::all();
        return view('admin.master.gudang-kimia.transaksi.keluar',compact('in_status_chemical'));
    }

    public function list(Request $request): JsonResponse
    {
        $goodsoutsChemical = GoodsOutChemical::with('itemChemical', 'user')->latest()->get();
        if ($request->ajax()) {
            return DataTables::of($goodsoutsChemical)

            ->addColumn('img', function ($data) {
                if (empty($data->image)) {
                    return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                }
                return "<img src='" . asset('storage/barang_keluar_kimia/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
            })

                ->addColumn('quantity', function ($data) {
                    // Hapus referensi ke 'unit'
                    $itemChemical = ItemChemical::find($data->itemChemical->id);
                    return $data->quantity; // Hanya tampilkan quantity tanpa satuan unit
                })
                ->addColumn('date_received_chemical', function ($data) {
                    return Carbon::parse($data->date_received_chemical)->format('d F Y');
                })
                ->addColumn('part_number', function ($data) {
                    return $data->itemChemical->part_number;
                })
                ->addColumn('part_name', function ($data) {
                    return $data->itemChemical->part_name;
                })
                ->addColumn('unit_name', function ($data) {
                    return $data->itemChemical->unit_name;
                })
                ->addColumn('brand_name', function ($data) {
                    return $data->itemChemical->brand_name;
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
        $goodsInThisMonth = GoodsInChemical::whereMonth('date_received_chemical', $currentMonth)
            ->whereYear('date_received_chemical', $currentYear)
            ->sum('quantity');
    
        $goodsOutThisMonth = GoodsOutChemical::whereMonth('date_out', $currentMonth)
            ->whereYear('date_out', $currentYear)
            ->sum('quantity');
    
        // Cek total quantity dari item
        $itemChemical = ItemChemical::find($request->item_chemical_id);
        if (!$itemChemical) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }
    
        // Total quantity item saat ini
        $totalQuantity = $itemChemical->quantity;
    
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
            $image->storeAs('public/barang_keluar_kimia', $imageName); // Menyimpan gambar
        }
    
        // Menyimpan transaksi keluar
        $data = [
            'item_chemical_id' => $request->item_chemical_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'invoice_number' => $request->invoice_number,
            'date_out' => $request->date_out,
            'image' => $imageName, // Hanya menyimpan nama file
        ];
    
        $goodsOutChemical = GoodsOutChemical::create($data);
    
        // Mengurangi quantity pada model Item
        $itemChemical->quantity -= $request->quantity; // Mengurangi quantity
        $itemChemical->save(); // Simpan perubahan
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    //update
    public function update(Request $request): JsonResponse
    {
        $id = $request->id;
        $data = GoodsOutChemical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }
    
        $data->user_id = $request->user_id;
        $data->date_out = $request->date_out;
        $data->quantity = $request->quantity;
        $data->invoice_number = $request->invoice_number;
        $data->item_chemical_id = $request->item_chemical_id;
    
        // Jika file gambar ada, simpan ke direktori dan tambahkan nama file ke $data
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_keluar_kimia/', $image->hashName());
            $data->image = $image->hashName();
        }
    
        $status = $data->save();
        if (!$status) {
            return response()->json(
                ["message" => __("data failed to update")]
            )->setStatusCode(400);
        }
    
        // Find the related item
        $itemChemical = ItemChemical::find($request->item_chemical_id);
    
        // Update the item's quantity by adding the quantity of the newly received goods
        $itemChemical->quantity += $request->quantity;
        $itemChemical->active = "true";
        $itemChemical->save();
    
        return response()->json([
            "message" => __("data updated successfully")
        ])->setStatusCode(200);
    }
    



public function detail(Request $request): JsonResponse
{
    $id = $request->id;
    
    // Ambil data GoodsOut berdasarkan ID tanpa memuat relasi 'customer'
    $data = GoodsOutChemical::where('id', $id)->first();
    
    // Cek jika data ditemukan
    if (!$data) {
        return response()->json(["message" => __("Goods out not found")], 404);
    }

    // Ambil barang berdasarkan item_chemical_id
    $barangChemical = ItemChemical::find($data->item_chemical_id);
    
    // Cek jika barang ditemukan
    if (!$barangChemical) {
        return response()->json(["message" => __("Item not found")], 404);
    }

    // Siapkan data untuk dikirim dalam response
    $data['part_number'] = $barangChemical->part_number;
    $data['part_name'] = $barangChemical->part_name;
    $data['id_barang'] = $barangChemical->id;
    $data['unit_name'] = $barangChemical->unit_name;  // Pastikan unit_name masih valid
    $data['brand_name'] = $barangChemical->brand_name;

    // Kembalikan response dengan data
    return response()->json(
        ["data" => $data]
    )->setStatusCode(200);
}

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        // Mencari transaksi keluar berdasarkan ID
        $data = GoodsOutChemical::find($id);
        if (!$data) {
            return response()->json(
                ["message" => __("data not found")]
            )->setStatusCode(404);
        }

        // Mendapatkan item yang terkait dengan transaksi keluar
        $itemChemical = ItemChemical::find($data->item_chemical_id);
        if ($itemChemical) {
            // Menambahkan kembali quantity yang dihapus
            $itemChemical->quantity += $data->quantity; // Mengembalikan quantity
            $itemChemical->save(); // Simpan perubahan pada item
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
