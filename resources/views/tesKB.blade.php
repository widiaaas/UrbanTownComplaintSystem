<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KMS Demo</title>

    <!-- Alpine -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Tailwind (optional kalau mau styling bagus) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fix x-cloak -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100">

<div x-data="kbDemo()" x-init="init()" class="p-6 space-y-4">

    <!-- BUTTON -->
    <button @click="openKB = true"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Lihat Knowledge Base
    </button>

    <button @click="openCreate = true"
        class="bg-green-600 text-white px-4 py-2 rounded">
        Simpan Knowledge
    </button>

    <!-- ================= MODAL KB ================= -->
    <div x-show="openKB" x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center">

        <div class="bg-white w-full max-w-6xl rounded p-6">

            <div class="flex justify-between mb-4">
                <h2 class="font-bold">Knowledge Base</h2>
                <button @click="openKB=false">✕</button>
            </div>

            <div class="grid grid-cols-3 gap-4">

                <!-- LEFT -->
                <div>
                    <input x-model="search"
                           @input="searchKB()"
                           placeholder="Cari (AC panas)"
                           class="w-full border px-3 py-2 mb-2">

                    <template x-if="search">
                        <p class="text-xs text-green-600 mb-2">
                            💡 Hasil berdasarkan kemiripan
                        </p>
                    </template>

                    <template x-for="kb in results" :key="kb.id">
                        <div @click="selectKB(kb)"
                             class="p-3 border mb-2 cursor-pointer"
                             :class="selectedKB?.id===kb.id?'bg-green-100':''">

                            <div class="flex justify-between">
                                <span x-text="kb.judul"></span>

                                <span class="text-xs px-2 rounded"
                                      :class="kb.score>=4?'bg-red-200':kb.score>=2?'bg-yellow-200':'bg-gray-200'"
                                      x-text="kb.score>=4?'tinggi':kb.score>=2?'sedang':'rendah'">
                                </span>
                            </div>

                            <small x-text="kb.kategori"></small>
                        </div>
                    </template>
                </div>

                <!-- MIDDLE -->
                <div>
                    <template x-if="selectedKB">
                        <div>
                            <template x-for="d in selectedKB.diagnosis" :key="d.id">
                                <div @click="selectedDiag=d"
                                     class="p-2 border mb-2 cursor-pointer"
                                     :class="selectedDiag?.id===d.id?'bg-green-100':''">
                                    <span x-text="d.penyebab"></span>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!selectedKB">
                        <p class="text-gray-400">Pilih KB</p>
                    </template>
                </div>

                <!-- RIGHT -->
                <div>
                    <template x-if="selectedDiag">
                        <div>
                            <h3 class="font-bold mb-2">Detail</h3>

                            <p><b>Penyebab:</b></p>
                            <p x-text="selectedDiag.penyebab"></p>

                            <p class="mt-2"><b>Solusi:</b></p>
                            <p x-text="selectedDiag.langkah"></p>

                            <button class="mt-3 bg-green-600 text-white px-3 py-1 rounded">
                                Gunakan Solusi
                            </button>
                        </div>
                    </template>

                    <template x-if="!selectedDiag">
                        <p class="text-gray-400">Pilih penyebab</p>
                    </template>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= MODAL CREATE ================= -->
    <div x-show="openCreate" x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center">

        <div class="bg-white w-full max-w-lg p-6 rounded">

            <div class="flex justify-between mb-3">
                <h2>Simpan Knowledge</h2>
                <button @click="openCreate=false">✕</button>
            </div>

            <input x-model="form.judul"
                   @input.debounce.500ms="checkDuplicate()"
                   placeholder="Judul"
                   class="w-full border p-2 mb-2">

            <!-- DUPLICATE -->
            <template x-if="duplicates.length">
                <div class="bg-yellow-100 p-2 text-sm mb-2">
                    ⚠️ Kemungkinan sudah ada:

                    <template x-for="d in duplicates">
                        <div class="flex justify-between">
                            <span x-text="d.judul"></span>
                            <button @click="form.judul=d.judul"
                                class="text-green-600 text-xs underline">
                                Gunakan
                            </button>
                        </div>
                    </template>
                </div>
            </template>

            <input x-model="form.kategori"
                   placeholder="Kategori"
                   class="w-full border p-2 mb-2">

            <textarea x-model="form.penyebab"
                      placeholder="Penyebab"
                      class="w-full border p-2 mb-2"></textarea>

            <textarea x-model="form.langkah"
                      placeholder="Solusi"
                      class="w-full border p-2 mb-2"></textarea>

            <button @click="save()"
                class="bg-green-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

        </div>
    </div>
</div>

<script>
function kbDemo(){
return{

openKB:false,
openCreate:false,

search:'',
results:[],
selectedKB:null,
selectedDiag:null,

form:{
    judul:'',
    kategori:'',
    penyebab:'',
    langkah:''
},

duplicates:[],

// DATA DUMMY
data:[
{
id:1,
judul:'AC tidak dingin',
kategori:'AC',
diagnosis:[
{id:1,penyebab:'Freon habis',langkah:'Isi freon'},
{id:2,penyebab:'Filter kotor',langkah:'Bersihkan filter'}
]
},
{
id:2,
judul:'AC bocor',
kategori:'AC',
diagnosis:[
{id:3,penyebab:'Drain tersumbat',langkah:'Bersihkan pipa'}
]
}
],

init(){
    this.results = this.data;
},

searchKB(){
    let k=this.search.toLowerCase();

    this.results=this.data.map(d=>{
        let score=0;

        if(d.judul.toLowerCase().includes(k)) score+=2;
        if(d.kategori.toLowerCase().includes(k)) score+=1;

        return {...d,score};

    }).filter(d=>d.score>0)
    .sort((a,b)=>b.score-a.score);
},

selectKB(kb){
    this.selectedKB=kb;
    this.selectedDiag=null;
},

checkDuplicate(){
    let j=this.form.judul.toLowerCase();

    this.duplicates=this.data.filter(d=>
        d.judul.toLowerCase().includes(j)
    );
},

save(){
    alert('Simulasi tersimpan!');
    this.openCreate=false;
}

}}
</script>

</body>
</html>