<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommunityController extends Controller
{
    // Menampilkan daftar komunitas
    public function index()
    {
        $communities = Community::with('leader')
            ->withCount('members')
            ->latest()
            ->get();

        return view('admin.communities.index', compact('communities'));
    }

    // Menampilkan form pembuatan komunitas
    public function create()
    {
        $users = User::where('role', 'member')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.communities.create', compact('users'));
    }

    // Menyimpan komunitas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'string', 'max:255'],
            'leader_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'member'),
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $community = Community::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'logo' => $validated['logo'] ?? null,
                'leader_id' => $validated['leader_id'],
            ]);

            // Mengubah role pengguna menjadi Ketua
            User::where('id', $validated['leader_id'])
                ->update(['role' => 'ketua']);

            // Mendaftarkan Ketua sebagai anggota aktif
            CommunityMember::updateOrCreate(
                [
                    'community_id' => $community->id,
                    'user_id' => $validated['leader_id'],
                ],
                [
                    'status' => 'active',
                    'joined_at' => now(),
                ]
            );
        });

        return redirect()
            ->route('admin.communities.index')
            ->with('success', 'Komunitas berhasil dibuat dan Ketua berhasil ditunjuk.');
    }

    // Mengganti Ketua komunitas (khusus Admin)
    public function updateLeader(Request $request, Community $community)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('community_members', 'user_id')
                    ->where('community_id', $community->id)
                    ->where('status', 'active'),
            ],
        ]);

        $newLeader = User::findOrFail($validated['user_id']);

        // Calon Ketua harus memiliki role member atau ketua
        if (!in_array($newLeader->role, ['member', 'ketua'], true)) {
            return back()->with(
                'error',
                'Pengguna tersebut tidak dapat ditunjuk sebagai Ketua.'
            );
        }

        // Periksa apakah calon sudah menjadi Ketua
        if ($community->leader_id == $newLeader->id) {
            return back()->with(
                'error',
                'Pengguna tersebut sudah menjadi Ketua komunitas.'
            );
        }

        DB::transaction(function () use ($community, $newLeader) {

            $oldLeader = User::find($community->leader_id);

            // Perbarui Ketua komunitas
            $community->update([
                'leader_id' => $newLeader->id,
            ]);

            // Kembalikan role Ketua lama jika tidak memimpin komunitas lain
            if (
                $oldLeader &&
                $oldLeader->role === 'ketua' &&
                !Community::where('leader_id', $oldLeader->id)
                    ->where('id', '!=', $community->id)
                    ->exists()
            ) {
                $oldLeader->update([
                    'role' => 'member',
                ]);
            }

            // Tetapkan role Ketua baru
            $newLeader->update([
                'role' => 'ketua',
            ]);
        });

        return redirect()
            ->route('admin.communities.members.index', $community->id)
            ->with('success', 'Ketua komunitas berhasil diganti.');
    }
}