<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('penghuniAktif');

        // ================= SEARCH =================
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('no_unit', 'like', "%$search%")
                ->orWhere('gedung', 'like', "%$search%");
            });
        }

        // ================= FILTER LANTAI =================
        if ($request->filled('lantai')) {
            $query->where('lantai', $request->lantai);
        }

        // ================= DATA =================
        $units = $query->latest()->get();

        // ================= AJAX RESPONSE =================
        if ($request->ajax()) {
            return response()->json($units);
        }

        return view('admin.units.index', compact('units'));
    }
    /**
     * FIX: relasi sekarang bukan units(), tapi unit()
     */
    public function getAvailablePenghuni()
    {
        return Penghuni::available()
            ->select('id','nama','telepon','email')
            ->get();
    }

    /**
     * FIX: unit tidak punya kolom password
     * password ada di tabel pengguna
     */
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_unit' => 'required|string|unique:units,no_unit',
    
                'gedung' => [
                    'required',
                    'string',
                    'regex:/^Tower\s[A-Z]$/'
                ],
    
                'lantai' => 'required|integer|min:1|max:30',
                'nomor_kamar' => 'required|integer|min:1|max:30',
            ], [
                'no_unit.unique' => 'Nomor unit sudah terdaftar.',
                'gedung.regex' => 'Format gedung harus "Tower A", "Tower B", dst.',
                'lantai.min' => 'Lantai minimal 1.',
                'lantai.max' => 'Lantai maksimal 30.',
                'nomor_kamar.min' => 'Nomor kamar minimal 1.',
                'nomor_kamar.max' => 'Nomor kamar maksimal 30.',
            ]);

            $prefix = strtoupper(substr($validated['no_unit'], 0, 1)); // ambil huruf depan
            $expectedGedung = 'Tower ' . $prefix;

            if ($validated['gedung'] !== $expectedGedung) {
                throw ValidationException::withMessages([
                    'gedung' => ["Gedung harus sesuai dengan nomor unit (harus {$expectedGedung})."]
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
    
                $password = Str::random(8);
    
                $user = \App\Models\Pengguna::create([
                    'username' => $validated['no_unit'],
                    'password' => Hash::make($password),
                    'role' => 'unit',
                    'is_active' => true,
                    'must_change_password' => true
                ]);
    
                $unit = Unit::create([
                    ...$validated,
                    'status' => 'Aktif',
                    'user_id' => $user->id
                ]);
    
                return response()->json([
                    'success' => true,
                    'unit' => $unit,
                    'password' => $password
                ]);
            });
    
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, Unit $unit)
    {
        try {
            $validated = $request->validate([
                'no_unit' => 'required|string|unique:units,no_unit,' . $unit->id,

                'gedung' => [
                    'required',
                    'string',
                    'regex:/^Tower\s[A-Z]$/'
                ],

                'lantai' => 'required|integer|min:1|max:30',
                'nomor_kamar' => 'required|integer|min:1|max:30',

            ], [
                'no_unit.unique' => 'Nomor unit sudah terdaftar.',
                'gedung.regex' => 'Format gedung harus "Tower A", "Tower B", dst.',
                'lantai.min' => 'Lantai minimal 1.',
                'lantai.max' => 'Lantai maksimal 30.',
                'nomor_kamar.min' => 'Nomor kamar minimal 1.',
                'nomor_kamar.max' => 'Nomor kamar maksimal 30.',
            ]);

            // 🔥 SAMA DENGAN STORE (CROSS VALIDATION)
            $noUnit = strtoupper(trim($validated['no_unit']));

            if (!preg_match('/^[A-Z]/', $noUnit)) {
                throw ValidationException::withMessages([
                    'no_unit' => ['Nomor unit harus diawali huruf (contoh: A101).']
                ]);
            }

            $prefix = substr($noUnit, 0, 1);
            $expectedGedung = 'Tower ' . $prefix;

            if ($validated['gedung'] !== $expectedGedung) {
                throw ValidationException::withMessages([
                    'gedung' => ["Gedung harus sesuai dengan nomor unit (harus {$expectedGedung})."]
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        return DB::transaction(function () use ($unit, $validated) {

            $unit->user->update([
                'username' => $validated['no_unit']
            ]);

            $unit->update($validated);

            return response()->json([
                'success' => true,
                'unit' => $unit,
                'message' => 'Unit berhasil diperbarui'
            ]);
        });
    }

    /**
     * FIX: tidak perlu detach
     */
    public function destroy(Unit $unit)
    {
        $unit->penghunis()->update([
            'unit_id' => null,
            'status' => 'Nonaktif'
        ]);
    
        $unit->delete();
    
        return response()->json(['success' => true]);
    }

    public function resetPassword(Unit $unit)
    {
        return DB::transaction(function () use ($unit) {

            $newPassword = Str::random(8);

            $unit->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true
            ]);

            return response()->json([
                'success' => true,
                'new_password' => $newPassword
            ]);
        });
    }

    /**
     * 🔥 PERBAIKAN PALING PENTING
     */
    public function gantiPenghuni(Request $request, Unit $unit)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
        ]);

        return DB::transaction(function () use ($request, $unit) {

            $penghuniBaru = Penghuni::findOrFail($request->penghuni_id);

            if ($penghuniBaru->unit_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penghuni ini sudah menempati unit lain.',
                ], 422);
            }

            // nonaktifkan penghuni lama
            $unit->penghunis()
                ->where('status', 'Aktif')
                ->update([
                    'status' => 'Nonaktif',
                    'tanggal_keluar' => now(),
                    'unit_id' => null,
                ]);

            // set penghuni baru
            $penghuniBaru->update([
                'unit_id' => $unit->id,
                'status' => 'Aktif',
                'tanggal_masuk' => now(),
            ]);

            $unit->update(['status' => 'Aktif']);

            return response()->json(['success' => true]);
        });
    }

    public function toggleStatus(Request $request, Unit $unit)
    {
        $request->validate([
            'action' => 'required|in:aktif,nonaktif'
        ]);

        return DB::transaction(function () use ($request, $unit) {

            if ($request->action === 'nonaktif') {

                // nonaktifkan unit
                $unit->update(['status' => 'Nonaktif']);

                // 🔥 hapus penghuni dari unit
                $unit->penghunis()
                    ->where('status', 'Aktif')
                    ->update([
                        'status' => 'Nonaktif',
                        'tanggal_keluar' => now(),
                        'unit_id' => null,
                    ]);

            } else {
                $unit->update(['status' => 'Aktif']);
            }

            return response()->json([
                'success' => true,
                'status' => $unit->status
            ]);
        });
    }
}