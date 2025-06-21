<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Exports\ItemExport;
use App\Imports\ItemImport;
use App\Models\ItemChemical;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Imports\ItemChemicalImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\ItemTemplateChemicalExport;
use App\Models\GoodsInChemical;
use App\Models\GoodsOutChemical;

class ItemChemicalController extends Controller
{

    public function index():View
    {
        return view('admin.master.gudang-kimia.barang-kimia.index');
    }

    //import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        // Import data dari file Excel
        Excel::import(new ItemChemicalImport, $file);

        return redirect()->route('barang-kimia')->with('success', __('Data imported successfully'));
    }

    //export ItemTemplateChemicalExport
    public function templateExport()
    {
        return Excel::download(new ItemTemplateChemicalExport, 'Template-Form-Data-Barang-Kimia.xlsx');
    }

    public function list(Request $request): JsonResponse
    {
        // Ambil data item tanpa relasi 'unit'
        $itemsChemical = ItemChemical::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($itemsChemical)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_kimia/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })

                // Hapus addColumn 'unit_name' karena unit sudah tidak ada

                ->addColumn('tindakan', function ($data) {

                    // Tombol View (Show)
                    $viewButton = "<a href='" . route('barang-kimia.show', $data->id) . "' class='view btn btn-outline-primary m-1' title='View'>
                                    <i class='fas fa-eye m-1'></i>
                                  </a>";
                
                    // Tombol Edit
                    $editButton = "<a href='" . route('barang-kimia.edit', $data->id) . "' class='ubah btn btn-outline-success m-1' title='Edit'>
                                    <i class='fas fa-edit m-1'></i>
                                  </a>";
                
                    // Tombol Delete
                    $deleteButton = "
                        <form action='" . route('barang-kimia.destroy', $data->id) . "' method='POST' class='d-inline delete-form'>
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
        $itemChemical = ItemChemical::find($id);

        // Cek jika item ada
        if (!$itemChemical) {
            return redirect()->route('barang-kimia')->withErrors(['message' => __("Item not found")]);
        }

        // Return the view dengan data item
        return view('admin.master.gudang-kimia.barang-kimia.show', compact('itemChemical'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Data untuk disimpan
            $data = [
                'quantity' => $request->quantity,
                'part_name' => $request->part_name,
                'part_number' => $request->part_number,
                'kode_rak' => $request->kode_rak,
                'date_items_chemical' => $request->date_items_chemical,
                'person_name' => $request->person_name,
                'active' => 'true', // Menambahkan atribut active dan set nilainya menjadi true
                'unit_name' => $request->unit_name,
                'brand_name' => $request->brand_name,
                'initial_qty' => $request->quantity,
                'status' => '0',
            ];
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/barang_kimia', $image->hashName());
                $data['image'] = $image->hashName(); // Hanya simpan nama file
            }
            
            
            if ($request->hasFile('file_reference')) {
                $pdf = $request->file('file_reference');
                $pdf->storeAs('public/pdf_files_kimia', $pdf->hashName());
                $data['file_reference'] = $pdf->hashName(); // Simpan hanya nama file
            }
            
            // Simpan data item ke database
            ItemChemical::create($data);
    
            return response()->json([
                "message" => __("saved successfully")
            ])->setStatusCode(200);
    
        } catch (\Exception $e) {
            // Log error ke laravel.log
            Log::error('Error saving item: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
    
            // Kembalikan error dengan status 500
            return response()->json([
                "error" => __("An error occurred while saving the item"),
                "details" => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    

    public function detail(Request $request): JsonResponse
    {
        $id = $request->id;

        // Cek apakah ID valid
        if (!$id) {
            return response()->json(["message" => __("Invalid ID")], 400);
        }

        // Ambil data item tanpa relasi 'unit'
        $data = ItemChemical::find($id);

        // Cek jika data ditemukan
        if (!$data) {
            return response()->json(["message" => __("Item not found")], 404);
        }


        return response()->json(["data" => $data], 200);
    }

    public function detailByCode(Request $request): JsonResponse
    {
        $part_number = $request->part_number;

        $data = ItemChemical::where("part_number", $part_number)->first();

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
        $itemChemical = ItemChemical::findOrFail($id);

        return view('admin.master.gudang-kimia.barang-kimia.edit', compact('itemChemical'));
    }
 
    public function update(Request $request, $id)
    {
        $itemChemical = ItemChemical::find($id);
    
        if (!$itemChemical) {
            return response()->json(["message" => __("Item not found")])->setStatusCode(404);
        }
    
        $data = $request->only([
            'part_name', 'part_number', 'kode_rak', 'date_items_chemical', 
            'person_name', 'unit_name', 'brand_name', 'quantity', 'status'
        ]);
    
        if ($request->file('image')) {
            Storage::delete('public/barang_kimia/' . $itemChemical->image);
            $image = $request->file('image');
            $imagePath = $image->storeAs('public/barang_kimia/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        if ($request->file('file_reference')) {
            Storage::delete('public/pdf_files_kimia/' . $itemChemical->file_reference);
            $pdf = $request->file('file_reference');
            $pdfPath = $pdf->storeAs('public/pdf_files_kimia/', $pdf->hashName());
            $data['file_reference'] = $pdf->hashName();
        }
    
        $itemChemical->update($data);
    
        return redirect()->route('barang-kimia')->with('success', __('Data updated successfully.'));
    }
    


    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        $itemChemical = ItemChemical::find($id);

        if (!$itemChemical) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }

        if ($itemChemical->image) {
            Storage::delete('public/barang-kimia/' . $itemChemical->image);
        }

        $status = $itemChemical->delete();

        if (!$status) {
            return response()->json([
                "message" => __("Data failed to delete")
            ])->setStatusCode(400);
        }

        return response()->json([
            "message" => __("Data deleted successfully")
        ])->setStatusCode(200);
    }

    //destroy by id
    public function destroy($id)
    {
        $itemChemical = ItemChemical::find($id);

        if (!$itemChemical) {
            return redirect()->route('barang-kimia')->withErrors(['message' => __("Item not found")]);
        }

        if ($itemChemical->image) {
            Storage::delete('public/barang_kimia/' . $itemChemical->image);
        }

        //file_reference
        if ($itemChemical->file_reference) {
            Storage::delete('public/pdf_files_kimia/' . $itemChemical->file_reference);
        }

        GoodsOutChemical::where('item_chemical_id', $id)->delete();  // Hapus dari goods_out
        GoodsInChemical::where('item_chemical_id', $id)->delete(); 

        $status = $itemChemical->delete();

        if (!$status) {
            return redirect()->route('barang-kimia')->withErrors(['message' => __("Data failed to delete")]);
        }

        return redirect()->route('barang-kimia')->with('success', __('Data deleted successfully'));
    }

}
