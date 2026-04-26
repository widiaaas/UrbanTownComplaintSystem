<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use App\Models\Diagnosis;
use App\Models\Sinonim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KnowledgeBaseController extends Controller
{   

    // Tampilkan halaman view
    public function page()
    {
        $knowledgeBase = KnowledgeBase::with('diagnosis')->get();
        
        $kategoriList = KnowledgeBase::distinct()
            ->pluck('kategori')
            ->filter()
            ->values();

        return view('tenantrelation.knowledgeBase.index', compact('knowledgeBase', 'kategoriList'));
    }
    /**
     * 🔥 GET KB
     */
    public function index()
    {
        $kb = KnowledgeBase::with('diagnosis')->get();

        return response()->json($kb);
    }

    /**
     * 🔥 STORE KB
     */
    public function store(Request $request)
    {   
        if (!Auth::check()) {
            return response()->json([
                'message' => 'User belum login'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'penyebab' => 'required|string',
            'langkah' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->variasi) {

            $variasiList = explode(',', strtolower($request->variasi));
        
            foreach ($variasiList as $v) {
        
                $v = trim($v);
        
                if (!$v) continue;
        
                \App\Models\Sinonim::firstOrCreate([
                    'kata_asli' => $v,
                    'kata_normal' => strtolower($request->judul),
                    'konteks' => $request->kategori
                ]);
            }
        }

       // 🔥 GABUNG SEMUA TEKS (judul + penyebab + variasi)
        $baseText = $request->judul . ' ' . $request->penyebab . ' ' . ($request->variasi ?? '');

        // 🔥 NORMALISASI
        $normalized = $this->normalizeText($baseText, $request->kategori);

        // 🔥 KEYWORD
        $keywords = $this->extractKeywords($normalized);

        // ================= SIMPAN KB =================
        $kb = KnowledgeBase::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'departemen_terkait' => $request->dept ?? 'Engineering',
            'keywords' => implode(',', $keywords),
            'variasi' => $request->variasi,
            'keluhan_id' => null,
            'created_by' => Auth::id(),
            'status' => 'approved'
        ]);

        // ================= SIMPAN DIAGNOSIS =================
        Diagnosis::create([
            'knowledge_base_id' => $kb->id,
            'penyebab' => $request->penyebab,
            'deskripsi' => $request->deskripsi ?? $request->penyebab,
            'langkah_penyelesaian' => $request->langkah,
        ]);

        return response()->json([
            'message' => 'Knowledge berhasil disimpan',
            'data' => $kb->load('diagnosis')
        ]);
    }

    /**
     * 🔥 NORMALISASI TEKS DENGAN SINONIM
     */
    private function normalizeText($text, $kategori = null)
    {
        $text = strtolower($text);

        $sinonims = Sinonim::where(function ($q) use ($kategori) {
            $q->whereNull('konteks');

            if ($kategori) {
                $q->orWhere('konteks', $kategori);
            }
        })->get();

        foreach ($sinonims as $s) {
            $text = str_replace(
                strtolower($s->kata_asli),
                strtolower($s->kata_normal),
                $text
            );
        }

        return $text;
    }

    /**
     * 🔥 EXTRACT KEYWORDS
     */
    private function extractKeywords($text)
    {
        // hapus simbol
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);

        // pecah kata
        $words = explode(' ', $text);

        // stopword sederhana
        $stopwords = ['dan','di','ke','yang','untuk','dengan'];

        return array_values(array_filter(
            array_diff($words, $stopwords)
        ));
    }

    public function search(Request $request)
    {
        $query = $request->q;

        if (!$query) {
            return response()->json([]);
        }

        // 🔥 1. NORMALISASI
        $normalized = $this->normalizeText($query, $request->kategori);

        // 🔥 2. EXTRACT KEYWORD
        $keywords = $this->extractKeywords($normalized);

        // 🔥 3. AMBIL SEMUA KB
        if ($request->kategori) {
            $kbList = KnowledgeBase::where('kategori', $request->kategori)
                ->with('diagnosis')
                ->get();
        } else {
            $kbList = KnowledgeBase::with('diagnosis')->get();
        }

        // 🔥 4. MATCHING + SCORING
        $results = $kbList->map(function ($kb) use ($keywords) {

            $score = 0;

            $judul = strtolower($kb->judul);
            $kbKeywords = explode(',', strtolower($kb->keywords));

            foreach ($keywords as $word) {

                // match judul
                if (str_contains($judul, $word)) {
                    $score += 2;
                }

                // match keyword
                if (in_array($word, $kbKeywords)) {
                    $score += 1;
                }

                foreach ($kb->diagnosis as $diag) {
                    $penyebabText = $this->normalizeText($diag->penyebab);
                    if (str_contains($penyebabText, $word)) {
                        $score += 2;
                    }
                }
            }

            return [
                'id' => $kb->id,
                'judul' => $kb->judul,
                'kategori' => $kb->kategori,
                'score' => $score,
                'diagnosis' => $kb->diagnosis
            ];
        });

        // 🔥 5. FILTER + SORT
        $results = $results
            ->filter(fn($r) => $r['score'] > 0)
            ->sortByDesc('score')
            ->values();

        return response()->json($results);
    }
}