<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Pengguna;
use App\Models\Unit;
use App\Models\RiwayatHunian;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UnitController extends Controller
{   

    // GENERATE PASSWORD
    private function generatePassword()
    {
        $chars =
            'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $random = '';

        for ($i = 0; $i < 6; $i++) {
            $random .=$chars[rand(0, strlen($chars) - 1)];
        }
        return 'APT-' . $random;
    }
    

    // INDEX
    public function index(Request $request)
    {
        $query = Unit::with([
            'penghuniAktif.penghuni',
            'pengguna'
        ]);

        // SEARCH
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where(
                    'nomor_unit',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'gedung',
                    'LIKE',
                    "%{$search}%"
                );
            });
        }

        // ================= FILTER STATUS =================
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        // FILTER LANTAI 
        if ($request->filled('lantai')) {
            $query->where(
                'lantai',
                $request->lantai
            );
        }
        $units = $query
            ->latest()
            ->get();

        // AJAX
        if ($request->ajax()) {
            return response()->json($units);
        }
        return view(
            'admin.units.index',
            compact('units')
        );
    }

    
    // GET AVAILABLE PENGHUNI 
    public function getAvailablePenghuni()
    {
        return Penghuni::whereDoesntHave(
            'riwayatHunian',
            function ($q) {
                $q->where('status', 'Aktif');
            }
        )
        ->select(
            'id',
            'nama',
            'no_telepon',
            'email'
        )
        ->get();
    }


    // STORE
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomor_unit' => ['required','string','max:15',
                    Rule::unique(
                        'units',
                        'nomor_unit'
                    )->whereNull('deleted_at')],
                'gedung' => ['required','string','regex:/^Tower\s[A-Z]$/'],
                'lantai' => [ 'required','string','regex:/^[0-9]+$/','max:2'],
                'nomor_kamar' => ['required', 'string','regex:/^[0-9]+$/','max:2'],

            ], [

                'nomor_unit.unique' => 'Nomor unit sudah terdaftar.',
                'gedung.regex' =>'Format gedung harus seperti Tower A.',
                'lantai.min' =>'Lantai minimal 1.',
                'lantai.max' =>'Lantai maksimal 30.',
                'nomor_kamar.min' => 'Nomor kamar minimal 1.',
            ]);


            //validasi gedung dr nomor unit
            $nomorUnit = strtoupper(
                trim($validated['nomor_unit'])
            );

            if (!preg_match('/^[A-Z]/', $nomorUnit)) {
                throw ValidationException::withMessages([
                    'nomor_unit' => ['Nomor unit harus diawali huruf.'
                    ]
                ]);
            }

            $prefix = substr($nomorUnit, 0, 1);
            $expectedGedung =
                'Tower ' . $prefix;

            if (
                $validated['gedung'] !==
                $expectedGedung
            ) {
                throw ValidationException::withMessages([
                    'gedung' => ["Gedung harus sesuai dengan nomor unit ({$expectedGedung})."
                    ]
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()

            ], 422);
        }

        try {

            return DB::transaction(function () use ($validated) {
                $password = bcrypt(Str::random(16));

                // buat pengguna
                $pengguna = Pengguna::create([
                    'username' =>$validated['nomor_unit'],
                    'password' =>Hash::make($password),
                    'role' => 'unit',
                    'is_active' => true,
                    'must_change_password' => true,
                ]);

                // Buat unit
                $unit = Unit::create([
                    'pengguna_id' =>$pengguna->id,
                    'nomor_unit' =>strtoupper($validated['nomor_unit']),
                    'gedung' =>$validated['gedung'],
                    'lantai' =>$validated['lantai'],
                    'nomor_kamar' => $validated['nomor_kamar'],
                    'status' => 'Aktif',
                ]);

                return response()->json([
                    'success' => true,
                    'message' =>'Unit berhasil ditambahkan',
                    'unit' =>$unit->load('pengguna'),
                ]);
            });

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ================= UPDATE =================
     */
    public function update(Request $request,Unit $unit) 
    {
        try {

            $validated = $request->validate([
                'nomor_unit' => ['required','string','max:10',
                    Rule::unique(
                        'units',
                        'nomor_unit'
                    )->ignore($unit->id)
                ],

                'gedung' => [ 'required','string', 'regex:/^Tower\s[A-Z]$/'],
                'lantai' => [ 'string','required', 'regex:/^[0-9]+$/','max:2',],
                'nomor_kamar' => ['string','required', 'regex:/^[0-9]+$/','max:2',],
            ], [
                'gedung.regex' =>'Format gedung harus seperti Tower A.'
            ]);

            // validasi gedung dari nomor unit
            $nomorUnit = strtoupper(
                trim($validated['nomor_unit'])
            );

            if (!preg_match('/^[A-Z]/', $nomorUnit)) {

                throw ValidationException::withMessages([

                    'nomor_unit' => [
                        'Nomor unit harus diawali huruf.'
                    ]
                ]);
            }

            $prefix = substr($nomorUnit, 0, 1);

            $expectedGedung = 'Tower ' . $prefix;

            if (
                $validated['gedung'] !==
                $expectedGedung
            ) {

                throw ValidationException::withMessages([

                    'gedung' => ["Gedung harus sesuai dengan nomor unit ({$expectedGedung})."
                    ]
                ]);
            }

        } catch (ValidationException $e) {

            return response()->json([

                'success' => false,

                'errors' => $e->errors()

            ], 422);
        }

        return DB::transaction(function () use (
            $unit,
            $validated
        ) {

            // UPDATE USERNAME LOGIN
            $unit->pengguna->update([
                'username' =>
                    strtoupper(
                        $validated['nomor_unit']
                    ),
                'is_active' => $unit->status === 'Aktif'
            ]);

            // Update unit
            $unit->update([
                'nomor_unit' =>strtoupper( $validated['nomor_unit']),
                'gedung' => $validated['gedung'],
                'lantai' =>$validated['lantai'],
                'nomor_kamar' =>$validated['nomor_kamar'],
            ]);

            return response()->json([
                'success' => true,
                'message' =>'Unit berhasil diperbarui',
                'unit' => $unit->fresh([
                    'pengguna',
                    'penghuniAktif'
                ])
            ]);
        });
    }


    /**
     * ================= RESET PASSWORD =================
     */
    public function resetPassword(Unit $unit)
    {
        return DB::transaction(function () use ($unit) {

            $newPassword = $this->generatePassword();
            $unit->pengguna->update([
                'password' =>
                    Hash::make($newPassword),
                'must_change_password' => true,
            ]);

            return response()->json([
                'success' => true,
                'new_password' => $newPassword
            ]);
        });
    }

    /**
     * ================= GANTI PENGHUNI =================
     */
    public function gantiPenghuni(Request $request,Unit $unit) {
        $request->validate([
            'penghuni_id' =>'required|exists:penghunis,id',
        ]);

        return DB::transaction(function () use (
            $request,
            $unit
        ) {

            $penghuniBaru = Penghuni::findOrFail(
                $request->penghuni_id
            );

           // cek sudah punya unit
            $masihAktif = RiwayatHunian::where('penghuni_id',$penghuniBaru->id)
                ->where('status', 'Aktif')
                ->exists();

            if ($masihAktif) {
                return response()->json([
                    'success' => false,
                    'message' =>'Penghuni masih menempati unit lain.'
                ], 422);
            }

            // Nonalktifkan penghuni lama 
            RiwayatHunian::where('unit_id',$unit->id)
            ->where('status', 'Aktif')
            ->update([
                'status' => 'Nonaktif',
                'tanggal_keluar' => now(),
            ]);

            // SET PENGHUNI BARI 
            RiwayatHunian::create([
                'penghuni_id' =>$penghuniBaru->id,
                'unit_id' =>$unit->id,
                'status' => 'Aktif',
                'tanggal_masuk' => now(),
            ]);

            // Reset password untuk login akun unit 
            $passwordBaru = $this->generatePassword();
            $unit->pengguna->update([
                'password' => Hash::make($passwordBaru),
                'must_change_password' => true,
            ]);

            // Aktifkan unit
            $unit->update([
                'status' => 'Aktif'
            ]);
            return response()->json([
                'success' => true,
                'password' => $passwordBaru
            ]);
        });
    }

    /**
     * ================= TOGGLE STATUS =================
     */
    public function toggleStatus(Request $request,Unit $unit) 
    {
        $request->validate([
            'action' =>
                'required|in:aktif,nonaktif'
        ]);

        return DB::transaction(function () use (
            $request,
            $unit
        ) {

            // nonaktifkan akun 
            if ($request->action === 'nonaktif') {

                $unit->update(['status' => 'Nonaktif' ]);
                $unit->pengguna->update(['is_active' => false ]);
                

                // Lepas penghuni
                RiwayatHunian::where('unit_id',$unit->id
                )
                ->where('status', 'Aktif')
                ->update([
                    'status' => 'Nonaktif',
                    'tanggal_keluar' => now(),
                ]);

            } else {

               // aktifkan unit
                $unit->update(['status' => 'Aktif']);
                $unit->pengguna->update(['is_active' => true]);
            }

            return response()->json([
                'success' => true,
                'status' => $unit->status
            ]);
        });
    }
}