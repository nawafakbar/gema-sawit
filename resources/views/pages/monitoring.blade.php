@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Monitoring IoT</h1>
        <p class="text-gray-500 text-sm">Realtime sensor suara & lingkungan.</p>
    </div>
    
    <div id="toastArea" class="fixed top-20 right-5 z-[9999] flex flex-col gap-2"></div>
</div>

<div id="iot-dashboard" class="space-y-6">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:flex-row gap-4 justify-between items-center">
        <div class="w-full lg:w-1/3 relative">
            <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
            <input id="searchBox" type="text" placeholder="Cari nama sensor / lokasi..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition text-sm">
        </div>
        
        <div class="flex flex-wrap gap-2 w-full lg:w-auto justify-end">
            <select id="sortBy" class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500">
                <option value="time_desc">Terbaru</option>
                <option value="db_desc">Terbising</option>
                <option value="batt_asc">Baterai Lemah</option>
            </select>

            <button onclick="toggleModal('metaModal')" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold transition flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple"></i> <span class="hidden sm:inline">Metadata</span>
            </button>
            <button onclick="toggleModal('settingsModal')" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold transition flex items-center gap-2">
                <i class="ph-bold ph-gear"></i> <span class="hidden sm:inline">Setting</span>
            </button>
            <button onclick="refreshAll(true)" class="px-4 py-2.5 bg-brand-50 text-brand-600 border border-brand-200 rounded-xl text-sm font-bold transition flex items-center gap-2 hover:bg-brand-100 active:scale-95">
                <i class="ph-bold ph-arrows-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-5 rounded-2xl text-white shadow-lg shadow-blue-200 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs opacity-80 uppercase font-bold tracking-wider">Total Sensor</p>
                <h3 class="text-3xl font-bold mt-1" id="statTotal">0</h3>
            </div>
            <i class="ph-fill ph-microchip absolute -right-2 -bottom-2 text-6xl opacity-20 group-hover:scale-110 transition"></i>
        </div>
        
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 rounded-2xl text-white shadow-lg shadow-emerald-200 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs opacity-80 uppercase font-bold tracking-wider">Online</p>
                <h3 class="text-3xl font-bold mt-1" id="statOnline">5</h3>
                <p class="text-[10px] opacity-70 mt-1">≤ <span id="statOnlineWindowDisplay">2</span> menit terakhir</p>
            </div>
            <i class="ph-fill ph-wifi-high absolute -right-2 -bottom-2 text-6xl opacity-20 group-hover:scale-110 transition"></i>
        </div>

        <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-5 rounded-2xl text-white shadow-lg shadow-orange-200 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs opacity-80 uppercase font-bold tracking-wider">Low Battery</p>
                <h3 class="text-3xl font-bold mt-1" id="statLow">0</h3>
                <p class="text-[10px] opacity-70 mt-1">Di bawah <span id="statBattLowDisplay">40</span>%</p>
            </div>
            <i class="ph-fill ph-battery-warning absolute -right-2 -bottom-2 text-6xl opacity-20 group-hover:scale-110 transition"></i>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-pink-600 p-5 rounded-2xl text-white shadow-lg shadow-red-200 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs opacity-80 uppercase font-bold tracking-wider">Rata-Rata dB</p>
                <h3 class="text-3xl font-bold mt-1"><span id="statAvg">0</span> <span class="text-lg font-normal">dB</span></h3>
            </div>
            <i class="ph-fill ph-speaker-high absolute -right-2 -bottom-2 text-6xl opacity-20 group-hover:scale-110 transition"></i>
        </div>
    </div>

    <div id="gridSensors" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-10 text-gray-400">
            <i class="ph-duotone ph-spinner animate-spin text-3xl"></i>
            <p class="mt-2 text-sm">Memuat data sensor...</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="ph-fill ph-globe-hemisphere-west text-brand-600"></i> Peta Sebaran Sensor
            </h3>
            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">Satellite View</span>
        </div>
        <div id="map" class="h-[500px] w-full z-0"></div>
    </div>

</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('detailModal')"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-100">
                
                <div class="bg-brand-600 px-4 py-4 sm:px-6 flex justify-between items-center text-white">
                    <div>
                        <h3 class="text-lg font-bold leading-6" id="detailTitle">Sensor Detail</h3>
                        <p class="text-xs text-brand-100 mt-1" id="detailSub">Location info</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="confirmClearData(currentSensorId)" class="p-2 bg-red-500 hover:bg-red-600 rounded-lg text-white transition shadow-sm" title="Hapus Semua Data">
                            <i class="ph-bold ph-trash"></i>
                        </button>

                        <button onclick="toggleModal('detailModal'); openMetaModal(currentSensorId)" class="p-2 bg-white/20 hover:bg-white/30 rounded-lg text-white transition">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </button>

                        <button onclick="toggleModal('detailModal')" class="p-2 hover:bg-white/20 rounded-lg text-white transition">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>
                </div>

                <div class="px-4 py-5 sm:p-6 bg-gray-50 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="ph-fill ph-wifi-high text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Status</p>
                                <span id="detailStatus" class="font-bold text-sm">LOADING...</span>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                                <i class="ph-fill ph-speaker-high text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500">Desibel</span>
                                    <span id="detailDB" class="font-bold">--</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="barDB" class="bg-orange-500 h-1.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                <i class="ph-fill ph-battery-high text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500">Baterai</span>
                                    <span id="detailBatt" class="font-bold">--</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="barBatt" class="bg-green-500 h-1.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <h5 class="text-sm font-bold text-gray-800 mb-3">Riwayat Kebisingan (dB)</h5>
                            <canvas id="chartDB" height="180"></canvas>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <h5 class="text-sm font-bold text-gray-800 mb-3">Riwayat Baterai (%)</h5>
                            <canvas id="chartBatt" height="180"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h5 class="text-sm font-bold text-gray-800">50 Data Terakhir</h5>
                            <button onclick="exportHistoryCSV()" class="text-xs text-brand-600 hover:text-brand-700 font-bold flex items-center gap-1">
                                <i class="ph-bold ph-download-simple"></i> CSV
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Waktu</th>
                                        <th class="px-4 py-3">dB</th>
                                        <th class="px-4 py-3">Baterai</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="settingsModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="toggleModal('settingsModal')"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Pengaturan Dashboard</h3>
                <button onclick="toggleModal('settingsModal')" class="text-gray-400 hover:text-red-500"><i class="ph-bold ph-x text-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ambang Batas</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600">Bising (dB)</label>
                            <input type="number" id="thDbLoud" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Low Batt (%)</label>
                            <input type="number" id="thBattLow" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs text-gray-600">Batas Waktu Online (Menit)</label>
                            <input type="number" id="thOnlineMin" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Notifikasi Suara</label>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="alertEnable" class="rounded text-brand-600 focus:ring-brand-500">
                            <span class="text-sm text-gray-700">Aktifkan</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-2">
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" id="alertOnLoud"> Saat Bising</label>
                            <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" id="alertOnLowBatt"> Saat Low Batt</label>
                        </div>
                        <button onclick="testAlert()" class="w-full mt-2 py-1.5 bg-white border border-gray-200 rounded text-xs font-bold text-gray-600 hover:bg-gray-100">
                            <i class="fa fa-volume-high"></i> Tes Suara
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <button onclick="saveSettings()" class="px-6 py-2.5 bg-brand-600 text-white font-bold rounded-xl shadow-lg hover:bg-brand-700 transition">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="metaModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="toggleModal('metaModal')"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Metadata Sensor</h3>
                <button onclick="toggleModal('metaModal')" class="text-gray-400 hover:text-red-500"><i class="ph-bold ph-x text-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">ID Sensor</label>
                    <select id="metaSensorId" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none"></select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama</label>
                        <input type="text" id="metaName" placeholder="Blok A" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lokasi</label>
                        <input type="text" id="metaLocation" placeholder="Kebun Utara" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                        <input type="number" id="metaLat" step="any" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                        <input type="number" id="metaLng" step="any" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="pickFromMap()" class="flex-1 py-2 bg-brand-50 text-brand-600 border border-brand-200 rounded-lg text-xs font-bold hover:bg-brand-100">
                        <i class="fa fa-map-pin"></i> Ambil dari Peta
                    </button>
                    <button onclick="deleteMeta()" class="py-2 px-3 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-100">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <button onclick="saveMeta()" class="px-6 py-2.5 bg-brand-600 text-white font-bold rounded-xl shadow-lg hover:bg-brand-700 transition">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- 1. CONFIG & STATE ---
    const PREFS_KEYS = { TH: 'sp_thresholds', DARK: 'sp_dark', META: 'sp_sensors_meta' };
    const DEFAULTS = { dbLoud: 70, battLow: 40, onlineMin: 2, alert: { enable: false, onLoud: true, onLowBatt: true, volume: 0.25 } };
    
    let thresholds = JSON.parse(localStorage.getItem(PREFS_KEYS.TH)) || DEFAULTS;
    let ONLINE_WINDOW_MS = thresholds.onlineMin * 60 * 1000;
    
    let map, dbChart, battChart;
    let summary = [], currentSensorId = null;
    let pickMarker = null, pendingLatLng = null;
    let audioCtx = null, lastAlertTimes = {};

    // --- 2. HELPER FUNCTIONS ---
    // Helper Modal Tanpa Bootstrap
    window.toggleModal = function(id) {
        const el = document.getElementById(id);
        if(el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }

    // Helper Toast Tailwind
    function showToast(msg, type = 'info') {
        const colors = {
            info: 'bg-gray-800 text-white',
            success: 'bg-green-600 text-white',
            danger: 'bg-red-600 text-white',
            warning: 'bg-orange-500 text-white'
        };
        const toast = document.createElement('div');
        toast.className = `${colors[type]} px-4 py-3 rounded-xl shadow-lg text-sm font-medium animate-bounce transition-opacity duration-300 flex items-center gap-2`;
        toast.innerHTML = `<span>${msg}</span>`;
        
        document.getElementById('toastArea').appendChild(toast);
        setTimeout(() => { 
            toast.style.opacity = '0'; 
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    async function jget(url) {
        const r = await fetch(url);
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    }

    // --- TAMBAHAN: FUNGSI HAPUS DATA ---
    async function confirmClearData(id) {
        // 1. Konfirmasi Ganda agar tidak salah pencet
        const confirmMsg = `⚠️ BAHAYA: Anda yakin ingin menghapus SEMUA RIWAYAT data untuk sensor: ${id}?\n\nData yang sudah dihapus TIDAK BISA dikembalikan. Database akan dimulai dari nol.`;
        
        if(!confirm(confirmMsg)) return;

        // 2. Tampilkan loading toast
        showToast('Sedang menghapus data...', 'info');

        try {
            // 3. Panggil API Laravel
            const response = await fetch('/api/sensors/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Jika pakai CSRF Token Laravel di web routes (opsional untuk API tapi bagus ada)
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ sensor_id: id })
            });

            const result = await response.json();

            if (result.status === 'success') {
                showToast('✅ Database sensor berhasil di-refresh!', 'success');
                toggleModal('detailModal'); // Tutup modal detail
                refreshAll(true); // Refresh dashboard biar angkanya jadi 0
            } else {
                showToast('❌ Gagal: ' + result.message, 'danger');
            }

        } catch (error) {
            console.error(error);
            showToast('❌ Terjadi kesalahan koneksi', 'danger');
        }
    }

    // --- 3. METADATA LOGIC ---
    function getMetas() { return JSON.parse(localStorage.getItem(PREFS_KEYS.META)) || []; }
    function findMeta(id) { return getMetas().find(m => m.sensor_id === id); }
    function upsertMeta(meta) {
        const all = getMetas();
        const i = all.findIndex(m => m.sensor_id === meta.sensor_id);
        if(i >= 0) all[i] = meta; else all.push(meta);
        localStorage.setItem(PREFS_KEYS.META, JSON.stringify(all));
    }
    function removeMeta(id) {
        const all = getMetas().filter(m => m.sensor_id !== id);
        localStorage.setItem(PREFS_KEYS.META, JSON.stringify(all));
    }

    // --- 4. CORE LOGIC (REFRESH, STATS, GRID) ---
    async function refreshAll(manual = false) {
        try {
            const res = await jget('/api/sensors/summary');
            summary = (res.status === 'success') ? res.nodes : [];
            
            checkAlerts(summary);
            renderStats();
            renderGrid();
            renderMap();
            
            if(manual) showToast('Data diperbarui', 'success');
        } catch(e) {
            console.error(e);
            if(manual) showToast('Gagal memuat data', 'danger');
        }
    }

    function renderStats() {
        const now = Date.now();
        const onlineCount = summary.filter(n => n.created_at && (new Date(n.created_at).getTime() > now - ONLINE_WINDOW_MS)).length;
        const lowBattCount = summary.filter(n => (n.presentase_baterai ?? 100) < thresholds.battLow).length;
        const dbVals = summary.map(n => Number(n.desibel)).filter(v => !isNaN(v));
        const avgDb = dbVals.length ? Math.round(dbVals.reduce((a,b)=>a+b,0) / dbVals.length) : 0;

        document.getElementById('statTotal').innerText = summary.length;
        document.getElementById('statOnline').innerText = onlineCount;
        document.getElementById('statLow').innerText = lowBattCount;
        document.getElementById('statAvg').innerText = avgDb;
        
        document.getElementById('statOnlineWindowDisplay').innerText = thresholds.onlineMin;
        document.getElementById('statBattLowDisplay').innerText = thresholds.battLow;
    }

    function renderGrid() {
        const wrap = document.getElementById('gridSensors');
        wrap.innerHTML = '';
        
        const q = document.getElementById('searchBox').value.toLowerCase();
        const sort = document.getElementById('sortBy').value;

        let list = summary.filter(n => {
            const m = findMeta(n.node_id) || {};
            const text = (n.node_id + (m.sensor_name||'') + (m.location||'')).toLowerCase();
            return text.includes(q);
        });

        // Sorting
        if(sort === 'db_desc') list.sort((a,b) => (b.desibel||0) - (a.desibel||0));
        else if(sort === 'batt_asc') list.sort((a,b) => (a.presentase_baterai||100) - (b.presentase_baterai||100));
        else list.sort((a,b) => new Date(b.created_at||0) - new Date(a.created_at||0));

        if(list.length === 0) {
            wrap.innerHTML = `<div class="col-span-full text-center text-gray-400 py-10">Tidak ada sensor ditemukan</div>`;
            return;
        }

        list.forEach(n => {
            const m = findMeta(n.node_id) || { sensor_name: n.node_id, location: 'Lokasi belum diatur' };
            const isOnline = n.created_at && (new Date(n.created_at).getTime() > Date.now() - ONLINE_WINDOW_MS);
            
            // Logic Warna Card (Mirip GemaSawit tapi Tailwind style)
            const db = Number(n.desibel);
            let borderClass = 'border-gray-100';
            let bgIndicator = 'bg-gray-400';
            
            if(db >= thresholds.dbLoud) { borderClass = 'border-red-200 bg-red-50/50'; bgIndicator = 'bg-red-500'; }
            else if(db >= 55) { borderClass = 'border-orange-200 bg-orange-50/50'; bgIndicator = 'bg-orange-400'; }
            else { borderClass = 'border-green-200 bg-green-50/50'; bgIndicator = 'bg-green-500'; }

            const card = document.createElement('div');
            card.className = `bg-white p-5 rounded-2xl border ${borderClass} shadow-sm hover:shadow-md transition cursor-pointer relative overflow-hidden group`;
            card.onclick = () => openDetail(n.node_id);
            
            card.innerHTML = `
                <div class="absolute top-0 left-0 w-1.5 h-full ${bgIndicator}"></div>
                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h5 class="font-bold text-gray-900 truncate pr-2">${m.sensor_name}</h5>
                        <p class="text-xs text-gray-500 truncate">${m.location}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide ${isOnline?'bg-green-100 text-green-700':'bg-green-100 text-green-700'}">
                        ${isOnline?'ONLINE':'ONLINE'}
                    </span>
                </div>
                
                <div class="flex justify-between items-end pl-2">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Kebisingan</p>
                        <p class="text-2xl font-bold text-gray-800">${n.desibel ?? '--'} <span class="text-sm font-normal text-gray-500">dB</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Baterai</p>
                        <div class="flex items-center gap-1 justify-end">
                            <i class="ph-fill ph-battery-high ${Number(n.presentase_baterai)<thresholds.battLow ? 'text-red-500 animate-pulse':'text-green-500'}"></i>
                            <span class="font-bold text-gray-700">${n.presentase_baterai ?? '--'}%</span>
                        </div>
                    </div>
                </div>
                
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                   <button onclick="event.stopPropagation(); centerOnSensor('${n.node_id}')" class="p-2 bg-white text-brand-600 rounded-full shadow-sm hover:scale-110">
                      <i class="fa fa-location-dot"></i>
                   </button>
                </div>
            `;
            wrap.appendChild(card);
        });
    }

    // --- 5. MAP LOGIC ---
    function renderMap() {
        if(!map) {
            map = L.map('map').setView([-0.9492, 100.3543], 13); // Default Padang
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Esri Satellite'
            }).addTo(map);
            
            // Map click listener for picker
            map.on('click', (e) => {
                if(document.getElementById('metaModal').classList.contains('hidden') === false) {
                   // Jika modal meta sedang terbuka (lagi pick loc)
                   document.getElementById('metaLat').value = e.latlng.lat.toFixed(6);
                   document.getElementById('metaLng').value = e.latlng.lng.toFixed(6);
                   
                   if(pickMarker) map.removeLayer(pickMarker);
                   pickMarker = L.marker(e.latlng).addTo(map);
                }
            });
        }

        // Clear existing markers
        map.eachLayer(layer => {
            if(layer instanceof L.Marker && layer !== pickMarker) map.removeLayer(layer);
        });

        const metas = getMetas();
        metas.forEach(m => {
            if(m.lat && m.lng) {
                const node = summary.find(s => s.node_id === m.sensor_id);
                const desc = node ? `<b>${node.desibel} dB</b> | Bat: ${node.presentase_baterai}%` : 'No Data';
                L.marker([m.lat, m.lng])
                 .addTo(map)
                 .bindPopup(`<b>${m.sensor_name}</b><br>${m.location}<br><br>${desc}`);
            }
        });
    }
    
    function centerOnSensor(id) {
        const m = findMeta(id);
        if(m && m.lat) {
            map.setView([m.lat, m.lng], 18);
            document.getElementById('map').scrollIntoView({behavior: 'smooth'});
            showToast('Lokasi ditemukan', 'success');
        } else {
            showToast('Lokasi sensor belum diatur di metadata', 'warning');
            openMetaModal(id);
        }
    }

    function pickFromMap() {
        showToast('Klik pada peta untuk mengambil koordinat', 'info');
        toggleModal('metaModal'); // Tutup sementara biar kelihatan peta
        document.getElementById('map').scrollIntoView({behavior: 'smooth'});
        
        // Hacky way: flag agar klik berikutnya membuka modal lagi
        map.once('click', (e) => {
            toggleModal('metaModal'); // Buka lagi
            document.getElementById('metaLat').value = e.latlng.lat.toFixed(6);
            document.getElementById('metaLng').value = e.latlng.lng.toFixed(6);
        });
    }

    // --- 6. DETAIL & CHARTS ---
    async function openDetail(id) {
        currentSensorId = id;
        toggleModal('detailModal');
        
        const m = findMeta(id) || { sensor_name: id, location: '-' };
        document.getElementById('detailTitle').innerText = m.sensor_name;
        document.getElementById('detailSub').innerText = m.location;
        document.getElementById('detailStatus').innerText = 'LOADING...';
        
        try {
            const res = await jget(`/api/sensors/get?sensor_id=${id}`);
            const data = res.data || [];
            const latest = data[0];
            
            // Update Mini Cards
            if(latest) {
                const isOnline = new Date(latest.timestamp).getTime() > Date.now() - ONLINE_WINDOW_MS;
                document.getElementById('detailStatus').innerText = isOnline ? 'ONLINE' : 'OFFLINE';
                document.getElementById('detailStatus').className = `font-bold text-sm ${isOnline?'text-green-600':'text-gray-500'}`;
                
                document.getElementById('detailDB').innerText = latest.decibel + ' dB';
                document.getElementById('barDB').style.width = Math.min(100, latest.decibel) + '%';
                
                document.getElementById('detailBatt').innerText = latest.battery_percent + '%';
                document.getElementById('barBatt').style.width = latest.battery_percent + '%';
            }

            // Charts
            const labels = data.slice(0, 20).reverse().map(d => new Date(d.timestamp).toLocaleTimeString());
            const dbData = data.slice(0, 20).reverse().map(d => d.decibel);
            const battData = data.slice(0, 20).reverse().map(d => d.battery_percent);

            if(dbChart) dbChart.destroy();
            dbChart = new Chart(document.getElementById('chartDB'), {
                type: 'line',
                data: { labels, datasets: [{ label: 'dB', data: dbData, borderColor: '#f97316', tension: 0.4 }] }
            });

            if(battChart) battChart.destroy();
            battChart = new Chart(document.getElementById('chartBatt'), {
                type: 'line',
                data: { labels, datasets: [{ label: '%', data: battData, borderColor: '#22c55e', tension: 0.4 }] }
            });

            // Table
            const tbody = document.getElementById('detailTableBody');
            tbody.innerHTML = '';
            data.slice(0, 50).forEach(d => {
                const tr = document.createElement('tr');
                tr.className = 'border-b hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="px-4 py-2">${new Date(d.timestamp).toLocaleString()}</td>
                    <td class="px-4 py-2 font-bold ${d.decibel>=thresholds.dbLoud?'text-red-600':''}">${d.decibel}</td>
                    <td class="px-4 py-2">${d.battery_percent}%</td>
                    <td class="px-4 py-2 text-xs">OK</td>
                `;
                tbody.appendChild(tr);
            });

        } catch(e) { console.error(e); }
    }

    // --- 7. METADATA & SETTINGS SAVING ---
    async function openMetaModal(id = null) {
        toggleModal('metaModal');
        const sel = document.getElementById('metaSensorId');
        sel.innerHTML = '<option>Loading...</option>';
        
        try {
            const res = await jget('/api/sensors/list');
            sel.innerHTML = '<option value="">-- Pilih ID Sensor --</option>';
            res.sensors.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.sensor_id;
                opt.innerText = s.sensor_id;
                if(s.sensor_id === id) opt.selected = true;
                sel.appendChild(opt);
            });
            
            if(id) {
                const m = findMeta(id);
                if(m) {
                    document.getElementById('metaName').value = m.sensor_name;
                    document.getElementById('metaLocation').value = m.location;
                    document.getElementById('metaLat').value = m.lat;
                    document.getElementById('metaLng').value = m.lng;
                }
            }
        } catch(e) { sel.innerHTML = '<option>Error DB</option>'; }
    }

    function saveMeta() {
        const id = document.getElementById('metaSensorId').value;
        if(!id) return showToast('Pilih ID Sensor', 'danger');
        
        upsertMeta({
            sensor_id: id,
            sensor_name: document.getElementById('metaName').value,
            location: document.getElementById('metaLocation').value,
            lat: document.getElementById('metaLat').value,
            lng: document.getElementById('metaLng').value
        });
        toggleModal('metaModal');
        refreshAll(false);
        showToast('Metadata tersimpan', 'success');
    }
    
    function deleteMeta() {
         const id = document.getElementById('metaSensorId').value;
         if(confirm('Hapus metadata sensor ini?')) {
             removeMeta(id);
             toggleModal('metaModal');
             refreshAll(false);
             showToast('Terhapus', 'warning');
         }
    }

    function saveSettings() {
        thresholds.dbLoud = Number(document.getElementById('thDbLoud').value);
        thresholds.battLow = Number(document.getElementById('thBattLow').value);
        thresholds.onlineMin = Number(document.getElementById('thOnlineMin').value);
        thresholds.alert.enable = document.getElementById('alertEnable').checked;
        thresholds.alert.onLoud = document.getElementById('alertOnLoud').checked;
        thresholds.alert.onLowBatt = document.getElementById('alertOnLowBatt').checked;
        
        localStorage.setItem(PREFS_KEYS.TH, JSON.stringify(thresholds));
        ONLINE_WINDOW_MS = thresholds.onlineMin * 60 * 1000;
        
        toggleModal('settingsModal');
        refreshAll(false);
        showToast('Pengaturan disimpan', 'success');
    }

    function openSettings() {
        toggleModal('settingsModal');
        document.getElementById('thDbLoud').value = thresholds.dbLoud;
        document.getElementById('thBattLow').value = thresholds.battLow;
        document.getElementById('thOnlineMin').value = thresholds.onlineMin;
        document.getElementById('alertEnable').checked = thresholds.alert.enable;
        document.getElementById('alertOnLoud').checked = thresholds.alert.onLoud;
        document.getElementById('alertOnLowBatt').checked = thresholds.alert.onLowBatt;
    }

    // --- 8. AUDIO ALERT ---
    function checkAlerts(nodes) {
        if(!thresholds.alert.enable) return;
        const now = Date.now();
        
        nodes.forEach(n => {
            const isLoud = thresholds.alert.onLoud && Number(n.desibel) >= thresholds.dbLoud;
            if(isLoud) {
                const key = n.node_id + '_loud';
                if(now - (lastAlertTimes[key]||0) > 30000) { // Cooldown 30s
                    playBeep();
                    showToast(`BISING: ${n.node_id} (${n.desibel} dB)`, 'danger');
                    lastAlertTimes[key] = now;
                }
            }
        });
    }

    function playBeep() {
        if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.frequency.value = 1000;
        osc.start();
        setTimeout(() => osc.stop(), 500);
    }
    
    function testAlert() { playBeep(); }

    // --- INIT ---
    document.addEventListener('DOMContentLoaded', () => {
        // Init listener for filters
        document.getElementById('searchBox').addEventListener('input', () => renderGrid());
        document.getElementById('sortBy').addEventListener('change', () => renderGrid());
        
        // Auto refresh
        refreshAll(false);
        setInterval(() => refreshAll(false), 30000); // 30s auto
    });
</script>
@endsection