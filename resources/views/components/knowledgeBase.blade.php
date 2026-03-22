<div class="px-6 pb-6 w-full">
    {{-- Layout Desktop: 3 kolom --}}
    <div class="hidden lg:grid lg:grid-cols-12 gap-4">

        {{-- LIST KB --}}
        <div class="col-span-3 flex flex-col">
            <div class="border rounded-xl p-3 bg-white shadow">
                <div class="sticky top-0 z-20 bg-white pb-2">
                    <input
                        type="text"
                        x-model="kbSearch"
                        placeholder="Cari judul atau kategori..."
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="overflow-y-auto space-y-2 mt-2" style="max-height: 70vh">
                    <template x-for="item in filteredKnowledgeBase" :key="item.id">
                        <div class="flex items-center justify-between gap-2">
                            <button
                                @click="selectKB(item)"
                                class="flex-1 text-left p-3 rounded-lg border transition"
                                :class="selectedKB && selectedKB.id === item.id 
                                    ? 'border-green-500 bg-green-50'
                                    : 'bg-gray-50 hover:bg-green-50'">
                                <p class="font-semibold text-sm" x-text="item.judul"></p>
                                <p class="text-xs text-gray-500" x-text="item.kategori"></p>
                            </button>
                            <div class="flex gap-1">
                                <button @click.stop="openEditKBModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button @click.stop="deleteKB(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- PENYEBAB --}}
        <div class="col-span-3 flex flex-col">
            <template x-if="selectedKB">
                <div class="border rounded-xl p-3 bg-white shadow">
                    <div class="sticky top-0 z-20 bg-white pb-2 flex justify-between items-center">
                        <input
                            type="text"
                            x-model="searchDiagnosis"
                            placeholder="Cari penyebab..."
                            class="flex-1 border rounded-lg px-3 py-2 text-sm mr-2">
                    </div>
                    <div class="overflow-y-auto space-y-2 mt-2" style="max-height: 60vh">
                        <template x-for="diag in filteredDiagnosis" :key="diag.id">
                            <div class="flex items-center justify-between gap-2">
                                <button
                                    @click="selectDiagnosis(diag)"
                                    class="flex-1 text-left p-3 rounded-lg border transition"
                                    :class="selectedDiagnosis && selectedDiagnosis.id === diag.id
                                        ? 'bg-green-100 border-green-500'
                                        : 'bg-gray-50 hover:bg-green-50'">
                                    <p class="text-sm font-medium" x-text="diag.Penyebab"></p>
                                </button>
                                <div class="flex gap-1">
                                    <button @click.stop="openEditCauseModal(diag)" class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteCause(diag.id)" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredDiagnosis.length === 0">
                            <div class="text-xs text-gray-400 italic text-center py-4">
                                Tidak ada penyebab. Klik "Tambah" untuk menambahkan.
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="!selectedKB">
                <div class="border rounded-xl p-4 bg-gray-50 shadow">
                    <div class="h-64 flex items-center justify-center text-gray-400">
                        <p class="text-center">Pilih Knowledge Base terlebih dahulu</p>
                    </div>
                </div>
            </template>
        </div>

        {{-- DETAIL --}}
        <div class="col-span-6 flex flex-col">
            <template x-if="selectedDiagnosis">
                <div class="border rounded-xl p-4 bg-green-50 shadow">
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Detail Penanganan
                        </h2>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Penyebab</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedDiagnosis.Penyebab"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Departemen Terkait</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedKB.dept || '-'"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Deskripsi</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedDiagnosis.deskripsi || '-'"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Langkah Penyelesaian</p>
                            <div class="bg-white border rounded p-3 mt-1 whitespace-pre-line" x-text="selectedDiagnosis.langkah"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Lampiran</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <template x-for="file in selectedDiagnosis.lampiran">
                                    <span class="px-3 py-1 border rounded text-xs bg-white" x-text="file"></span>
                                </template>
                                <template x-if="selectedDiagnosis.lampiran.length === 0">
                                    <span class="text-xs text-gray-400">Tidak ada lampiran</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="selectedKB && !selectedDiagnosis">
                <div class="border rounded-xl p-4 bg-green-50 shadow">
                    <div class="h-64 flex items-center justify-center text-gray-400">
                        <p class="text-center">Pilih penyebab untuk melihat detail penanganan</p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Layout Mobile: Vertikal (Judul -> Penyebab -> Detail) --}}
    <div class="lg:hidden space-y-4">
        {{-- LIST KB --}}
        <div class="bg-white rounded-xl shadow">
            <div class="p-3 border-b">
                <input
                    type="text"
                    x-model="kbSearch"
                    placeholder="Cari judul atau kategori..."
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="p-3 space-y-2 max-h-64 overflow-y-auto">
                <template x-for="item in filteredKnowledgeBase" :key="item.id">
                    <div class="flex items-center justify-between gap-2">
                        <button
                            @click="selectKB(item)"
                            class="flex-1 text-left p-3 rounded-lg border transition"
                            :class="selectedKB && selectedKB.id === item.id 
                                ? 'border-green-500 bg-green-50'
                                : 'bg-gray-50 hover:bg-green-50'">
                            <p class="font-semibold text-sm" x-text="item.judul"></p>
                            <p class="text-xs text-gray-500" x-text="item.kategori"></p>
                        </button>
                        <div class="flex gap-1">
                            <button @click.stop="openEditKBModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button @click.stop="deleteKB(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- PENYEBAB + DETAIL --}}
        <div x-show="selectedKB" x-cloak>
            <div class="space-y-4">

                {{-- Penyebab --}}
                <div class="bg-white rounded-xl shadow">
                    <div class="p-3 border-b flex justify-between items-center">
                        <input
                            type="text"
                            x-model="searchDiagnosis"
                            placeholder="Cari penyebab..."
                            class="flex-1 border rounded-lg px-3 py-2 text-sm mr-2">
                        
                    </div>
                    <div class="p-3 space-y-2 max-h-64 overflow-y-auto">
                        <template x-for="diag in filteredDiagnosis" :key="diag.id">
                            <div class="flex items-center justify-between gap-2">
                                <button
                                    @click="selectDiagnosis(diag)"
                                    class="flex-1 text-left p-3 rounded-lg border transition"
                                    :class="selectedDiagnosis && selectedDiagnosis.id === diag.id
                                        ? 'bg-green-100 border-green-500'
                                        : 'bg-gray-50 hover:bg-green-50'">
                                    <p class="text-sm font-medium" x-text="diag.Penyebab"></p>
                                </button>
                                <div class="flex gap-1">
                                    <button @click.stop="openEditCauseModal(diag)" class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteCause(diag.id)" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredDiagnosis.length === 0">
                            <div class="text-xs text-gray-400 italic text-center py-4">
                                Tidak ada penyebab. Klik "Tambah" untuk menambahkan.
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Detail --}}
                <div x-show="selectedDiagnosis" x-cloak class="bg-green-50 rounded-xl shadow p-4">
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">Detail Penanganan</h2>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Penyebab</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedDiagnosis.Penyebab"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Departemen Terkait</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedKB.dept || '-'"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Deskripsi</p>
                            <div class="bg-white border rounded p-3 mt-1" x-text="selectedDiagnosis.deskripsi || '-'"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Langkah Penyelesaian</p>
                            <div class="bg-white border rounded p-3 mt-1 whitespace-pre-line" x-text="selectedDiagnosis.langkah"></div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold">Lampiran</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <template x-for="file in selectedDiagnosis.lampiran">
                                    <span class="px-3 py-1 border rounded text-xs bg-white" x-text="file"></span>
                                </template>
                                <template x-if="selectedDiagnosis.lampiran.length === 0">
                                    <span class="text-xs text-gray-400">Tidak ada lampiran</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pesan ketika belum pilih KB --}}
        <div x-show="!selectedKB" x-cloak>
            <div class="bg-gray-50 rounded-xl shadow p-8 text-center text-gray-400">
                Pilih Knowledge Base terlebih dahulu
            </div>
        </div>

        {{-- Pesan ketika sudah pilih KB tapi belum pilih penyebab --}}
        <div x-show="selectedKB && !selectedDiagnosis" x-cloak>
            <div class="bg-green-50 rounded-xl shadow p-8 text-center text-gray-400">
                Pilih penyebab untuk melihat detail penanganan
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function kbPage() {
    return {
        searchKB: '',
        searchDiagnosis: '',
        selectedKB: null,
        selectedDiagnosis: null,

        knowledgeBase: [
        {
            id: 1,
            judul: "AC Tidak Dingin",
            kategori: "AC",
            dept: "Engineering",
            diagnosis_list: [
                { 
                    id: 1, 
                    Penyebab: "Freon habis atau tekanan tidak stabil", 
                    deskripsi: "Tekanan freon rendah menyebabkan AC tidak mendingin optimal.", 
                    langkah: "1. Cek tekanan freon menggunakan manifold gauge\n2. Isi ulang freon jika tekanan rendah\n3. Periksa kebocoran pada sambungan pipa\n4. Pastikan tidak ada kebocoran pada evaporator", 
                    lampiran: ["freon-check.pdf"] 
                },
                { 
                    id: 2, 
                    Penyebab: "Saluran drainase tersumbat", 
                    deskripsi: "Air tidak bisa mengalir keluar sehingga AC bocor atau tidak dingin.", 
                    langkah: "1. Bersihkan selang pembuangan dengan air bertekanan\n2. Pastikan selang tidak tertekuk\n3. Cek posisi kemiringan AC indoor\n4. Bersihkan bak penampung air", 
                    lampiran: [] 
                },
                { 
                    id: 3, 
                    Penyebab: "Kipas atau kompresor bermasalah", 
                    deskripsi: "Kerusakan kipas atau kompresor mengurangi sirkulasi udara dingin.", 
                    langkah: "1. Cek kipas indoor dan outdoor\n2. Bersihkan debu pada kipas\n3. Periksa kompresor apakah bekerja\n4. Hubungi teknisi jika berlanjut", 
                    lampiran: ["fan-check.png"] 
                }
            ]
        },
        {
            id: 2,
            judul: "AC Bocor Air",
            kategori: "AC",
            dept: "Engineering",
            diagnosis_list: [
                { 
                    id: 4, 
                    Penyebab: "Saluran drainase tersumbat", 
                    deskripsi: "Drainase tersumbat menyebabkan air tidak keluar dan menetes.", 
                    langkah: "1. Bersihkan selang pembuangan\n2. Pastikan tidak tertekuk\n3. Cek posisi AC\n4. Bersihkan evaporator", 
                    lampiran: [] 
                },
                { 
                    id: 5, 
                    Penyebab: "Filter AC kotor", 
                    deskripsi: "Filter kotor menghambat aliran udara dan menimbulkan kebocoran air.", 
                    langkah: "1. Lepas filter AC\n2. Bersihkan dengan air mengalir\n3. Keringkan dan pasang kembali\n4. Lakukan pembersihan rutin setiap bulan", 
                    lampiran: ["filter-cleaning-guide.pdf"] 
                }
            ]
        }
    ],
        get filteredKnowledgeBase() {
            return this.knowledgeBase.filter(kb =>
                kb.judul.toLowerCase().includes(this.searchKB.toLowerCase()) ||
                kb.kategori.toLowerCase().includes(this.searchKB.toLowerCase())
            );
        },

        get filteredDiagnosis() {
            if (!this.selectedKB) return [];
            return this.selectedKB.diagnosis_list.filter(d =>
                d.Penyebab.toLowerCase().includes(this.searchDiagnosis.toLowerCase())
            );
        },

        selectKB(item) {
            this.selectedKB = item;
            this.selectedDiagnosis = null;
            this.searchDiagnosis = '';
            
            // Di mobile, scroll ke bagian penyebab
            if (window.innerWidth < 1024) {
                setTimeout(() => {
                    const penyebabSection = document.querySelector('.lg\\:hidden .bg-white.rounded-xl.shadow:has(input[placeholder*="penyebab"])');
                    if (penyebabSection) {
                        penyebabSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start'
                        });
                    }
                }, 150);
            }
        },

        selectDiagnosis(diag) {
            this.selectedDiagnosis = diag;
            
            // Di mobile, scroll ke bagian detail
            if (window.innerWidth < 1024) {
                setTimeout(() => {
                    const detailSection = document.querySelector('.lg\\:hidden .bg-green-50.rounded-xl');
                    if (detailSection) {
                        detailSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start'
                        });
                    }
                }, 150);
            }
        }
    }
}
</script>