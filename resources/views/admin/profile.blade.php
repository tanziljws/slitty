@extends('layouts.dashboard')

@section('title', 'Admin Profile')

@section('content')
<!-- Header Section -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Admin</h1>
            <p class="text-gray-600 mt-1">Kelola profil dan pengaturan akun Anda</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openEditProfileModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center space-x-2 shadow-lg">
                <i class="fas fa-edit"></i>
                <span>Edit Profil</span>
            </button>
            <button onclick="openChangePasswordModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center space-x-2 shadow-lg">
                <i class="fas fa-key"></i>
                <span>Ganti Password</span>
            </button>
        </div>
    </div>
</div>

<!-- Profile Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ auth()->user()->name ?? 'Admin User' }}</h2>
                <p class="text-gray-600 mb-4">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Admin Aktif
                    </span>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Bergabung Sejak</p>
                            <p class="font-medium text-gray-900">{{ auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Terakhir Login</p>
                            <p class="font-medium text-gray-900">{{ now()->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Foto</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalFotos }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Petugas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPetugas }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-2xl font-bold text-gray-900">1.2K</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-upload text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Foto baru diupload</p>
                        <p class="text-xs text-gray-500">2 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Petugas baru ditambahkan</p>
                        <p class="text-xs text-gray-500">5 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-folder-plus text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Kategori baru dibuat</p>
                        <p class="text-xs text-gray-500">1 hari yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings and Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Account Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Akun</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="text-sm font-medium text-gray-900">Notifikasi Email</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-shield-alt text-gray-600"></i>
                    <span class="text-sm font-medium text-gray-900">Two-Factor Auth</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-globe text-gray-600"></i>
                    <span class="text-sm font-medium text-gray-900">Bahasa</span>
                </div>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Bahasa Indonesia</option>
                    <option>English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Versi Aplikasi</span>
                <span class="text-sm text-gray-600">v1.0.0</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Framework</span>
                <span class="text-sm text-gray-600">Laravel 10.x</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Database</span>
                <span class="text-sm text-gray-600">MySQL 8.0</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Server</span>
                <span class="text-sm text-gray-600">Apache/Nginx</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Last Update</span>
                <span class="text-sm text-gray-600">{{ now()->format('d M Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Profil</h3>
                    <button onclick="closeEditProfileModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.profile.update') }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+62 xxx-xxxx-xxxx">
                    </div>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea id="bio" name="bio" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ceritakan sedikit tentang diri Anda">{{ auth()->user()->bio ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditProfileModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Ganti Password</h3>
                    <button onclick="closeChangePasswordModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.password.update') }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeChangePasswordModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Modal functions
function openEditProfileModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

function openChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.remove('hidden');
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editProfileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditProfileModal();
    }
});

document.getElementById('changePasswordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChangePasswordModal();
    }
});
</script>
@endpush
