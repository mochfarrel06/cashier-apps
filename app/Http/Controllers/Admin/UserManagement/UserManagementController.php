<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UserManagementStoreRequest;
use App\Models\User;
use App\Traits\ProfileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    use ProfileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('admin.user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserManagementStoreRequest $request)
    {
        try {
            $imagePath = $this->uploadImage($request, 'avatar');

            $user = new User();
            $user->name = $request->name;
            $user->avatar =  isset($imagePath) ? $imagePath : 'avatar';
            $user->username = $request->username;
            $user->email = $request->email;
            $user->transaction_code = $request->transaction_code;
            $user->location = $request->location;
            $user->role = $request->role;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'Berhasil menambahkan data pengguna');
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            // Log error dan tampilkan pesan error
            session()->flash('error', 'Terdapat kesalahan pada proses data barang: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
