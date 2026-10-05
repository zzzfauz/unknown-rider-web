<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Touring - Rockbikers</title>
    
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
        <div class="text-2xl font-black text-orange-500 tracking-wider">🔥 ROCKBIKERS</div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-300">Halo, <span class="font-bold text-white">Rider (Dummy)</span></span>
            <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded font-bold text-sm transition">Logout</button>
        </div>
    </nav>

    <!-- MAIN DASHBOARD -->
    <div class="container mx-auto p-4 mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: Peta Safety Ride -->
        <div class="lg:col-span-2 bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold flex items-center">📍 Safety Ride - Live Tracking</h2>
                <span id="status-badge" class="bg-gray-600 text-xs px-2 py-1 rounded text-white">GPS Nonaktif</span>
            </div>
            
            <!-- Wadah Peta -->
            <div id="map" class="rounded-lg border border-gray-600 mb-5"></div>
            
            <!-- Tombol Kontrol GPS -->
            <div class="flex space-x-4">
                <button id="btn-start" class="w-full bg-green-600 hover:bg-green-700 p-3 rounded-lg font-bold transition">Mulai Pancarkan Lokasi Saya</button>
                <button id="btn-stop" class="w-full hidden bg-red-600 hover:bg-red-700 p-3 rounded-lg font-bold transition">Hentikan Lokasi</button>
            </div>
        </div>

        <!-- KOLOM KANAN: Panel Konvoi & Kas -->
        <div class="space-y-6">
            <!-- Info Konvoi -->
            <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-lg">
                <h3 class="text-lg font-bold text-orange-500 mb-4">Formasi Konvoi (Simulasi)</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center bg-gray-700 p-3 rounded-lg border-l-4 border-orange-500">
                        <div>
                            <p class="font-bold">Road Captain</p>
                            <p class="text-xs text-gray-400">Kecepatan: 45 km/j</p>
                        </div>
                        <span class="text-green-400 text-xs font-bold animate-pulse">● LIVE</span>
                    </li>
                    <li class="flex justify-between items-center bg-gray-700 p-3 rounded-lg border-l-4 border-blue-500">
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

    <!-- LOGIKA JAVASCRIPT & PETA -->
    <script>
        // 1. Inisialisasi Peta (Koordinat awal difokuskan di Bandung / Baleendah)
        const map = L.map('map').setView([-6.9950, 107.6250], 13);

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
        
        L.marker([-6.9850, 107.6200], {icon: captainIcon}).addTo(map).bindPopup('<b>Road Captain</b><br>Sedang melaju...');
        L.marker([-7.0050, 107.6300]).addTo(map).bindPopup('<b>Sweeper</b><br>Menjaga barisan belakang.');

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
                    console.log(`Mengirim ke Laravel: Lat ${lat}, Lng ${lng}`);

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
    </script>
</body>
</html>