<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use Illuminate\Http\Request;

class CommunityJoinController extends Controller
{
    // Menampilkan daftar komunitas
    public function index(){
    $communities = Community::with('leader')
        ->withCount('members')
        ->latest()
        ->get();

    // Periksa apakah pengguna sudah memiliki keanggotaan aktif
    // atau pengajuan yang masih menunggu persetujuan.
    $userMembership = CommunityMember::with('community')
        ->where('user_id', auth()->id())
        ->whereIn('status', ['active', 'pending'])
        ->first();

    return view('communities.index', compact(
        'communities',
        'userMembership'
        ));
    }

    // Mengajukan permintaan bergabung
    public function store(Request $request, Community $community)
    {
        $user = $request->user();

        // Periksa apakah pengguna sudah memiliki
        // keanggotaan aktif atau pengajuan pending
        $hasMembership = CommunityMember::where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->exists();

        if ($hasMembership) {
            return back()->with(
                'error',
                'Kamu sudah memiliki keanggotaan aktif atau pengajuan yang sedang diproses.'
            );
        }

        // Simpan pengajuan
        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'joined_at' => null,
        ]);

        return back()->with(
            'success',
            'Pengajuan berhasil dikirim. Menunggu persetujuan Ketua.'
        );
    }
}