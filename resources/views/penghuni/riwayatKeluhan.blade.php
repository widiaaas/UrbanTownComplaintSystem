@extends('layouts.app')

@section('title', 'Riwayat Keluhan')

@section('content')

@php
    $user = [ 
        'name' => 'Jun',
        'role' => 'penghuni',
    ];

    $keluhan = [
        [
            'id' => 1,
            'ticket' => 'CMP-001',
            'title' => 'Perbaikan AC',
            'description' => 'Cek freon dan unit indoor.',
            'status' => 'dalam_pengerjaan',
            'priority' => 'tinggi',
            'date' => '2026-02-10',
            'chat' => [
                ['id'=>1, 'sender'=>'TR', 'message'=>'Halo, keluhan Anda sedang diproses'],
                ['id'=>2, 'sender'=>'Penghuni', 'message'=>'Terima kasih, kapan selesai?']
            ]
        ],
        [
            'id' => 2,
            'ticket' => 'CMP-002',
            'title' => 'Ganti Lampu',
            'description' => 'Penggantian lampu kamar mandi.',
            'status' => 'pending',
            'priority' => 'sedang',
            'date' => '2026-02-09',
            'chat' => [
                ['id'=>1, 'sender'=>'TR', 'message'=>'Lampu sudah diperbaiki']
            ]
        ],
    ];

    function badge($label, $type) {
        $map = [
            'pending' => 'bg-orange-100 text-orange-700',
            'dalam_pengerjaan' => 'bg-yellow-100 text-yellow-700',
            'selesai' => 'bg-green-100 text-green-700',
            'tinggi' => 'bg-red-100 text-red-700',
            'sedang' => 'bg-orange-100 text-orange-700',
            'rendah' => 'bg-gray-100 text-gray-700',
        ];
        $class = $map[$type] ?? 'bg-gray-100 text-gray-700';
        return "<span class='px-2 py-1 rounded-full text-xs font-medium $class'>$label</span>";
    }
@endphp

<div x-data="keluhanApp()" x-init="init()" class="space-y-6 p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Keluhan</h1>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Keluhan / Ticket</label>
            <input type="text" placeholder="Contoh: AC Bocor atau CMP-001"
                   class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                   x-model="search">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                    x-model="filterStatus">
                <option value="">Semua</option>
                <option value="pending">Pending</option>
                <option value="dalam_pengerjaan">Dalam Pengerjaan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="applyFilter">Filter</button>
            <button class="px-4 py-2 border rounded-lg hover:bg-gray-100" @click="resetFilter">Reset</button>
        </div>
    </div>

    {{-- LIST KELUHAN --}}
    <div class="bg-white rounded-lg border shadow-sm divide-y">
        <template x-for="kel in filteredKeluhan" :key="kel.id">
            <div class="p-6 hover:bg-gray-50 transition flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-gray-800" x-text="kel.ticket"></span>
                        <span x-html="badgeHtml(kel.status, kel.status)"></span>
                        <span x-html="badgeHtml(kel.priority, kel.priority)"></span>
                    </div>
                    <p class="font-medium" x-text="kel.title"></p>
                    <p class="text-sm text-gray-600" x-text="kel.description"></p>
                    <p class="text-xs text-gray-500 mt-1" x-text="'Ticket ' + kel.ticket + ' • ' + kel.date"></p>
                </div>
                <div class="flex-shrink-0">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            @click="openChat(kel)">Lihat Detail & Chat</button>
                </div>
            </div>
        </template>

        <template x-if="filteredKeluhan.length === 0">
            <div class="p-6 text-center text-gray-500">Tidak ada keluhan</div>
        </template>
    </div>

    {{-- MODAL DETAIL & CHAT --}}
    <div x-show="openChatModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="closeChat()" class="bg-white w-full max-w-lg rounded-lg p-6 flex flex-col space-y-4 shadow-lg">
            
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold" x-text="selectedKeluhan.title"></h2>
                <button class="text-gray-500 hover:text-gray-700" @click="closeChat()">✕</button>
            </div>

            <div class="space-y-2 border-b pb-4">
                <p><span class="font-semibold">Ticket:</span> <span x-text="selectedKeluhan.ticket"></span></p>
                <p><span class="font-semibold">Status:</span> <span x-html="badgeHtml(selectedKeluhan.status, selectedKeluhan.status)"></span></p>
                <p><span class="font-semibold">Prioritas:</span> <span x-html="badgeHtml(selectedKeluhan.priority, selectedKeluhan.priority)"></span></p>
                <p><span class="font-semibold">Tanggal:</span> <span x-text="selectedKeluhan.date"></span></p>
                <p><span class="font-semibold">Deskripsi:</span> <span x-text="selectedKeluhan.description"></span></p>
            </div>

            <div class="flex-1 flex flex-col overflow-y-auto max-h-72 space-y-2">
                <template x-for="msg in selectedKeluhan.chat" :key="msg.id">
                    <div :class="msg.sender === 'Penghuni' ? 'self-end bg-blue-100 text-blue-800' : 'self-start bg-gray-100 text-gray-800'"
                         class="px-3 py-1 rounded max-w-xs">
                        <span x-text="msg.sender + ': ' + msg.message"></span>
                    </div>
                </template>
            </div>

            <div class="flex gap-2 mt-2">
                <input type="text" placeholder="Tulis pesan..." class="flex-1 border rounded px-3 py-2"
                       x-model="newMessage" @keydown.enter="sendMessage()">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" @click="sendMessage()">Kirim</button>
            </div>

        </div>
    </div>

</div>

<script>
function keluhanApp() {
    return {
        search: '',
        filterStatus: '',
        openChatModal: false,
        newMessage: '',
        selectedKeluhan: null,
        keluhan: @json($keluhan),
        filteredKeluhan: [],
        init() { this.filteredKeluhan = this.keluhan; },
        applyFilter() {
            this.filteredKeluhan = this.keluhan.filter(k =>
                (this.search === '' || k.ticket.toLowerCase().includes(this.search.toLowerCase()) || k.title.toLowerCase().includes(this.search.toLowerCase())) &&
                (this.filterStatus === '' || k.status === this.filterStatus)
            );
        },
        resetFilter() {
            this.search = '';
            this.filterStatus = '';
            this.filteredKeluhan = this.keluhan;
        },
        openChat(kel) {
            this.selectedKeluhan = kel;
            this.openChatModal = true;
            this.$nextTick(() => { 
                const container = document.querySelector('.max-h-72');
                if(container) container.scrollTop = container.scrollHeight;
            });
        },
        closeChat() {
            this.openChatModal = false;
            this.newMessage = '';
        },
        sendMessage() {
            if(this.newMessage.trim() === '') return;
            this.selectedKeluhan.chat.push({id: Date.now(), sender:'Penghuni', message:this.newMessage});
            this.newMessage = '';
            this.$nextTick(() => { 
                const container = document.querySelector('.max-h-72');
                if(container) container.scrollTop = container.scrollHeight;
            });
        },
        badgeHtml(label, type) {
            const map = {
                'pending':'bg-orange-100 text-orange-800',
                'dalam_pengerjaan':'bg-yellow-100 text-yellow-800',
                'selesai':'bg-green-100 text-green-800',
                'tinggi':'bg-red-100 text-red-800',
                'sedang':'bg-orange-100 text-orange-800',
                'rendah':'bg-gray-100 text-gray-800',
            };
            const cls = map[type] || 'bg-gray-100 text-gray-800';
            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${cls}">${label}</span>`;
        }
    }
}
</script>

@endsection
