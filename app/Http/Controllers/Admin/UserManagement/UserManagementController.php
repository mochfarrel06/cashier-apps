<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UserManagementStoreRequest;
use App\Http\Requests\UserManagement\UserManagementUpdateRequest;
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
        $user = User::findOrFail($id);

        return view('admin.user-management.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserManagementUpdateRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->role = $request->role;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Check if any changes were made
            if ($user->isDirty()) {
                $user->save();
                session()->flash('success', 'Berhasil memperbarui data pengguna');
                return response()->json(['success' => true], 200);
            } else {
                session()->flash('info', 'Tidak melakukan perubahan data pengguna');
                return response()->json(['info' => true], 200);
            }
        } catch (\Exception $e) {
            // Log error dan tampilkan pesan error
            session()->flash('error', 'Terdapat kesalahan pada proses data barang: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Mengambil satuan barang berdasarkan ID. Jika tidak ditemukan, melempar exception.
            $user = User::findOrFail($id);

            if ($user->avatar) {
                $avatarPath = public_path($user->avatar);
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }
            }

            // Menghapus data satuan barang dari database
            $user->delete();

            // Memberikan respons bahwa penghapusan berhasil
            return response(['status' => 'success', 'message' => 'Berhasil menghapus data pengguna']);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan saat menghapus
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
