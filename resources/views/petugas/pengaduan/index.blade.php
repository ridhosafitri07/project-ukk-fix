@extends('layouts.petugas')

@section('title', 'Pengaduan Saya')
@section('header', 'Pengaduan Saya')
@section('subheader', 'Daftar pengaduan yang ditugaskan kepada Anda')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-yellow-100 truncate">Baru Diajukan</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">-</p>
            </div>
            <i class="fas fa-inbox text-3xl md:text-4xl text-yellow-200 flex-shrink-0"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-green-100 truncate">Siap Diproses</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">-</p>
            </div>
            <i class="fas fa-clipboard-check text-3xl md:text-4xl text-green-200 flex-shrink-0"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-blue-100 truncate">Tugas Saya</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $statistics['tugas_saya'] }}</p>
            </div>
            <i class="fas fa-user-check text-3xl md:text-4xl text-blue-200 flex-shrink-0"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg md:rounded-xl shadow-lg p-3 md:p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between gap-2 md:gap-0">
            <div class="min-w-0">
                <p class="text-xs md:text-sm font-medium text-purple-100 truncate">Sedang Diproses</p>
                <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2">{{ $statistics['diproses'] }}</p>
            </div>
            <i class="fas fa-cog text-3xl md:text-4xl text-purple-200 flex-shrink-0"></i>
        </div>
    </div>
</div>

    <!-- Pengaduan Saya: daftar pengaduan yang ditugaskan ke petugas -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-md overflow-hidden">
        <div class="p-4 md:p-6 border-b border-slate-200 bg-green-50">
            <div class="flex items-start gap-3">
                <i class="fas fa-clipboard-list text-green-500 text-xl mt-1"></i>
                <div>
                    <h4 class="text-sm font-bold text-green-800">Pengaduan Saya</h4>
                    <p class="text-xs text-green-700 mt-1">Daftar pengaduan yang ditugaskan kepada Anda. Klik Detail untuk mengambil atau menyelesaikan tugas.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase">ID</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase hidden md:table-cell">Pengadu</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase">Judul</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase hidden lg:table-cell">Lokasi</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase">Status</th>
                        <th class="px-3 md:px-6 py-3 md:py-4 text-xs font-bold text-slate-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($pengaduanSaya as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                            <span class="text-xs md:text-sm font-bold text-slate-900">#{{ $item->id_pengaduan }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap hidden sm:table-cell">
                            <span class="text-xs md:text-sm text-slate-600">{{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4 hidden md:table-cell">
                            <span class="text-xs md:text-sm text-slate-900">{{ Str::limit($item->user->nama_pengguna, 15) }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4">
                            <span class="text-xs md:text-sm font-medium text-slate-900">{{ Str::limit($item->nama_pengaduan, 30) }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4 hidden lg:table-cell">
                            <span class="text-xs md:text-sm text-slate-600">{{ Str::limit($item->lokasi, 15) }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                            <span class="px-2 md:px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                                @if($item->status === 'Disetujui') bg-green-100 text-green-800
                                @elseif($item->status === 'Diproses') bg-blue-100 text-blue-800
                                @elseif($item->status === 'Selesai') bg-purple-100 text-purple-800
                                @endif">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                            <a href="{{ route('petugas.pengaduan.show', $item) }}" 
                               class="text-green-600 hover:text-green-900 inline-flex items-center gap-1 text-xs md:text-sm font-medium">
                                <i class="fas fa-eye"></i>
                                <span class="hidden sm:inline">Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-slate-300 text-5xl mb-4"></i>
                            <p class="text-slate-500 font-medium">Tidak ada pengaduan yang ditugaskan kepada Anda</p>
                            <p class="text-slate-400 text-sm mt-2">Pengaduan yang ditugaskan akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengaduanSaya->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $pengaduanSaya->links() }}
        </div>
        @endif
    </div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-green-500', 'text-green-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}
</script>
@endsection
