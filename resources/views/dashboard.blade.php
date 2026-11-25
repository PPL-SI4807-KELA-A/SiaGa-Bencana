@extends('layouts.app')

@section('title', 'Dasbor')
@section('menu_aktif', 'dasbor')

@section('content')
    <!-- Notifikasi Sukses -->
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #badbcc;">
        <h4 style="margin: 0 0 5px 0; font-size: 1.05rem;">Status Sprint 1: Aktif & Sesuai Target</h4>
        <p style="margin: 0; font-size: 0.95rem;">Master Layout Blade dan komponen antarmuka modular berjalan stabil di branch <strong>arkan</strong>.</p>
    </div>

    <!-- Kartu Statistik Darurat -->
    <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 220px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border-left: 4px solid #dc3545;">
            <span style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 5px;">Bencana Aktif</span>
            <h3 style="margin: 0; color: #212529; font-size: 1.6rem;">3 Wilayah</h3>
        </div>
        <div style="flex: 1; min-width: 220px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border-left: 4px solid #ffc107;">
            <span style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 5px;">Posko Siaga</span>
            <h3 style="margin: 0; color: #212529; font-size: 1.6rem;">12 Posko</h3>
        </div>
        <div style="flex: 1; min-width: 220px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border-left: 4px solid #0d6efd;">
            <span style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 5px;">Relawan Terdaftar</span>
            <h3 style="margin: 0; color: #212529; font-size: 1.6rem;">145 Orang</h3>
        </div>
    </div>

    <!-- Tabel Laporan Darurat Terbaru (Upgrade Full Spec) -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.1rem; color: #333;">Laporan Darurat Terbaru</h3>
            <span style="font-size: 0.85rem; color: #6c757d; background: #e9ecef; padding: 4px 10px; border-radius: 4px;">Live Data Ready</span>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #dee2e6; color: #495057;">
                        <th style="padding: 10px;">ID Laporan</th>
                        <th style="padding: 10px;">Jenis Bencana</th>
                        <th style="padding: 10px;">Lokasi</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f2f2f2;">
                        <td style="padding: 12px 10px; color: #6c757d;">#DR-001</td>
                        <td style="padding: 12px 10px; font-weight: 500;">Banjir Bandang</td>
                        <td style="padding: 12px 10px;">Dayeuhkolot, Bandung</td>
                        <td style="padding: 12px 10px;"><span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Darurat</span></td>
                        <td style="padding: 12px 10px;"><a href="#" style="color: #0d6efd; text-decoration: none; font-weight: 500;">Detail</a></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f2f2f2;">
                        <td style="padding: 12px 10px; color: #6c757d;">#DR-002</td>
                        <td style="padding: 12px 10px; font-weight: 500;">Tanah Longsor</td>
                        <td style="padding: 12px 10px;">Lembang, Bandung Barat</td>
                        <td style="padding: 12px 10px;"><span style="background: #f8d7da; color: #842029; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Evakuasi</span></td>
                        <td style="padding: 12px 10px;"><a href="#" style="color: #0d6efd; text-decoration: none; font-weight: 500;">Detail</a></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 10px; color: #6c757d;">#DR-003</td>
                        <td style="padding: 12px 10px; font-weight: 500;">Puting Beliung</td>
                        <td style="padding: 12px 10px;">Rancaekek, Kab. Bandung</td>
                        <td style="padding: 12px 10px;"><span style="background: #d1e7dd; color: #0f5132; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">Tertangani</span></td>
                        <td style="padding: 12px 10px;"><a href="#" style="color: #0d6efd; text-decoration: none; font-weight: 500;">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
