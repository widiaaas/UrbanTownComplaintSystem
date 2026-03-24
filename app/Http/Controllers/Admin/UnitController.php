<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Penghuni;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index()
    {
        $units = Unit::with(['penghuniAktif', 'user'])->get();
        return view('admin.units.index', compact('units'));
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_unit' => 'required|string|unique:unit,no_unit',
            'gedung' => 'required|string|max:50',
            'lantai' => 'required|integer',
            'nomor_kamar' => 'required|integer',
        ]);

        // Create user for unit
        $user = Pengguna::create([
            'username' => $request->no_unit,
            'password_hash' => Hash::make($password = $this->generatePassword()),
            'role' => 'unit',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        // Create unit
        $unit = Unit::create([
            'no_unit' => $request->no_unit,
            'gedung' => $request->gedung,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
            'status' => 'Aktif',
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil ditambahkan',
            'unit' => $unit,
            'password' => $password,
        ]);
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $request->validate([
            'gedung' => 'required|string|max:50',
            'lantai' => 'required|integer',
            'nomor_kamar' => 'required|integer',
        ]);

        $unit->update([
            'gedung' => $request->gedung,
            'lantai' => $request->lantai,
            'nomor_kamar' => $request->nomor_kamar,
        ]);

        return response()->json(['success' => true, 'message' => 'Unit berhasil diperbarui']);
    }

    /**
     * Remove the specified unit.
     */
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete(); // soft delete
        return response()->json(['success' => true, 'message' => 'Unit berhasil dihapus']);
    }

    /**
     * Change occupant of a unit.
     */
    public function changeOccupant(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $request->validate([
            'penghuni_id' => 'required|exists:penghuni,id',
        ]);

        $newPenghuni = Penghuni::find($request->penghuni_id);
        $oldPenghuniId = $unit->penghuni_aktif_id;

        // Update unit
        $unit->penghuni_aktif_id = $newPenghuni->id;
        $unit->save();

        // Update old penghuni's status if needed (optional: set to nonaktif)
        if ($oldPenghuniId) {
            $oldPenghuni = Penghuni::find($oldPenghuniId);
            if ($oldPenghuni) {
                $oldPenghuni->status = 'Nonaktif';
                $oldPenghuni->tanggal_keluar = now();
                $oldPenghuni->save();
            }
        }

        // Update new penghuni's status
        $newPenghuni->status = 'Aktif';
        $newPenghuni->tanggal_masuk = now();
        $newPenghuni->save();

        // Reset unit's user password
        $user = $unit->user;
        $newPassword = $this->generatePassword();
        $user->password_hash = Hash::make($newPassword);
        $user->must_change_password = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil diganti',
            'new_password' => $newPassword,
        ]);
    }

    /**
     * Reset password for a unit.
     */
    public function resetPassword($id)
    {
        $unit = Unit::findOrFail($id);
        $user = $unit->user;
        $newPassword = $this->generatePassword();
        $user->password_hash = Hash::make($newPassword);
        $user->must_change_password = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset',
            'new_password' => $newPassword,
        ]);
    }

    /**
     * Toggle unit status (Aktif/Nonaktif).
     */
    public function toggleStatus($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->status = $unit->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $unit->save();

        return response()->json([
            'success' => true,
            'message' => 'Status unit berhasil diubah menjadi ' . $unit->status,
        ]);
    }

    /**
     * Get list of available penghuni for dropdown.
     */
    public function getPenghuniList()
    {
        $penghuni = Penghuni::where('status', 'Aktif')
                    ->whereNull('unit_id') // optional: only those without active unit
                    ->orWhere('unit_id', null)
                    ->get(['id', 'nama', 'telepon', 'email']);
        return response()->json($penghuni);
    }

    /**
     * Generate a random password.
     */
    private function generatePassword()
    {
        return 'Tmp-' . Str::upper(Str::random(5));
    }
}