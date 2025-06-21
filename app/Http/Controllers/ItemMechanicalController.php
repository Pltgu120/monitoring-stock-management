<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\GoodsIn;
use App\Models\GoodsOut;
use Illuminate\View\View;
use App\Exports\ItemExport;
use App\Imports\ItemImport;
use Illuminate\Http\Request;
use App\Models\ItemMechanical;
use Yajra\DataTables\DataTables;
use App\Models\GoodsInMechanical;
use Illuminate\Http\JsonResponse;
use App\Models\GoodsOutMechanical;
use Illuminate\Support\Facades\DB;
use App\Exports\ItemTemplateExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemMechanicalImport;
use Illuminate\Support\Facades\Storage;
use App\Exports\ItemTemplateMechanicalExport;

class ItemMechanicalController extends Controller
{

    public function index():View
    {
        return view('admin.master.gudang-har-mekanik.barang-mekanik.index');
    }

    //import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        // Import data dari file Excel
        Excel::import(new ItemMechanicalImport, $file);

        return redirect()->route('barang-mekanik')->with('success', __('Data imported successfully'));
    }

    //templateExport ItemTemplateMechanicalExport
    public function templateExport()
    {
        return Excel::download(new ItemTemplateMechanicalExport, 'Template-Form-Data-Mechanical.xlsx');
    }


    public function list(Request $request): JsonResponse
    {
        // Ambil data item tanpa relasi 'unit'
        $itemsMechanical = ItemMechanical::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($itemsMechanical)
                ->addColumn('img', function ($data) {
                    if (empty($data->image)) {
                        return "<img src='" . asset('default.png') . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                    }
                    return "<img src='" . asset('storage/barang_mekanik/' . $data->image) . "' style='width:100%;max-width:240px;aspect-ratio:1;object-fit:cover;padding:1px;border:1px solid #ddd'/>";
                })
            
                ->addColumn('tindakan', function ($data) {

                    // Tombol View (Show)
                    $viewButton = "<a href='" . route('barang-mekanik.show', $data->id) . "' class='view btn btn-outline-primary m-1' title='View'>
                                    <i class='fas fa-eye m-1'></i>
                                  </a>";
                
                    // Tombol Edit
                    $editButton = "<a href='" . route('barang-mekanik.edit', $data->id) . "' class='ubah btn btn-outline-success m-1' title='Edit'>
                                    <i class='fas fa-edit m-1'></i>
                                  </a>";
                
                    // Tombol Delete
                    $deleteButton = "
                        <form action='" . route('barang-mekanik.destroy', $data->id) . "' method='POST' class='d-inline delete-form'>
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
        $itemMechanical = ItemMechanical::find($id);

        // Cek jika item ada
        if (!$itemMechanical) {
            return redirect()->route('barang-mekanik')->withErrors(['message' => __("Item not found")]);
        }

        // Return the view dengan data item
        return view('admin.master.gudang-har-mekanik.barang-mekanik.show', compact('itemMechanical'));
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
                'date_items_mechanical' => $request->date_items_mechanical,
                'person_name' => $request->person_name,
                'active' => 'true', // Menambahkan atribut active dan set nilainya menjadi true
                'unit_name' => $request->unit_name,
                'brand_name' => $request->brand_name,
                'initial_qty' => $request->quantity,
                'status' => '0'
            ];
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->storeAs('public/barang_mekanik', $image->hashName());
                $data['image'] = basename($path); // Simpan hanya nama file
            }
            
            
            if ($request->hasFile('file_reference')) {
                $pdf = $request->file('file_reference');
                $path = $pdf->storeAs('public/pdf_files_mekanik', $pdf->hashName());
                $data['file_reference'] = basename($path); // Hanya menyimpan nama file
            }
            
            
    
            // Simpan data item ke database
            ItemMechanical::create($data);
    
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
        $data = ItemMechanical::find($id);

        // Cek jika data ditemukan
        if (!$data) {
            return response()->json(["message" => __("Item not found")], 404);
        }


        return response()->json(["data" => $data], 200);
    }

    public function detailByCode(Request $request): JsonResponse
    {
        $part_number = $request->part_number;

        $data = ItemMechanical::where("part_number", $part_number)->first();

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
        $itemMechanical = ItemMechanical::findOrFail($id);

        return view('admin.master.gudang-har-mekanik.barang-mekanik.edit', compact('itemMechanical'));
    }
 
    public function update(Request $request, $id)
    {
        $itemMechanical = ItemMechanical::find($id);
    
        if (!$itemMechanical) {
            return response()->json(["message" => __("Item not found")])->setStatusCode(404);
        }
    
        $data = $request->only([
            'part_name', 'part_number', 'kode_rak', 'date_items_mechanical', 
            'person_name', 'unit_name', 'brand_name', 'quantity', 'status'
        ]);
    
        if ($request->file('image')) {
            Storage::delete('public/barang_mekanik/' . $itemMechanical->image);
            $image = $request->file('image');
            $imagePath = $image->storeAs('public/barang_mekanik/', $image->hashName());
            $data['image'] = $image->hashName();
        }
    
        if ($request->file('file_reference')) {
            Storage::delete('public/pdf_files_mekanik/' . $itemMechanical->file_reference);
            $pdf = $request->file('file_reference');
            $pdfPath = $pdf->storeAs('public/pdf_files_mekanik/', $pdf->hashName());
            $data['file_reference'] = $pdf->hashName();
        }
    
        $itemMechanical->update($data);
    
        return redirect()->route('barang-mekanik')->with('success', __('Data updated successfully.'));
    }
    


    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        $itemMechanical = ItemMechanical::find($id);

        if (!$itemMechanical) {
            return response()->json([
                "message" => __("Item not found")
            ])->setStatusCode(404);
        }


        if ($itemMechanical->image) {
            Storage::delete('public/barang-mekanik/' . $itemMechanical->image);
        }

        $status = $itemMechanical->delete();

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
        $itemMechanical = ItemMechanical::find($id);

        if (!$itemMechanical) {
            return redirect()->route('barang-mekanik')->withErrors(['message' => __("Item not found")]);
        }

        if ($itemMechanical->image) {
            Storage::delete('public/barang_mekanik/' . $itemMechanical->image);
        }

        GoodsOutMechanical::where('item_mechanical_id', $id)->delete();  // Hapus dari goods_out
        GoodsInMechanical::where('item_mechanical_id', $id)->delete();   // Hapus dari goods_in jika perlu

        $status = $itemMechanical->delete();

        if (!$status) {
            return redirect()->route('barang-mekanik')->withErrors(['message' => __("Data failed to delete")]);
        }

        return redirect()->route('barang-mekanik')->with('success', __('Data deleted successfully'));
    }

}
