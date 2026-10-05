<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unknown Riders</title>
    
    <!-- Tailwind CSS (CDN untuk development cepat) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        /* Ukuran wajib untuk peta Leaflet */
        #map { height: 450px; z-index: 1; }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">

    <!-- NAVBAR -->
    <nav class="bg-black p-4 flex justify-between items-center border-b border-orange-600 shadow-md shadow-orange-900/20">
        <div class="text-2xl font-black text-orange-500 tracking-wider">UNKNOWN RIDER</div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-300">Halo, <span class="font-bold text-white">Rider (Dummy)</span></span>
            <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded font-bold text-sm transition">Logout</button>
        </div>
    </nav>

    <!-- MAIN DASHBOARD -->
<div class="container mx-auto p-4 mt-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    <!-- KOLOM KIRI: Peta Safety Ride -->
    <div class="lg:col-span-2 bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
          📍 Safety Ride - Live Tracking
        </h2>
        <span id="status-badge" class="bg-gray-600 text-xs px-2 py-1 rounded text-white w-fit">
          GPS Nonaktif
        </span>
      </div>

      <!-- Wadah Peta -->
      <div
        id="map"
        class="rounded-lg border border-gray-600 mb-5 h-64 sm:h-80 lg:h-[520px]"
      ></div>

      <!-- Tombol Kontrol GPS -->
      <div class="flex flex-col sm:flex-row gap-3">
        <button
          id="btn-start"
          class="w-full bg-green-600 hover:bg-green-700 p-3 rounded-lg font-bold transition"
        >
          Mulai Pancarkan Lokasi Saya
        </button>

        <button
          id="btn-stop"
          class="w-full sm:w-auto hidden bg-red-600 hover:bg-red-700 p-3 rounded-lg font-bold transition"
        >
          Hentikan Lokasi
        </button>
      </div>
    </div>

    <!-- KOLOM KANAN: Panel Konvoi & Kas -->
    <div class="space-y-6">
      <!-- Info Konvoi -->
      <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg">
        <h3 class="text-lg font-bold text-orange-500 mb-4">Formasi Konvoi (Simulasi)</h3>

        <ul class="space-y-3">
          <li class="flex flex-col sm:flex-row sm:justify-between gap-2 items-start sm:items-center bg-gray-700 p-3 rounded-lg border-l-4 border-orange-500">
            <div>
              <p class="font-bold">Road Captain</p>
              <p class="text-xs text-gray-400">Kecepatan: 45 km/j</p>
            </div>
            <span class="text-green-400 text-xs font-bold animate-pulse">● LIVE</span>
          </li>

          <li class="flex flex-col sm:flex-row sm:justify-between gap-2 items-start sm:items-center bg-gray-700 p-3 rounded-lg border-l-4 border-blue-500">
            <div>
              <p class="font-bold">Sweeper</p>
              <p class="text-xs text-gray-400">Kecepatan: 40 km/j</p>
            </div>
            <span class="text-green-400 text-xs font-bold animate-pulse">● LIVE</span>
          </li>
        </ul>
      </div>

      <!-- Info Kas -->
      <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg">
        <h3 class="text-lg font-bold text-orange-500 mb-4">Kas Komunitas</h3>

        <div class="bg-gray-900 p-4 rounded text-center border border-gray-700">
          <p class="text-gray-400 text-sm">Saldo Saat Ini (Dummy)</p>
          <p class="text-3xl font-black text-green-500">Rp 1.250.000</p>
        </div>
      </div>
    </div>

  </div>
</div>


    <!-- LOGIKA JAVASCRIPT & PETA -->
    <script>
        // 1. Inisialisasi Peta (target lokasi: Telkom University Bandung)
        const map = L.map('map').setView([-6.9734, 107.6308], 15);

        // 2. Tambahkan Tile / Gambar Peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 3. Menambahkan Marker Dummy (Simulasi teman-teman yang sedang touring)
        const captainIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
        });
        
        // Rute dummy yang disesuaikan agar pas melewati jalur jalan di peta
    const ruteRoadCaptain = [
        [-6.973000, 107.630000],
        [-6.973000, 107.631000],
        [-6.973000, 107.632000],
        [-6.973000, 107.633000]
    ];

    const ruteSweeper = [
        [-6.973500, 107.633000],
        [-6.973500, 107.632000],
        [-6.973500, 107.631000],
        [-6.973500, 107.630000]
    ];

    let indexRuteRC = 0;
    let indexRuteSW = 0;

    // Buat Marker awal
    let markerCaptain = L.marker(ruteRoadCaptain[0], {icon: captainIcon}).addTo(map)
        .bindPopup('<b>Road Captain</b><br>Menyusuri Jalan...');
    
    let markerSweeper = L.marker(ruteSweeper[0]).addTo(map)
        .bindPopup('<b>Sweeper</b><br>Menyusuri Jalan...');

    // Jalankan marker bergerak menyusuri titik jalan
    setInterval(() => {
        indexRuteRC = (indexRuteRC + 1) % ruteRoadCaptain.length;
        markerCaptain.setLatLng(ruteRoadCaptain[indexRuteRC]);

        indexRuteSW = (indexRuteSW + 1) % ruteSweeper.length;
        markerSweeper.setLatLng(ruteSweeper[indexRuteSW]);
    }, 2500);

        // 4. Logika Tombol GPS (Simulasi HTML5 Geolocation)
        let watchId;
        let myMarker;
        const btnStart = document.getElementById('btn-start');
        const btnStop = document.getElementById('btn-stop');
        const statusBadge = document.getElementById('status-badge');

        btnStart.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert("Browser kamu tidak mendukung fitur GPS!");
                return;
            }

            // Ubah UI Tombol
            btnStart.classList.add('hidden');
            btnStop.classList.remove('hidden');
            statusBadge.classList.replace('bg-gray-600', 'bg-green-600');
            statusBadge.innerText = 'GPS Aktif & Mengirim';

            // Mulai Lacak GPS HP
            watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // NANTI: Di sinilah kamu menambahkan fungsi fetch() atau axios
                    // untuk mengirim (lat, lng) ke URL API buatan temanmu (contoh: /api/lokasi/update)
                    //console.log(`Mengirim ke Laravel: Lat ${lat}, Lng ${lng}`);

                    // Kirim data ke API buatanmu
fetch('/api/tracking/update', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        latitude: lat,
        longitude: lng
    })
})
.then(response => response.json())
.then(data => {
    console.log("Respon dari Backend:", data.message);
})
.catch(error => {
    console.error("Gagal mengirim ke database:", error);
});

                    // Update marker di peta lokal
                    if (!myMarker) {
                        myMarker = L.marker([lat, lng]).addTo(map).bindPopup('<b>Lokasi Kamu</b>').openPopup();
                        map.setView([lat, lng], 15); // Zoom ke lokasimu
                    } else {
                        myMarker.setLatLng([lat, lng]);
                    }
                },
                (error) => {
                    console.error("Gagal mendapatkan lokasi:", error);
                    alert("Tolong izinkan akses lokasi di browser kamu!");
                },
                { enableHighAccuracy: true, maximumAge: 0 }
            );
        });

        btnStop.addEventListener('click', () => {
            navigator.geolocation.clearWatch(watchId);
            
            // Ubah UI Tombol
            btnStop.classList.add('hidden');
            btnStart.classList.remove('hidden');
            statusBadge.classList.replace('bg-green-600', 'bg-gray-600');
            statusBadge.innerText = 'GPS Nonaktif';
            
            // Hapus marker sendiri jika ada
            if (myMarker) {
                map.removeLayer(myMarker);
                myMarker = null;
            }
        });

        // --- FITUR POLLING: Ambil data lokasi dari database setiap 3 detik ---
    let markerTeman = {}; // Untuk menyimpan marker rider lain

    setInterval(() => {
        fetch('/api/tracking/get')
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    result.data.forEach(item => {
                        const idUser = item.user_id;
                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);

                        // Jika marker user tersebut belum ada di peta, buat baru
                        if (!markerTeman[idUser]) {
                            markerTeman[idUser] = L.marker([lat, lng]).addTo(map)
                                .bindPopup(`<b>Rider ID: ${idUser}</b><br>Update Terakhir`);
                        } else {
                            // Jika sudah ada, cukup geser posisinya secara mulus
                            markerTeman[idUser].setLatLng([lat, lng]);
                        }
                    });
                }
            })
            .catch(error => console.error("Gagal mengambil data polling:", error));
    }, 3000); // Angka 3000 artinya setiap 3000 milidetik (3 detik)
    </script>
</body>
</html>