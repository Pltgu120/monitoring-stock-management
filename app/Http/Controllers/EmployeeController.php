<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class EmployeeController extends Controller
{
    public function index(): View
    {   $roles = Role::where('name','employee')->get();
        if(Auth::user()->role->name == 'super_admin'){
            $roles = Role::all();
        }
        return view('admin.settings.employee',compact('roles'));
    }

    public function list(Request $request): JsonResponse
    {
        // Define allowed role IDs
        $allowedRoleIds = [1, 2, 3]; // Assuming 1 -> super_admin, 2 -> admin, 3 -> employee
    
        // Filter users by role
        $staff = User::with('role')->whereHas('role', function (Builder $builder) use ($allowedRoleIds) {
            $builder->whereIn('id', $allowedRoleIds);
        })->latest()->get();
    
        // Adjust query if the current user is an employee
        if (Auth::user()->role->name == 'employee') {
            $id_staff = Role::where('name', 'employee')->first()->id;
            $staff = User::with('role')->where('role_id', $id_staff)->latest()->get();
        }
    
        // Handle AJAX request
        if ($request->ajax()) {
            return DataTables::of($staff)
                ->addColumn('username', function ($data) {
                    return strtolower($data->username); // Konversi ke huruf kecil
                })
                ->addColumn('role_name', function ($data) {
                    return $data->role->name;
                })
                ->addColumn('tindakan', function ($data) {
                    $button = "<button class='ubah btn btn-success m-1' id='" . $data->id . "'><i class='fas fa-pen m-1'></i>" . __("edit") . "</button>";
                    $button .= "<button class='hapus btn btn-danger m-1' id='" . $data->id . "'><i class='fas fa-trash m-1'></i>" . __("delete") . "</button>";
                    return $button;
                })
                ->rawColumns(['tindakan'])
                ->make(true);
        }
    }
    
    

    public function save(Request $request): JsonResponse
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'nullable|integer|in:0,1', // Hanya 0 atau 1 yang diperbolehkan
        ]);
    
        $user = new User();
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role_id = $validatedData['role_id'];
    
        // Set status dengan default 1 jika tidak diberikan
        $user->status = $validatedData['status'] ?? 1;
    
        $status = $user->save();
    
        if (!$status) {
            return response()->json(
                ["message" => __("failed to save")]
            )->setStatusCode(400);
        }
    
        return response()->json([
            "message" => __("saved successfully")
        ])->setStatusCode(200);
    }

    // update
    public function update(Request $request): JsonResponse
    {
        // Validasi input
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->id,
            'email' => 'required|email|max:255|unique:users,email,' . $request->id,
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'nullable|integer|in:0,1', // Hanya 0 atau 1 yang diperbolehkan
        ]);
    
        $user = User::find($validatedData['id']);
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'];
    
        // Set status dengan default 1 jika tidak diberikan
        $user->status = $validatedData['status'] ?? 1;
    
        $status = $user->save();
    
        if (!$status) {
            return response()->json(
                ["message" => __("failed to update")]
            )->setStatusCode(400);
        }
    
        return response()->json([
            "message" => __("updated successfully")
        ])->setStatusCode(200);
    }
    
    

    public function detail(Request $request): JsonResponse
    {
        $id = $request -> id;
        $user = User::find($id);
        return response()->json(
            ["data"=>$user]
        )->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request -> id;
        $user = User::find($id);
        $status = $user -> delete();
        if(!$status){
            return response()->json(
                ["message"=>__("data failed to delete")]
            )->setStatusCode(400);
        }
        return response()->json([
            "message"=>__("data deleted successfully")
        ]) -> setStatusCode(200);
    }

    //destroy
    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            // Return JSON response if user is not found
            return response()->json(['message' => __('Item not found.')], 404);
        }
    
        $user->delete();
    
        // Return success message as JSON response
        return response()->json(['message' => __('Data deleted successfully')], 200);
    }
    

}
