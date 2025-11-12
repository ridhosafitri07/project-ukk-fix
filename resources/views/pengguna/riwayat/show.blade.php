@extends('layouts.pengguna')

@section('title', 'Detail Riwayat')
@section('header', 'Detail Pengaduan')
@section('subheader', 'Rincian pengaduan yang sudah selesai')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold">#{{ $pengaduan->id_pengaduan }} - {{ $pengaduan->nama_pengaduan }}</h3>
        <p class="text-sm text-gray-500">Selesai: {{ date('d/m/Y', strtotime($pengaduan->tgl_selesai)) }}</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-600">Deskripsi</h4>
                <p class="text-gray-800 mt-2">{{ $pengaduan->deskripsi }}</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-600">Lokasi</h4>
                <p class="text-gray-800 mt-2">{{ $pengaduan->lokasi }}</p>
                <h4 class="text-sm font-semibold text-gray-600 mt-4">Petugas</h4>
                <p class="text-gray-800 mt-2">{{ optional($pengaduan->petugas)->nama ?? '-' }}</p>
            </div>
        </div>

        @if($pengaduan->temporary_items && $pengaduan->temporary_items->count())
        <div class="mt-6">
            <h4 class="text-sm font-semibold text-gray-600">Permintaan Item</h4>
            <ul class="mt-2 space-y-2">
                @foreach($pengaduan->temporary_items as $it)
                <li class="p-3 border rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $it->nama_barang_baru }}</p>
                            <p class="text-sm text-gray-500">{{ $it->alasan_permintaan }}</p>
                        </div>
                        <div class="text-sm text-gray-500">{{ $it->status_permintaan }}</div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="p-6 border-t bg-gray-50">
        <a href="{{ route('pengguna.riwayat.index') }}" class="px-4 py-2 bg-white border rounded-lg">Kembali</a>
    </div>
</div>
@endsection
