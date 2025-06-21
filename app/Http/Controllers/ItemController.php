<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\GoodsIn;
use App\Models\GoodsOut;
use Illuminate\View\View;
use App\Exports\ItemExport;
use App\Imports\ItemImport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exports\ItemTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{

        public function export()
    {
        return Excel::download(new ItemExport, 'Data-Sparepart.xlsx');
    }

    //tempplateExport
    public function templateExport()
    {
        return Excel::download(new ItemTemplateExport, 'Template-Form-Data-Sparepart.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        // Import data dari file Excel
        Excel::import(new ItemImport, $file);

        return redirect()->route('barang')->with('success', __('Data imported successfully'));
    }

    public function index():View
    {
        return view('admin.master.gudang-har-elektrik.barang.index');
    }
    public function list(Request $request): JsonResponse
    {
        // Ambil data item tanpa relasi 'unit'
        $items = Item::latest()->get();
    
        if ($request->ajax()) {
            return DataTables::of($items)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })
    
                // Hapus addColumn 'unit_name' karena unit sudah tidak ada
    
                ->addColumn('tindakan', function ($data) {
    
                    // Tombol View (Show)
                    $viewButton = "<a href='" . route('barang.show', $data->id) . "' class='view btn btn-outline-primary m-1' title='View'>
                                    <i class='fas fa-eye m-1'></i>
                                  </a>";
                
                    // Tombol Edit
                    $editButton = "<a href='" . route('barang.edit', $data->id) . "' class='ubah btn btn-outline-success m-1' title='Edit'>
                                    <i class='fas fa-edit m-1'></i>
                                  </a>";
                
                    // Tombol Delete
                    $deleteButton = "
                        <form action='" . route('barang.destroy', $data->id) . "' method='POST' class='d-inline delete-form'>
                            " . csrf_field() . "
                            " . method_field('DELETE') . "
                            <button type='button' class='hapus btn btn-outline-danger m-1' title='Delete'>
                                <i class='fas fa-trash-alt m-1'></i>
                            </button>
                        </form>
                    ";
    
                    return $viewButton . $editButton . $deleteButton;
                })
    
                ->rawColumns(['img', 'tindakan'])
                ->make(true);
        }
    }
    

    public function show($id)
    {
        // Temukan item berdasarkan ID tanpa relasi 'unit'
        $item = Item::find($id);
    
        // Cek jika item ada
        if (!$item) {
            return redirect()->route('barang')->withErrors(['message' => __("Item not found")]);
        }
    
        // Return the view dengan data item
        return view('admin.master.gudang-har-elektrik.barang.show', compact('item'));
    }
    





public function save(Request $request): JsonResponse
{

    // Data untuk disimpan
    $data = [
        // 'price' => $request->price,
        'quantity' => $request->quantity,
        'part_name' => $request->part_name,
        'part_number' => $request->part_number,
        'kode_rak' => $request->kode_rak,
        'date_items' => $request->date_items,
        'person_name' => $request->person_name,
        'active' => 'true', // Menambahkan atribut active dan set nilainya menjadi true
        'unit_name' => $request->unit_name,
        'brand_name' => $request->brand_name,
        'initial_qty' => $request->quantity,
        'status' => '0'
    ];

    // Proses upload gambar
    if ($request->file('image') != null) {
        $image = $request->file('image');
        $image->storeAs('public/barang/', $image->hashName());
        $data['image'] = $image->hashName();
    }

    //file_reference
    if ($request->file('file_reference') != null) {
        $pdf = $request->file('file_reference');
        $pdf->storeAs('public/pdf_files/', $pdf->hashName());
        $data['file_reference'] = $pdf->hashName();
    }

    // Simpan data item ke database
    Item::create($data);

    return response()->json([
        "message" => __("saved successfully")
    ])->setStatusCode(200);
}


    


public function detail(Request $request): JsonResponse
{
    $id = $request->id;
    
    // Cek apakah ID valid
    if (!$id) {
        return response()->json(["message" => __("Invalid ID")], 400);
    }

    // Ambil data item tanpa relasi 'unit'
    $data = Item::find($id);
    
    // Cek jika data ditemukan
    if (!$data) {
        return response()->json(["message" => __("Item not found")], 404);
    }


    return response()->json(["data" => $data], 200);
}



public function detailByCode(Request $request): JsonResponse
{
    $part_number = $request->part_number;

    $data = Item::where("part_number", $part_number)->first();
    
    // Cek apakah data ditemukan
    if (!$data) {
        return response()->json(["message" => __("Item not found")], 404);
    }


    return response()->json(
        ["data" => $data]
    )->setStatusCode(200);
}


    public function edit($id)
    {
        $item = Item::findOrFail($id);
    
        return view('admin.master.gudang-har-elektrik.barang.edit', compact('item'));
    }
    
     
    public function update(Request $request, $id)
    {
        // Cari item berdasarkan ID yang diberikan di URL
        $item = Item::find($id);
    
        if (!$item) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }
    
        // Data yang akan di-update
        $data = $request->only([
            'quantity',
            'part_name',
            'part_number',
            'kode_rak',
            'date_items',
            'person_name',
            'unit_name',
            'brand_name',
            'initial_qty',
            'status'
        ]);
    
        if ($request->file('image') != null) {
            Storage::delete('public/barang/' . $item->image); // Menghapus file lama
            $image = $request->file('image');
            $imagePath = $image->storeAs('public/barang/', $image->hashName());
            $data['image'] = $image->hashName(); // Menyimpan nama file baru
        }

        //file_reference
        if ($request->file('file_reference') != null) {
            Storage::delete('public/pdf_files/' . $item->file_reference); // Menghapus file lama
            $pdf = $request->file('file_reference');
            $pdfPath = $pdf->storeAs('public/pdf_files/', $pdf->hashName());
            $data['file_reference'] = $pdf->hashName(); // Menyimpan nama file baru
        }
    
        // Update item dengan data baru
        $item->fill($data);
        $item->save();
    
        // Redirect ke view 'index' dengan nama route 'barang'
        return redirect()->route('barang')->with('success', __('Data changed successfully'));
    }
    
    

    public function delete(Request $request): JsonResponse
{
    $id = $request->id;
    $item = Item::find($id);

    if (!$item) {
        return response()->json([
            "message" => __("Item not found")
        ])->setStatusCode(404);
    }


    if ($item->image) {
        Storage::delete('public/barang/' . $item->image);
    }

    $status = $item->delete();

    if (!$status) {
        return response()->json([
            "message" => __("Data failed to delete")
        ])->setStatusCode(400);
    }

    return response()->json([
        "message" => __("Data deleted successfully")
    ])->setStatusCode(200);
}


public function destroy($id)
{
    $item = Item::find($id);

    if (!$item) {
        return redirect()->route('barang')->with('error', __('Item not found.'));
    }

    GoodsOut::where('item_id', $id)->delete();  // Hapus dari goods_out
    GoodsIn::where('item_id', $id)->delete();   // Hapus dari goods_in jika perlu

    Storage::delete('public/barang/' . $item->image);
    Storage::delete('public/pdf_files/' . $item->file_reference);

    $item->delete();

    return redirect()->route('barang')->with('success', __('Data deleted successfully'));
}

}
