<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\OwnerLaundry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class OwnerController extends Controller
{

    public function index()
    {
        $title = "Owner Management";

        if (!auth()->user()->hasRole('owner')) {
            return abort('403', 'Anda tidak memiliki izin');
        }

        $owner = OwnerLaundry::query()
            ->join('users as u', 'owner_laundry.user_id', '=', 'u.id')
            ->where('u.deleted_at', null)
            ->orderBy('owner_laundry.created_at', 'asc')->get();

        $ownerTrash = User::join('owner_laundry as o', 'o.user_id', '=', 'users.id')->onlyTrashed()->orderBy('o.created_at', 'asc')->get();

        return view('dashboard.user.owner.index', compact('title', 'owner', 'ownerTrash'));
    }

    public function view(Request $request)
    {
        $title = "Detail User | Owner";
        $trash = false;
        $userRole = auth()->user()->roles[0]->name;
        $user = User::where('slug', $request->user)->first();

        if ($user == null && $userRole != 'owner') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'owner') {
            $profile = OwnerLaundry::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.pic-rw.lihat', compact('title', 'user', 'profile', 'trash'));
    }

    public function create()
    {
        $title = "Tambah User | Owner";
        // $userRole = auth()->user()->roles[0]->name;
        $role = Role::where('name', 'owner')->get();
        return view('dashboard.user.owner.tambah', compact('title', 'role'));
    }

    public function edit(Request $request)
    {
        $title = "Ubah User | Owner";
        $userRole = auth()->user()->roles[0]->name;

        $role = Role::where('name', 'owner')->get();
        $cabang = Cabang::where('deleted_at', null)->get();

        $user = User::where('slug', $request->user)->first();
        if ($user == null && $userRole != 'owner') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'owner') {
            $profile = OwnerLaundry::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.owner.ubah', compact('title', 'cabang', 'role', 'user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = User::where('slug', $request->user)->first();
        $validatorUser = Validator::make(
            $request->all(),
            [
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
            ],
            [
                'required' => ':attribute harus diisi.',
                'unique' => ':attribute sudah ada, silakan isi yang lain.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'integer' => ':attribute harus berupa angka.',
            ]
        );
        $validatedUser = $validatorUser->validated();
        $validatedUser['slug'] = str()->slug($validatedUser['username']);

        $validatorProfile = Validator::make(
            $request->all(),
            [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:1|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'mulai_kerja' => 'nullable|date',
                'selesai_kerja' => 'nullable|date',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'date' => ':attribute harus berupa tanggal.',
            ]
        );

        $validatedProfile = $validatorProfile->validated();

        $userUpdate = User::where('id', $user->id)->update($validatedUser);
        $user->removeRole($user->getRoleNames()[0]);
        $user->assignRole($request->role);
        $validatedProfile['user_id'] = $user->id;

        switch ($request->role) {
            case 'owner':
                if (OwnerLaundry::where('user_id', $user->id)->first()) {
                    OwnerLaundry::where('user_id', $user->id)->delete();
                    $profileUpdate = OwnerLaundry::create($validatedProfile);
                } else {
                    $profileUpdate = OwnerLaundry::where('user_id', $user->id)->update($validatedProfile);
                }
                break;
        }

        if ($userUpdate && $profileUpdate) {
            return to_route('owner')->with('success', 'User Berhasil Diperbarui');
        } else {
            return to_route('owner')->with('error', 'User Gagal Diperbarui');
        }
    }

    public function editPassword(Request $request)
    {
        $title = "Ubah Password User";

        $user = User::where('slug', $request->user)->first();
        $userRole = auth()->user()->roles[0]->name;
        if ($user == null && $userRole != 'owner') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug) {
            return to_route('profile', $user->slug);
        }
        return view('dashboard.user.owner.ubahPassword', compact('title', 'user'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag(
            'updatePassword',
            [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ],
            [
                'required' => ':attribute harus diisi.',
                'current_password' => 'Password lama salah.',
                'confirmed' => 'Konfirmasi :attribute tidak sama.',
                'min' => 'minimal :min karakter.',
            ]
        );

        $updatePassword = User::where('slug', $request->slug)->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($updatePassword) {
            return to_route('owner')->with('success', 'Password User Berhasil Diganti');
        } else {
            return to_route('owner')->with('error', 'Password User Gagal Diganti');
        }
    }
}
