<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <a href="{{ route('admin.pengaduan.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Kembali ke Daftar
                        </a>
                    </div>

                    <!-- Informasi Pengaduan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengaduan</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Pengaduan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $pengaduan->id_pengaduan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ date('d/m/Y H:i', strtotime($pengaduan->tgl_pengajuan)) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pengadu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->user->nama_pengguna }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($pengaduan->status === 'Diajukan') bg-yellow-100 text-yellow-800
                                            @elseif($pengaduan->status === 'Disetujui') bg-green-100 text-green-800
                                            @elseif($pengaduan->status === 'Ditolak') bg-red-100 text-red-800
                                            @elseif($pengaduan->status === 'Diproses') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $pengaduan->status }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pengaduan</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Judul Pengaduan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->nama_pengaduan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->deskripsi }}</dd>
                                </div>
                                @if($pengaduan->file_pendukung)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Pendukung</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ asset('storage/' . $pengaduan->file_pendukung) }}" 
                                           class="text-indigo-600 hover:text-indigo-900"
                                           target="_blank">
                                            Lihat File
                                        </a>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Daftar Item yang Diminta (jika ada) -->
                    @if($pengaduan->temporary_items->count() > 0)
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permintaan Penggantian Barang</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pengaduan->temporary_items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_barang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->spesifikasi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jumlah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($item->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                                                @elseif($item->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $item->status_permintaan }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Form Update Status -->
                    @if($pengaduan->status === 'Diajukan')
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status Pengaduan</h3>
                        <form action="{{ route('admin.pengaduan.update-status', $pengaduan) }}" method="POST">
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
                                            placeholder="Tambahkan catatan untuk pengaduan ini" required></textarea>
                                </div>

                                <div>
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Riwayat Status -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Status</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            @if($pengaduan->tgl_verifikasi)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Verifikasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->tgl_verifikasi ? date('d/m/Y H:i', strtotime($pengaduan->tgl_verifikasi)) : '-' }}</dd>
                            </div>
                            @endif
                            @if($pengaduan->tgl_selesai)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->tgl_selesai ? date('d/m/Y H:i', strtotime($pengaduan->tgl_selesai)) : '-' }}</dd>
                            </div>
                            @endif
                            @if($pengaduan->catatan_admin)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->catatan_admin }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>