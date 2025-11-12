@extends('layouts.admin')

@section('title', 'Edit Distribusi Barang')
@section('header', 'Edit Distribusi Barang')
@section('subheader', 'Ubah lokasi/ruangan untuk barang')

@section('content')
<div class="max-w-3xl mx-auto">
            <div class="mb-6">
        <a href="{{ route('admin.relasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Sarpras
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Distribusi Barang
            </h3>
            <p class="text-yellow-100 text-sm mt-1">Update lokasi untuk: <strong>{{ $item->nama_item }}</strong></p>
        </div>

        <form action="{{ route('admin.relasi.update', $item->id_item) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Informasi Barang:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            ID: #{{ $item->id_item }} | Nama: {{ $item->nama_item }} | Saat ini di: {{ $item->lokasis->count() }} lokasi
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-box text-yellow-600 mr-2"></i>
                    Barang
                </label>
                <div class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-sm font-bold text-gray-900">{{ $item->nama_item }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">
                    <i class="fas fa-map-marker-alt text-yellow-600 mr-2"></i>
                    Pilih Lokasi/Ruangan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 border-2 border-gray-300 rounded-lg bg-gray-50 max-h-96 overflow-y-auto">
                    @foreach($lokasis as $lokasi)
                        <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                            <input type="checkbox" name="lokasi[]" id="lokasi_{{ $lokasi->id_lokasi }}" value="{{ $lokasi->id_lokasi }}" {{ $item->lokasis->contains('id_lokasi', $lokasi->id_lokasi) ? 'checked' : '' }} class="w-4 h-4 text-yellow-600">
                            <label for="lokasi_{{ $lokasi->id_lokasi }}" class="ml-3 text-sm cursor-pointer">{{ $lokasi->nama_lokasi }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t-2">
                <a href="{{ route('admin.relasi.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg">Batal</a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg">Update Distribusi</button>
            </div>
        </form>
    </div>
</div>
@endsection
