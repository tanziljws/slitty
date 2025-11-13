@extends('layouts.dashboard')

@section('title', 'Edit Informasi Sekolah')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Informasi Sekolah</h1>
            <p class="text-gray-600 mt-1">Edit profil dan identitas sekolah</p>
        </div>
        <div>
            <a href="{{ route('site-settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('informasi.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Title -->
            <div class="mb-6">
                <label for="profile_title" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-heading text-blue-600 mr-2"></i>Judul Profil
                </label>
                <input type="text" 
                       name="profile_title" 
                       id="profile_title" 
                       value="{{ old('profile_title', $settings['profile_title']->value ?? 'Profil SMKN 4 BOGOR') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profile_title') border-red-500 @enderror"
                       required>
                @error('profile_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Content -->
            <div class="mb-6">
                <label for="profile_content" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>Konten Profil
                </label>
                <textarea name="profile_content" 
                          id="profile_content" 
                          rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profile_content') border-red-500 @enderror"
                          required>{{ old('profile_content', $settings['profile_content']->value ?? 'SMK Negeri 4 Bogor dikenal juga dengan sebutan NEBRAZKA (mirip Nebraska negara bagian Amerika) singkatan dari Negeri Empat Bogor AZKA. Azka sendiri memiliki arti suci, berbudi luhur.') }}</textarea>
                @error('profile_content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Image -->
            <div class="mb-6">
                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-image text-blue-600 mr-2"></i>Foto Profil Sekolah
                </label>
                
                @if(isset($settings['profile_image']) && $settings['profile_image']->value)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $settings['profile_image']->value) }}" 
                                 alt="Profile Image" 
                                 class="w-64 h-40 object-cover rounded-lg border border-gray-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/50 to-blue-700/50 rounded-lg"></div>
                        </div>
                    </div>
                @endif

                <input type="file" 
                       name="profile_image" 
                       id="profile_image" 
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profile_image') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Foto akan ditampilkan dengan overlay biru transparan.</p>
                @error('profile_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('site-settings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
