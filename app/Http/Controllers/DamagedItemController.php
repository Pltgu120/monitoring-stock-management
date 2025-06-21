<?php

namespace App\Http\Controllers;

use App\Models\DamagedItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Exports\DamagedItemExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DamagedItemController extends Controller
{

    public function index()
    {
        $damaged_items = DamagedItem::all();

        return view('admin.master.gudang-har-elektrik.barang-rusak.index');
    }

    //export
    public function export()
    {
        return Excel::download(new DamagedItemExport, 'Data-Barang_Rusak.xlsx');
    }



    public function create()
    {
        return view('damaged_items.create');
    }

    public function list(Request $request): JsonResponse
    {
        // Ambil data item tanpa relasi 'unit'
        $damagedItems = DamagedItem::latest()->get();
    
        if ($request->ajax()) {
            return DataTables::of($damagedItems)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_rusak/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })
    
                // Hapus addColumn 'unit_name' karena unit sudah tidak ada
    
                ->addColumn('tindakan', function ($data) {
    
                    $viewButton = "<a href='" . route('barang-rusak.show', $data->id) . "' class='view btn btn-info m-1' title='View'><i class='fas fa-eye m-1'></i></a>";
                    $editButton = "<a href='" . route('barang-rusak.edit', $data->id) . "' class='ubah btn btn-success m-1'><i class='fas fa-pen m-1'></i></a>";
                    
                    // Delete button with a form submission for DELETE request
                    $deleteButton = "
                        <form action='" . route('barang-rusak.destroy', $data->id) . "' method='POST' class='d-inline delete-form'>
                            " . csrf_field() . "
                            " . method_field('DELETE') . "
                            <button type='button' class='hapus btn btn-danger m-1'><i class='fas fa-trash m-1'></i></button>
                        </form>
                    ";
    
                    return $viewButton . $editButton . $deleteButton;
                })
    
                ->rawColumns(['img', 'tindakan'])
                ->make(true);
        }
    }
    

    public function store(Request $request): JsonResponse
    {
    
        // Data untuk disimpan
        $data = [
            // 'price' => $request->price,
            'quantity' => $request->quantity,
            // 'category_id' => $request->category_id,
            // 'brand_id' => $brand_id, // Menggunakan id merk (baik baru atau yang sudah ada)
            // 'unit_id' => $request->unit_id,
            'part_name' => $request->part_name,
            'part_number' => $request->part_number,
            'kode_rak' => $request->kode_rak,
            'date_damaged_items' => $request->date_damaged_items,
            'person_name' => $request->person_name,
            'active' => 'true', // Menambahkan atribut active dan set nilainya menjadi true
            'unit_name' => $request->unit_name,
            'brand_name' => $request->brand_name,
            'initial_qty' => $request->quantity,
        ];
    
        // Proses upload gambar
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_rusak/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        //file_reference
    
        // Simpan data item ke database
        DamagedItem::create($data);
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }
    
    

    public function show($id)
    {
        $damaged_item = DamagedItem::find($id);
        return view('admin.master.gudang-har-elektrik.barang-rusak.show', compact('damaged_item'));
    }

    public function edit($id)
    {
        $damaged_item = DamagedItem::find($id);
        return view('admin.master.gudang-har-elektrik.barang-rusak.edit', compact('damaged_item'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'image',
            'quantity',
            'initial_qty',
            'part_name',
            'part_number',
            'kode_rak',
            'date_damaged_items',
            'person_name',
            'unit_name',
            'brand_name',
            'status'
        ]);


        // hash name image
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_rusak/', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $damaged_item = DamagedItem::find($id);
        $damaged_item->update($data);

        return redirect()->route('barang-rusak')->with('success', __('Data updated successfully'));
    }

    public function destroy($id)
    {
        $damaged_item = DamagedItem::find($id);
    
        if (!$damaged_item) {
            return redirect()->route('barang-rusak')->with('error', __('Item not found.'));
        }
        
        Storage::delete('public/barang_rusak/' . $damaged_item->image);
    
        $damaged_item->delete();
    
        return redirect()->route('barang-rusak')->with('success', __('Data deleted successfully'));
    }
    // //delete
    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        $damaged_item = DamagedItem::find($id);

        if (!$damaged_item) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }

        if ($damaged_item->image) {
            Storage::delete('public/barang_rusak/' . $damaged_item->image);
        }

        $status = $damaged_item->delete();

        if ($status) {
            return response()->json([
                "message" => __("Item deleted successfully")
            ]);
        } else {
            return response()->json([
                "message" => __("Item can't be deleted")
            ])->setStatusCode(500);
        }
    }

    //detail
    public function detail($id)
    {
        $damaged_item = DamagedItem::find($id);
        return view('damaged_items.detail', compact('damaged_item'));
    }
    
}
