<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\ConsumableItemMechanical;

class ConsumableItemMechanicalController extends Controller
{
    
    public function index()
    {
        $consumable_item_mechanical = ConsumableItemMechanical::all();

        return view('admin.master.gudang-har-mekanik.barang-bekas-pakai.index');
    }

    public function create()
    {
        return view('barang-bekas-pakai.create');
    }

    public function list(Request $request): JsonResponse
    {
        // Ambil data item tanpa relasi 'unit'
        $consumable_item_mechanical = ConsumableItemMechanical::latest()->get();
    
        if ($request->ajax()) {
            return DataTables::of($consumable_item_mechanical)
                ->addColumn('img', content: function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_bekas_pakai_mekanik/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })
    
                // Hapus addColumn 'unit_name' karena unit sudah tidak ada
    
                ->addColumn('tindakan', function ($data) {
    
                    $viewButton = "<a href='" . route('barang-bekas-pakai-mekanik.show', $data->id) . "' class='view btn btn-info m-1' title='View'><i class='fas fa-eye m-1'></i></a>";
                    $editButton = "<a href='" . route('barang-bekas-pakai-mekanik.edit', $data->id) . "' class='ubah btn btn-success m-1'><i class='fas fa-pen m-1'></i></a>";
                    
                    // Delete button with a form submission for DELETE request
                    $deleteButton = "
                        <form action='" . route('barang-bekas-pakai-mekanik.destroy', $data->id) . "' method='POST' class='d-inline delete-form'>
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
        $data = [
            // 'price' => $request->price,
            'quantity' => $request->quantity,
            'part_name' => $request->part_name,
            'part_number' => $request->part_number,
            'kode_rak' => $request->kode_rak,
            'date_consumable_items' => $request->date_consumable_items,
            'person_name' => $request->person_name,
            'active' => 'true', // Menambahkan atribut active dan set nilainya menjadi true
            'unit_name' => $request->unit_name,
            'brand_name' => $request->brand_name,
            'initial_qty' => $request->quantity,
        ];
    
        // Proses upload gambar
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_bekas_pakai_mekanik/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        //file_reference
    
        // Simpan data item ke database
        ConsumableItemMechanical::create($data);
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    public function show($id)
    {
        $consumable_item_mechanical = ConsumableItemMechanical::find($id);
        return view('admin.master.gudang-har-mekanik.barang-bekas-pakai.show', compact('consumable_item_mechanical'));
    }

    public function edit($id)
    {
        $consumable_item_mechanical = ConsumableItemMechanical::find($id);
        return view('admin.master.gudang-har-mekanik.barang-bekas-pakai.edit', compact('consumable_item_mechanical'));
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
            'date_consumable_items',
            'person_name',
            'unit_name',
            'brand_name',
            'status'
        ]);


        // hash name image
        if ($request->file('image') != null) {
            $image = $request->file('image');
            $image->storeAs('public/barang_bekas_pakai_mekanik/', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $consumable_item_mechanical = ConsumableItemMechanical::find($id);
        $consumable_item_mechanical->update($data);

        return redirect()->route('barang-bekas-pakai-mekanik')->with('success', __('Data updated successfully'));
    }

    public function destroy($id)
    {
        $consumable_item_mechanical = ConsumableItemMechanical::find($id);
    
        if (!$consumable_item_mechanical) {
            return redirect()->route('barang-bekas-pakai-mekanik')->with('error', __('Item not found.'));
        }
        
        Storage::delete('public/barang_bekas_pakai_mekanik/' . $consumable_item_mechanical->image);
    
        $consumable_item_mechanical->delete();
    
        return redirect()->route('barang-bekas-pakai-mekanik')->with('success', __('Data deleted successfully'));
    }
    // //delete
    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        $consumable_item_mechanical = ConsumableItemMechanical::find($id);

        if (!$consumable_item_mechanical) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }

        if ($consumable_item_mechanical->image) {
            Storage::delete('public/barang_bekas_pakai_mekanik/' . $consumable_item_mechanical->image);
        }

        $status = $consumable_item_mechanical->delete();

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
        $consumable_item_mechanical = ConsumableItemMechanical::find($id);
        return view('damaged_items.detail', compact('consumable_item_mechanical'));
    }

}

