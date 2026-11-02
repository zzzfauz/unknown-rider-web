<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use Illuminate\Support\Facades\DB;

class CommunityRequestController extends Controller
{
    // Menampilkan permintaan bergabung
    public function index(Community $community)
    {
        // Hanya Ketua komunitas yang bersangkutan
        abort_unless(
            $community->leader_id === auth()->id(),
            403
        );

        $requests = $community->members()
            ->with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view(
            'ketua.communities.requests.index',
            compact('community', 'requests')
        );
    }

    // Menyetujui permintaan
    public function approve(
        Community $community,
        CommunityMember $membership
    ) {
        abort_unless(
            $community->leader_id === auth()->id(),
            403
        );

        abort_unless(
            $membership->community_id === $community->id &&
            $membership->status === 'pending',
            404
        );

        // Pastikan pengguna belum aktif di komunitas lain
        $alreadyJoined = CommunityMember::where(
            'user_id',
            $membership->user_id
        )
            ->where('status', 'active')
            ->exists();

        if ($alreadyJoined) {
            return back()->with(
                'error',
                'Pengguna sudah menjadi anggota komunitas lain.'
            );
        }

        DB::transaction(function () use ($membership) {
            $membership->update([
                'status' => 'active',
                'joined_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Permintaan bergabung berhasil disetujui.'
        );
    }

    // Menolak permintaan
    public function reject(
        Community $community,
        CommunityMember $membership
    ) {
        abort_unless(
            $community->leader_id === auth()->id(),
            403
        );

        abort_unless(
            $membership->community_id === $community->id &&
            $membership->status === 'pending',
            404
        );

        $membership->update([
            'status' => 'rejected',
        ]);

        return back()->with(
            'success',
            'Permintaan bergabung berhasil ditolak.'
        );
    }
}