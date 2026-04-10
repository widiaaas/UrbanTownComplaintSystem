<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['penghunis' => function ($q) {
            $q->where('status', 'Aktif');
        }])
        ->get()
        ->map(function ($u) {
            $penghuniAktif = $u->penghunis->first();
    
            return [
                'id' => $u->id,
                'no_unit' => $u->no_unit,
                'gedung' => $u->gedung,
                'lantai' => $u->lantai,
                'nomor_kamar' => $u->nomor_kamar,
                'status' => $u->status,
                'currentPenghuni' => $penghuniAktif->nama ?? null,
            ];
        });

        return view('admin.units.index', compact('units'));
    }

    /**
     * FIX: relasi sekarang bukan units(), tapi unit()
     */
    public function getAvailablePenghuni()
    {
        $available = Penghuni::whereNull('unit_id')
            ->get(['id', 'nama', 'telepon', 'email']);

        return response()->json($available);
    }

    /**
     * FIX: unit tidak punya kolom password
     * password ada di tabel pengguna
     */
    public function store(Request $request)
    {
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
            
            'gedung.regex' => 'Format gedung harus "Tower A", "Tower B", dst.',
            'lantai.min' => 'Lantai minimal 1.',
            'lantai.max' => 'Lantai maksimal 30.',
            'nomor_kamar.min' => 'Nomor kamar minimal 1.',
            'nomor_kamar.max' => 'Nomor kamar maksimal 30.',
        ]);

        DB::beginTransaction();

        try {
            $password = Str::random(8);

            // buat akun (penggunas)
            $user = \App\Models\Pengguna::create([
                'username' => $validated['no_unit'],
                'password' => Hash::make($password),
                'role' => 'unit',
                'is_active' => true,
                'must_change_password' => true
            ]);

            // buat unit (pakai user_id)
            $unit = Unit::create([
                'no_unit'     => $validated['no_unit'],
                'gedung'      => $validated['gedung'],
                'lantai'      => $validated['lantai'],
                'nomor_kamar' => $validated['nomor_kamar'],
                'status'      => 'Aktif',
                'user_id'     => $user->id
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'unit'     => $unit,
                'password' => $password
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

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
                'gedung.regex' => 'Format gedung harus "Tower A", "Tower B", dst.',
            ]);

            DB::beginTransaction();

            // 🔥 update username di pengguna
            $unit->user->update([
                'username' => $validated['no_unit']
            ]);

            // 🔥 update unit
            $unit->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'unit' => $unit,
                'message' => 'Unit berhasil diperbarui'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIX: tidak perlu detach
     */
    public function destroy(Unit $unit)
    {
        // kosongkan penghuni yang terkait
        Penghuni::where('unit_id', $unit->id)->update([
            'unit_id' => null,
            'status' => 'Nonaktif'
        ]);

        $unit->delete();

        return response()->json(['success' => true]);
    }

    public function resetPassword(Unit $unit)
    {
        DB::beginTransaction();

        try {
            // generate password baru
            $newPassword = Str::random(8);

            // update ke tabel pengguna
            $unit->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'new_password' => $newPassword
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 PERBAIKAN PALING PENTING
     */
    public function changeOccupant(Request $request, Unit $unit)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
        ]);

        DB::beginTransaction();

        try {
            $penghuniBaru = Penghuni::findOrFail($request->penghuni_id);

            if ($penghuniBaru->unit_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penghuni ini sudah menempati unit lain.',
                ], 422);
            }

            // Nonaktifkan semua penghuni aktif di unit ini lewat relasi
            $unit->penghunis()
                ->where('status', 'Aktif')
                ->update([
                    'status' => 'Nonaktif',
                    'tanggal_keluar' => now(),
                    'unit_id' => null,
                ]);

            // Set penghuni baru
            $penghuniBaru->update([
                'unit_id' => $unit->id,
                'status' => 'Aktif',
                'tanggal_masuk' => now(),
            ]);

            // Update status unit (tanpa penghuni_aktif_id)
            $unit->update(['status' => 'Aktif']);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ❌ DIHAPUS (tidak relevan)
     * password unit sekarang ada di tabel pengguna
     */

    public function toggleStatus(Unit $unit)
    {
        $newStatus = $unit->status === 'Aktif' ? 'Nonaktif' : 'Aktif';

        $unit->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 'Aktif'
                ? 'Unit berhasil diaktifkan.'
                : 'Unit berhasil dinonaktifkan.',
        ]);
    }
}