@extends('layouts.dashboard')

@section('title', $groupLabel . ' - Pengaturan Website')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $groupLabel }}</h1>
            <p class="text-gray-600 mt-1">Edit konten {{ strtolower($groupLabel) }}</p>
        </div>
        <a href="{{ route('site-settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('site-settings.update.bulk') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                @foreach($settings as $setting)
                    @if($setting->key === 'social_twitter')
                        @continue
                    @endif
                    <div class="border-b border-gray-200 pb-6 last:border-b-0">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $setting->label }}
                            @if($setting->description)
                                <span class="block text-xs font-normal text-gray-500 mt-1">{{ $setting->description }}</span>
                            @endif
                        </label>

                        @if($setting->type === 'text')
                            <input type="text" 
                                   name="settings[{{ $setting->key }}]" 
                                   value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        
                        @elseif($setting->type === 'textarea')
                            <textarea name="settings[{{ $setting->key }}]" 
                                      rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                        
                        @elseif($setting->type === 'editor')
                            <textarea name="settings[{{ $setting->key }}]" 
                                      rows="8"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 font-mono text-sm">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Anda bisa menggunakan HTML di sini</p>
                        
                        @elseif($setting->type === 'image')
                            <div class="space-y-3">
                                @if($setting->value)
                                    <div class="mb-3">
                                        <img src="{{ asset('uploads/settings/' . $setting->value) }}" 
                                             alt="{{ $setting->label }}" 
                                             class="h-32 w-auto object-cover rounded border border-gray-200">
                                        <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                                    </div>
                                @endif
                                <input type="file" 
                                       name="settings_files[{{ $setting->key }}]" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <p class="text-xs text-gray-500">Upload gambar baru untuk mengganti yang lama</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex gap-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Semua Perubahan
                </button>
                <a href="{{ route('site-settings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection