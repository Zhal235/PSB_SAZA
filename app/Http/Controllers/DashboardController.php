<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\FinancialRecord;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Pendaftar
        $totalPendaftar = CalonSantri::count();
        $pendaftarMTs = CalonSantri::where('jenjang', 'MTs')->count();
        $pendaftarSMK = CalonSantri::where('jenjang', 'SMK')->count();
        
        // Statistik Status
        $statusBaru = CalonSantri::where('status', 'baru')->count();
        $statusDiterima = CalonSantri::where('status', 'diterima')->count();
        $statusDitolak = CalonSantri::where('status', 'ditolak')->count();
        
        // Statistik Dokumen
        $totalDokumen = \App\Models\Dokumen::count();
        $dokumenBelumVerifikasi = CalonSantri::where('status_hardcopy', 0)->count();
        $dokumenSudahVerifikasi = CalonSantri::where('status_hardcopy', 1)->count();
        
        // Statistik Keuangan
        $totalPemasukan = FinancialRecord::where('type', 'income')->sum('amount');
        $totalPengeluaran = FinancialRecord::where('type', 'expense')->sum('amount');
        $saldoTotal = $totalPemasukan - $totalPengeluaran;
        
        $saldoCash = FinancialRecord::where('type', 'income')->where('payment_method', 'cash')->sum('amount') 
                   - FinancialRecord::where('type', 'expense')->where('payment_method', 'cash')->sum('amount');
        
        $saldoTransfer = FinancialRecord::where('type', 'income')->where('payment_method', 'transfer')->sum('amount') 
                       - FinancialRecord::where('type', 'expense')->where('payment_method', 'transfer')->sum('amount');
        
        // Statistik Pembayaran
        $totalTagihan = Pembayaran::sum('total_amount');
        $totalTerbayar = Pembayaran::sum('paid_amount');
        $sisaTagihan = $totalTagihan - $totalTerbayar;
        
        $pembayaranLunas = Pembayaran::whereRaw('paid_amount >= total_amount')->count();
        $pembayaranBelum = Pembayaran::where('paid_amount', 0)->count();
        $pembayaranCicilan = Pembayaran::whereRaw('paid_amount > 0 AND paid_amount < total_amount')->count();
        
        // Aktivitas Terbaru (10 pendaftar terakhir)
        $recentActivities = CalonSantri::orderBy('created_at', 'desc')->take(10)->get();
        
        return view('admin.dashboard', compact(
            'totalPendaftar', 'pendaftarMTs', 'pendaftarSMK',
            'statusBaru', 'statusDiterima', 'statusDitolak',
            'totalDokumen', 'dokumenBelumVerifikasi', 'dokumenSudahVerifikasi',
            'totalPemasukan', 'totalPengeluaran', 'saldoTotal', 'saldoCash', 'saldoTransfer',
            'totalTagihan', 'totalTerbayar', 'sisaTagihan',
            'pembayaranLunas', 'pembayaranBelum', 'pembayaranCicilan',
            'recentActivities'
        ));
    }
}
