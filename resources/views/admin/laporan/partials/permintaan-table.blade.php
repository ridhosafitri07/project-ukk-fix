<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Permintaan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Barang</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Lokasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Alasan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pemohon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tgl. Persetujuan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
            @forelse ($data as $item)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                    #{{ $item->id_item }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                    {{ $item->tanggal_permintaan ? \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d/m/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-900">
                    <p class="font-medium">{{ $item->nama_barang_baru ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $item->lokasi_barang_baru ?? '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    <p class="max-w-xs truncate">{{ $item->alasan_permintaan ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-900">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-purple-600 font-semibold text-xs">
                                    {{ strtoupper(substr($item->pengaduan->user->nama_pengguna ?? 'U', 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ $item->pengaduan->user->nama_pengguna ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->status_permintaan == 'Disetujui')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                        </span>
                    @elseif($item->status_permintaan == 'Ditolak')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> {{ $item->status_permintaan }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                    {{ $item->tanggal_persetujuan ? \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $item->pengaduan->petugas->nama_lengkap ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-inbox text-5xl mb-3"></i>
                        <p class="text-lg font-medium">Tidak ada data</p>
                        <p class="text-sm">Belum ada laporan permintaan barang yang sesuai filter</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($data->hasPages())
<div class="mt-4">
    {{ $data->appends(request()->query())->links() }}
</div>
@endif
