<?php

namespace App\Http\Controllers;


use App\Models\WorkOrder;
use App\Models\Keluhan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanController extends Controller
{
    public function index()
    {
        return view('tenantrelation.laporan.rekapPenanganan');
    }

    public function getDepartemen()
    {
        $departemen = WorkOrder::select('departemen_tujuan')
            ->distinct()
            ->pluck('departemen_tujuan');

        return response()->json($departemen);
    }

    public function getStatus()
    {
        $status = Keluhan::select('status')
            ->distinct()
            ->pluck('status')
            ->map(function ($s) {
                return ucwords(str_replace('_', ' ', $s));
            });

        return response()->json($status);
    }

    private function getDataRekap($request)
    {
        $query = Keluhan::with([
            'penghuni:id,nama',
            'latestWorkOrder'
        ]);

        if ($request->filled('tglAwal') && $request->filled('tglAkhir')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tglAwal)->startOfDay(),
                Carbon::parse($request->tglAkhir)->endOfDay()
            ]);
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $status = strtolower(str_replace(' ', '_', $request->status));
            $query->where('status', $status);
        }

        if ($request->filled('departemen') && $request->departemen !== 'Semua') {
            $query->whereHas('latestWorkOrder', function ($q) use ($request) {
                $q->where('departemen_tujuan', $request->departemen);
            });
        }

        return $query->latest()->get();
    }

    public function rekapData(Request $request)
    {
        $data = $this->getDataRekap($request);

        return response()->json(
            $data->map(function ($k) {
                $wo = $k->latestWorkOrder;

                return [
                    'id' => $k->id,
                    'tiket' => $k->ticket,
                    'tanggal' => optional($k->created_at)->format('Y-m-d'),
                    'nama' => $k->penghuni->nama ?? '-',
                    'departemen' => $wo->departemen_tujuan ?? '-',
                    'status' => ucwords(str_replace('_', ' ', $wo?->status ?? $k->status))
                ];
            })
        );
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getDataRekap($request);

        $data = $data->map(function ($k) {
            $wo = $k->latestWorkOrder;

            return [
                'tiket' => $k->ticket,
                'tanggal' => optional($k->created_at)->format('d/m/Y'),
                'nama' => $k->penghuni->nama ?? '-',
                'departemen' => $wo->departemen_tujuan ?? '-',
                'status' => ucwords(str_replace('_', ' ', $wo?->status ?? $k->status))
            ];
        });

        $pdf = Pdf::loadView('tenantrelation.laporan.pdf', compact('data'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('rekap-penanganan.pdf'); // atau download()
    }
}