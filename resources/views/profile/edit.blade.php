@extends('layouts.pengguna')

@section('title', 'Edit Profil')
@section('header', 'Edit Profil')
@section('subheader', 'Perbarui informasi akun Anda')

@section('content')
<style>
    .stat-card-modern {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 12px -2px rgba(0,0,0,0.03);
    }

    .stat-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(139, 92, 246, 0.2);
    }

    .form-label {
        @apply block text-sm font-semibold text-gray-700 mb-2;
    }

    .form-input, .form-textarea {
        @apply w-full px-4 py-3 rounded-xl border border-gray-200 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition;
    }

    .btn-gradient {
        @apply px-6 py-3 font-bold rounded-xl text-white shadow-lg transition-all;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(102, 126, 234, 0.4);
    }

    .btn-outline {
        @apply px-6 py-3 font-bold rounded-xl border-2 text-purple-600 border-purple-200 hover:bg-purple-50 transition;
    }

    .preview-avatar {
        transition: all 0.3s ease;
    }

    .preview-avatar:hover {
        transform: scale(1.08) rotate(3deg);
    }

    .password-toggle {
        @apply absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600;
    }
</style>

<div class="space-y-8">

    <!-- ✅ Success Notification -->
    @if(session('success'))
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-5 text-white shadow-lg animate-fade-in-up">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-2xl"></i>
            <div>
                <p class="font-bold text-lg">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- 🖼️ Edit Profile Card -->
    <div class="stat-card-modern animate-fade-in-up">
        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-user-edit text-purple-500"></i>
            Edit Profil
        </h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- 📸 Photo Upload Section -->
            <div class="border-b border-gray-100 pb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-camera text-purple-500"></i>
                    Foto Profil
                </h3>

                <div class="flex flex-col md:flex-row items-start gap-8">
                    <!-- Preview -->
                    <div class="relative group">
                        <div class="relative">
                            @if($user->foto_profil)
                                <img id="preview-image" src="{{ asset('storage/' . $user->foto_profil) }}"
                                     alt="Preview Foto"
                                     class="w-32 h-32 rounded-2xl object-cover ring-2 ring-gray-200 shadow-md preview-avatar">
                            @else
                                <div id="preview-initials" class="w-32 h-32 rounded-2xl flex items-center justify-center text-white font-black text-3xl ring-2 ring-gray-200 shadow-md bg-gradient-to-br from-purple-500 to-pink-500 preview-avatar">
                                    {{ strtoupper(substr($user->nama_pengguna, 0, 1)) }}
                                </div>
                                <img id="preview-image" src="" alt="Preview" class="w-32 h-32 rounded-2xl object-cover ring-2 ring-gray-200 shadow-md preview-avatar hidden">
                            @endif
                            <div class="absolute inset-0 bg-black/30 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <i class="fas fa-camera text-white text-xl"></i>
                            </div>
                        </div>
                        <p class="mt-3 text-center text-sm text-gray-600 font-medium">Klik untuk ganti</p>
                    </div>

                    <!-- Upload & Info -->
                    <div class="flex-1">
                        <label for="foto_profil" class="block mb-3">
                            <span class="cursor-pointer inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 font-semibold rounded-xl hover:from-purple-100 hover:to-pink-100 border border-purple-200 transition">
                                <i class="fas fa-upload"></i>
                                <span>Pilih Gambar</span>
                            </span>
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" class="hidden">
                        </label>

                        <p class="mt-3 text-sm text-gray-600">
                            Format: JPG, PNG, GIF ≤ 2MB<br>
                            Disarankan ukuran ≥ 400×400 px
                        </p>

                        @error('foto_profil')
                            <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror

                        <!-- Delete Photo -->
                        @if($user->foto_profil)
                        <form action="{{ route('profile.delete-photo') }}" method="POST" class="mt-4 inline-block" onsubmit="return confirm('Yakin hapus foto profil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-semibold">
                                <i class="fas fa-trash-alt"></i> Hapus Foto
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ✍️ Personal Info -->
            <div class="border-b border-gray-100 pb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-user text-purple-500"></i>
                    Informasi Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_pengguna" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengguna" id="nama_pengguna" required
                               value="{{ old('nama_pengguna', $user->nama_pengguna) }}"
                               class="form-input">
                        @error('nama_pengguna')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="form-label">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" required
                               value="{{ old('username', $user->username) }}"
                               class="form-input">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="telp_user" class="form-label">Nomor Telepon</label>
                        <input type="text" name="telp_user" id="telp_user"
                               value="{{ old('telp_user', $user->telp_user) }}"
                               placeholder="Contoh: 081234567890"
                               class="form-input">
                        @error('telp_user')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="md:col-span-2">
                        <label for="bio" class="form-label">Bio / Tentang Anda</label>
                        <textarea name="bio" id="bio" rows="3" maxlength="500"
                                  placeholder="Ceritakan tentang diri Anda (maks. 500 karakter)..."
                                  class="form-textarea resize-none">{{ old('bio', $user->bio) }}</textarea>
                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-gray-500">Karakter: <span id="charCount" class="font-bold text-purple-600">{{ strlen($user->bio ?? '') }}/500</span></p>
                        </div>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 🔐 Account Info (Read-only) -->
            <div class="border-b border-gray-100 pb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-purple-500"></i>
                    Informasi Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 font-medium">Role</p>
                        <p class="text-gray-900 font-bold mt-1">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 font-medium">Email</p>
                        <p class="text-gray-900 font-bold mt-1 break-all">{{ $user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 font-medium">Bergabung</p>
                        <p class="text-gray-900 font-bold mt-1">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- ✅ Submit Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('profile.index') }}" class="btn-outline justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
                <button type="submit" class="btn-gradient flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- 🔑 Change Password Card -->
    <div class="stat-card-modern animate-fade-in-up" style="animation-delay: 0.1s">
        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-lock text-purple-500"></i>
            Ubah Kata Sandi
        </h2>

        @if(session('password_success'))
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl p-4 text-white mb-6 animate-fade-in-up">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('password_success') }}</span>
            </div>
        </div>
        @endif

        <form action="{{ route('profile.change-password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="form-label">Password Saat Ini <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required
                               placeholder="Masukkan password lama Anda"
                               class="form-input pr-12">
                        <button type="button" class="password-toggle toggle-password" data-target="current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="form-label">Password Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               placeholder="Minimal 8 karakter"
                               class="form-input pr-12">
                        <button type="button" class="password-toggle toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-600">💡 Gunakan kombinasi huruf besar/kecil, angka, dan simbol untuk keamanan ekstra</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="Ulangi password baru"
                               class="form-input pr-12">
                        <button type="button" class="password-toggle toggle-password" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('profile.index') }}" class="btn-outline justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
                <button type="submit" class="btn-gradient flex items-center justify-center">
                    <i class="fas fa-lock mr-2"></i> Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Character Counter
const bioTextarea = document.getElementById('bio');
const charCount = document.getElementById('charCount');

if (bioTextarea && charCount) {
    bioTextarea.addEventListener('input', () => {
        const len = bioTextarea.value.length;
        charCount.textContent = `${len}/500`;
        charCount.className = len <= 500 ? 'font-bold text-purple-600' : 'font-bold text-red-500';
    });
}

// Photo Preview
document.getElementById('foto_profil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file melebihi 2MB!');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('preview-image');
        const initials = document.getElementById('preview-initials');
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (initials) initials.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});

// Password Toggle
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

// Smooth scroll to first error
document.addEventListener('DOMContentLoaded', () => {
    const firstError = document.querySelector('.text-red-600');
    if (firstError) {
        setTimeout(() => {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});
</script>
@endsection