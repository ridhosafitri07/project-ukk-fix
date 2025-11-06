@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h3 class="text-2xl font-semibold text-gray-800">Edit User</h3>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="nama_pengguna" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required value="{{ old('nama_pengguna', $user->nama_pengguna) }}">
                    @error('nama_pengguna')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required value="{{ old('username', $user->username) }}">
                    @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Role</option>
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="pengguna" {{ old('role', $user->role) == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                    </select>
                    @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pekerjaan Field (hanya muncul jika role petugas) -->
                <div id="pekerjaan-field" style="display: none;">
                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <select name="pekerjaan" id="pekerjaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Pekerjaan</option>
                        <option value="CS" {{ old('pekerjaan', $user->petugas->pekerjaan ?? '') == 'CS' ? 'selected' : '' }}>Customer Service (CS)</option>
                        <option value="Teknisi" {{ old('pekerjaan', $user->petugas->pekerjaan ?? '') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="Administrasi" {{ old('pekerjaan', $user->petugas->pekerjaan ?? '') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                        <option value="Supervisor" {{ old('pekerjaan', $user->petugas->pekerjaan ?? '') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                    @error('pekerjaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
-
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const pekerjaanField = document.getElementById('pekerjaan-field');
    const pekerjaanSelect = document.getElementById('pekerjaan');
    
    function togglePekerjaanField() {
        if (roleSelect.value === 'petugas') {
            pekerjaanField.style.display = 'block';
            pekerjaanSelect.required = true;
        } else {
            pekerjaanField.style.display = 'none';
            pekerjaanSelect.required = false;
        }
    }
    
    // Check on page load
    togglePekerjaanField();
    
    // Check when role changes
    roleSelect.addEventListener('change', togglePekerjaanField);
});
</script>
@endsection