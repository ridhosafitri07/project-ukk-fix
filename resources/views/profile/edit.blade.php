@extends('layouts.' . auth()->user()->role)

@section('title', 'Edit Profil')
@section('header', 'Edit Profil')
@section('subheader', 'Perbarui informasi akun Anda')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('profile.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white rounded-xl text-slate-700 hover:bg-slate-50 transition-all shadow-sm border border-slate-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm animate-slide-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
            <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
        <div class="p-6 bg-gradient-to-r 
            {{ auth()->user()->role === 'admin' ? 'from-indigo-500 to-purple-600' : 
               (auth()->user()->role === 'petugas' ? 'from-emerald-500 to-green-600' : 
               'from-blue-500 to-sky-600') }}">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-user-edit mr-2"></i>
                Informasi Profil
            </h3>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Profile Photo -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Foto Profil</label>
                    <div class="flex items-center space-x-6">
                        <div class="relative group">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" 
                                     alt="Profile Photo" 
                                     id="preview-image"
                                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-slate-100 shadow-lg">
                            @else
                                <div id="preview-initials" class="w-24 h-24 rounded-2xl flex items-center justify-center text-white font-bold text-3xl ring-4 ring-slate-100 shadow-lg
                                    {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-700' : 
                                       ($user->role === 'petugas' ? 'bg-gradient-to-br from-emerald-500 to-green-700' : 
                                       'bg-gradient-to-br from-blue-500 to-sky-700') }}">
                                    {{ strtoupper(substr($user->nama_pengguna, 0, 2)) }}
                                </div>
                                <img src="" alt="Preview" id="preview-image" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-slate-100 shadow-lg hidden">
                            @endif
                            <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fas fa-camera text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" 
                                   class="block w-full text-sm text-slate-500
                                   file:mr-4 file:py-2.5 file:px-4
                                   file:rounded-xl file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-gradient-to-r 
                                   {{ auth()->user()->role === 'admin' ? 'file:from-indigo-50 file:to-purple-50 file:text-indigo-700' : 
                                      (auth()->user()->role === 'petugas' ? 'file:from-emerald-50 file:to-green-50 file:text-emerald-700' : 
                                      'file:from-blue-50 file:to-sky-50 file:text-blue-700') }}
                                   hover:file:brightness-95
                                   cursor-pointer">
                            <p class="mt-2 text-xs text-slate-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                JPG, PNG, atau GIF. Maksimal 2MB.
                            </p>
                            @error('foto_profil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($user->foto_profil)
                            <form action="{{ route('profile.delete-photo') }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus foto profil?')"
                                        class="text-sm text-red-600 hover:text-red-800 font-medium">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus Foto
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_pengguna" class="block text-sm font-bold text-slate-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" required
                           value="{{ old('nama_pengguna', $user->nama_pengguna) }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 focus:border-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 transition-all">
                    @error('nama_pengguna')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-bold text-slate-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" id="username" required
                           value="{{ old('username', $user->username) }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 focus:border-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 transition-all">
                    @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="telp_user" class="block text-sm font-bold text-slate-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="text" name="telp_user" id="telp_user"
                           value="{{ old('telp_user', $user->telp_user) }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 focus:border-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 transition-all">
                    @error('telp_user')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-bold text-slate-700 mb-2">
                        Bio / Tentang Saya
                    </label>
                    <textarea name="bio" id="bio" rows="4"
                              placeholder="Ceritakan sedikit tentang diri Anda..."
                              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 focus:border-{{ auth()->user()->role === 'admin' ? 'indigo' : (auth()->user()->role === 'petugas' ? 'emerald' : 'blue') }}-500 transition-all">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Maksimal 500 karakter</p>
                    @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('profile.index') }}" 
                       class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r 
                            {{ auth()->user()->role === 'admin' ? 'from-indigo-500 to-purple-600' : 
                               (auth()->user()->role === 'petugas' ? 'from-emerald-500 to-green-600' : 
                               'from-blue-500 to-sky-600') }}
                            text-white rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div id="change-password" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-rose-500 to-red-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-key mr-2"></i>
                Ganti Password
            </h3>
        </div>
        <form action="{{ route('profile.change-password') }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-amber-400 mr-3 mt-0.5"></i>
                        <p class="text-sm text-amber-800">
                            Pastikan password baru Anda kuat dan mudah diingat. Jangan bagikan password kepada siapapun.
                        </p>
                    </div>
                </div>

                <!-- Password Lama -->
                <div>
                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all">
                        <button type="button" onclick="togglePassword('current_password')" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="current_password-icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Minimal 6 karakter</p>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all">
                        <button type="button" onclick="togglePassword('password_confirmation')" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-slate-200">
                    <button type="button" onclick="document.getElementById('password').value=''; document.getElementById('current_password').value=''; document.getElementById('password_confirmation').value='';"
                            class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                        Reset Form
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center">
                        <i class="fas fa-key mr-2"></i>
                        Ganti Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Preview foto profil
document.getElementById('foto_profil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('preview-image');
            const previewInitials = document.getElementById('preview-initials');
            
            previewImage.src = e.target.result;
            previewImage.classList.remove('hidden');
            
            if (previewInitials) {
                previewInitials.classList.add('hidden');
            }
        }
        reader.readAsDataURL(file);
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<style>
@keyframes slideIn { 
    from { opacity: 0; transform: translateY(-10px); } 
    to { opacity: 1; transform: translateY(0); } 
}
.animate-slide-in { animation: slideIn 0.3s ease-out; }
</style>
@endsection