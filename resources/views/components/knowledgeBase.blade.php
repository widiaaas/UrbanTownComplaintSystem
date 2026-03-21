<div class="grid grid-cols-3 gap-4">

    {{-- LIST --}}
    <div class="col-span-1 space-y-2 max-h-[70vh] overflow-y-auto border rounded-lg p-3 bg-green-50 shadow-sm">

        <template x-for="item in filteredKnowledgeBase" :key="item.id">
            <button
                @click="selectKB(item)"
                class="w-full text-left border rounded-lg p-3 bg-white hover:bg-green-100 transition"
                :class="{
                    'border-green-500 bg-green-100': selectedKB && selectedKB.id === item.id
                }">

                <p class="font-medium text-sm" x-text="item.judul"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="item.kategori"></p>
            </button>
        </template>

        <template x-if="filteredKnowledgeBase.length === 0">
            <p class="text-sm text-gray-400 italic text-center py-4">
                Solusi tidak ditemukan
            </p>
        </template>
    </div>

    {{-- DETAIL --}}
    <div class="col-span-2 border rounded-lg p-4 space-y-3 bg-green-50 shadow-sm">

    <template x-if="selectedKB">
        <div class="space-y-3">

            <p class="font-semibold text-lg" x-text="selectedKB.judul"></p>

            <div>
                <p class="text-xs text-gray-500">Kategori</p>
                <div class="bg-white border rounded-lg p-3"
                    x-text="selectedKB.kategori"></div>
            </div>

            <div>
                <p class="text-xs text-gray-500">Departemen</p>
                <div class="bg-white border rounded-lg p-3"
                    x-text="selectedKB.dept"></div>
            </div>

            <div>
                <p class="text-xs text-gray-500">Deskripsi</p>
                <div class="bg-white border rounded-lg p-3"
                    x-text="selectedKB.deskripsi"></div>
            </div>

            <div>
                    <p class="text-xs text-gray-500">Diagnosis</p>
                    <div class="bg-white border rounded-lg p-3"
                        x-text="selectedKB.diagnosis ? selectedKB.diagnosis : '-'"></div>
                </div>

            <div>
                <p class="text-xs text-gray-500">Langkah Penyelesaian</p>
                <div class="bg-white border rounded-lg p-3 whitespace-pre-line"
                    x-text="selectedKB.langkah"></div>
            </div>

            {{-- LAMPIRAN --}}
            <div>
                <p class="text-sm font-medium mb-1">Lampiran</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="file in selectedKB.lampiran">
                        <span class="px-3 py-1 border rounded text-xs bg-white"
                            x-text="file"></span>
                    </template>

                    <template x-if="selectedKB.lampiran.length === 0">
                        <span class="text-xs text-gray-400">
                            Tidak ada lampiran
                        </span>
                    </template>
                </div>
            </div>

            {{-- RELATED KELUHAN --}}
            <div>
                <p class="text-sm font-medium mb-1">Riwayat Keluhan Terkait</p>

                <template x-if="selectedKB.relatedKeluhan.length > 0">
                    <div class="space-y-2">
                        <template x-for="rk in selectedKB.relatedKeluhan" :key="rk.tiket">
                            <div class="border rounded-lg p-3 bg-white text-xs">
                                <p><span class="font-medium">Tiket:</span> <span x-text="rk.tiket"></span></p>
                                <p><span class="font-medium">Unit:</span> <span x-text="rk.unit"></span></p>
                                <p><span class="font-medium">Status:</span> 
                                    <span 
                                        class="px-2 py-0.5 rounded text-white text-[10px]"
                                        :class="{
                                            'bg-green-500': rk.status === 'Close',
                                            'bg-yellow-500': rk.status === 'Open',
                                            'bg-red-500': rk.status === 'Reject'
                                        }"
                                        x-text="rk.status">
                                    </span>
                                </p>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="selectedKB.relatedKeluhan.length === 0">
                    <p class="text-xs text-gray-400">Tidak ada keluhan terkait</p>
                </template>
            </div>

            

        </div>
    </template>
    </div>
</div>