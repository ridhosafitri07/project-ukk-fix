<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Permintaan Sarana Prasarana') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.sarpras.permintaan-list') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Permintaan -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Permintaan</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Permintaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $permintaan->id_item }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Permintaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->tanggal_permintaan->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($permintaan->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                                            @elseif($permintaan->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                                            @elseif($permintaan->status_permintaan === 'Ditolak') bg-red-100 text-red-800
                                            @endif">
                                            {{ $permintaan->status_permintaan }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Informasi Barang -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Barang</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Barang</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->nama_barang }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Spesifikasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->spesifikasi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->jumlah }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alasan Penggantian</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->alasan_penggantian }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($permintaan->status_permintaan === 'Menunggu Persetujuan')
                    <!-- Form Persetujuan -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan</h3>
                        <form action="{{ route('admin.sarpras.update-status', $permintaan->id_item) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1 space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status" value="Disetujui" class="form-radio">
                                            <span class="ml-2">Setujui</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status" value="Ditolak" class="form-radio">
                                            <span class="ml-2">Tolak</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label for="catatan_admin" class="block text-sm font-medium text-gray-700">
                                        Catatan Admin
                                    </label>
                                    <textarea id="catatan_admin" name="catatan_admin" rows="3" 
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                              placeholder="Tambahkan catatan untuk permintaan ini"></textarea>
                                </div>
                                <div>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Informasi Persetujuan -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Persetujuan</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Persetujuan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->tanggal_persetujuan ? $permintaan->tanggal_persetujuan->format('d/m/Y H:i') : '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $permintaan->catatan_admin ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>