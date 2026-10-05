<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function updateLokasi(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // Matikan aturan relasi (Foreign Key) sementara untuk testing
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::table('location_histories')->insert([
                'tour_id' => 1,       
                'user_id' => 1,       
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy' => 10,     
                'speed' => 0,         
                'heading' => 0,       
                'recorded_at' => now('Asia/Jakarta'), // Paksa pakai waktu Indonesia
                'created_at' => now('Asia/Jakarta'),
                'updated_at' => now('Asia/Jakarta'),
            ]);
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil: Titik lokasi masuk ke database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal DB: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSemuaLokasi()
    {
        // Ambil data lokasi terbaru dari database
        $lokasi = DB::table('location_histories')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $lokasi
        ]);
    }
}