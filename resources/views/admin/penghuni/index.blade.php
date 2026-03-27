@extends('layouts.app')

@section('title', 'Kelola Penghuni')

@section('content')

<div x-data="penghuniApp()" class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Penghuni</h1>
        <button @click="openCreate = true"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Penghuni
        </button>
    </div>
    
    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Penghuni / Unit</label>
            <input type="text" x-model="search"
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select x-model="statusFilter" class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button @click="$refs.tableFilter.submit()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
            <button @click="search=''; statusFilter=''" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Reset</button>
        </div>
    </div>
    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-center">
                    <th class="px-4 py-2">Nama Penghuni</th>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Telepon</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-center">
                @foreach($penghunis as $penghuni)
                <tr class="hover:bg-gray-50 align-middle">
                    <td class="px-4 py-2">{{ $penghuni->nama }}</td>
                    <td class="px-4 py-2">{{ $penghuni->unit->nomor ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $penghuni->email }}</td>
                    <td class="px-4 py-2">{{ $penghuni->telepon }}</td>
                    <td class="px-4 py-2">
                        @if($penghuni->status == 'Aktif')
                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-2 flex-wrap">

                        {{-- Tombol Detail --}}
                        <button class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-xs font-medium btn-detail"
                                data-tenant='@json($penghuni->load("unit"))'>
                            Detail
                        </button>

                        {{-- Tombol Edit --}}
                        <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs font-medium btn-edit"
                                data-tenant='@json($penghuni->load("unit"))'>
                            Edit
                        </button>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.penghuni.destroy', $penghuni->id) }}" method="POST"
                              onsubmit="return confirm('Hapus penghuni {{ $penghuni->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs font-medium">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH PENGHUNI ================= --}}
    <div x-show="openCreate" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="openCreate=false" class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 space-y-5">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Penghuni Baru</h2>
            <form action="{{ route('admin.penghuni.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penghuni</label>
                    <input name="nama" type="text" placeholder="Nama lengkap"
                           class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit_id"
                            class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nomor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" placeholder="email@example.com"
                           class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input name="telepon" type="text" placeholder="08xxxxxxxxxx"
                           class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button type="button" @click="openCreate=false"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-100">Batal</button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL DETAIL ================= --}}
    <div x-show="openDetail" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDetail=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Detail Penghuni (<span x-text="tenantData.nama"></span>)</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Nama:</strong> <span x-text="tenantData.nama"></span></p>
                <p><strong>Unit:</strong> <span x-text="tenantData.unit ? tenantData.unit.nomor : '-'"></span></p>
                <p><strong>Email:</strong> <span x-text="tenantData.email"></span></p>
                <p><strong>Telepon:</strong> <span x-text="tenantData.telepon"></span></p>
                <p><strong>Status:</strong> 
                    <span x-text="tenantData.status"
                          :class="tenantData.status=='Aktif' ? 'px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium' : 'px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-medium'">
                    </span>
                </p>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <button class="px-4 py-2 border rounded hover:bg-gray-100" @click="openDetail=false">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT ================= --}}
    <div x-show="openEdit" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Penghuni (<span x-text="tenantData.nama"></span>)</h2>
            <form id="form-edit" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input name="nama" type="text" class="w-full mt-1 border rounded-lg px-3 py-2" x-model="tenantData.nama">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <select name="unit_id" class="w-full mt-1 border rounded-lg px-3 py-2" x-model="tenantData.unit_id">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nomor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" class="w-full mt-1 border rounded-lg px-3 py-2" x-model="tenantData.email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input name="telepon" type="text" class="w-full mt-1 border rounded-lg px-3 py-2" x-model="tenantData.telepon">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full mt-1 border rounded-lg px-3 py-2" x-model="tenantData.status">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openEdit=false" class="px-4 py-2 border rounded hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ================= SCRIPT ================= --}}
<script>
function penghuniApp() {
    return {
        openCreate: false,
        openEdit: false,
        openDetail: false,
        tenantData: {},

        init() {
            // Tombol Edit
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', e => {
                    this.tenantData = JSON.parse(btn.dataset.tenant);
                    document.getElementById('form-edit').action = `/admin/penghuni/${this.tenantData.id}`;
                    this.openEdit = true;
                });
            });

            // Tombol Detail
            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', e => {
                    this.tenantData = JSON.parse(btn.dataset.tenant);
                    this.openDetail = true;
                });
            });
        }
    }
}
</script>

@endsection