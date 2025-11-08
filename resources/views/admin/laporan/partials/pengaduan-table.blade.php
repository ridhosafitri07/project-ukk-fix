<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pelapor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Isi Laporan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Petugas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
            @forelse ($data as $item)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                    #{{ $item->id_pengaduan }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                    {{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-900">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-xs">
                                    {{ strtoupper(substr($item->user->nama_pengguna ?? 'U', 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ $item->user->nama_pengguna ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    <p class="max-w-xs truncate">{{ $item->isi_laporan }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->status == 'Selesai')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Selesai
                        </span>
                    @elseif($item->status == 'Proses')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-spinner mr-1"></i> Proses
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                            <i class="fas fa-clock mr-1"></i> {{ $item->status }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $item->petugas->nama_lengkap ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                    {{ $item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-inbox text-5xl mb-3"></i>
                        <p class="text-lg font-medium">Tidak ada data</p>
                        <p class="text-sm">Belum ada laporan pengaduan yang sesuai filter</p>
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
