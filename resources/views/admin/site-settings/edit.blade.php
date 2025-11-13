@extends('layouts.dashboard')

@section('title', 'Edit ' . $setting->label)

@section('content')
<div class="w-full max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit {{ $setting->label }}</h1>
            <p class="text-gray-600 mt-1">{{ $setting->description }}</p>
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

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('site-settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ $setting->label }}
                    <span class="text-xs font-normal text-gray-500 ml-2">({{ ucfirst($setting->type) }})</span>
                </label>

                @if($setting->type === 'text')
                    <input type="text" 
                           name="value" 
                           value="{{ old('value', $setting->value) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                           required>
                
                @elseif($setting->type === 'textarea')
                    <textarea name="value" 
                              rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                              required>{{ old('value', $setting->value) }}</textarea>
                
                @elseif($setting->type === 'editor')
                    <textarea name="value" 
                              rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 font-mono text-sm"
                              required>{{ old('value', $setting->value) }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Anda bisa menggunakan HTML di sini untuk formatting</p>
                
                @elseif($setting->type === 'image')
                    <div class="space-y-4">
                        @if($setting->value)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                                <img src="{{ asset('uploads/settings/' . $setting->value) }}" 
                                     alt="{{ $setting->label }}" 
                                     class="h-48 w-auto object-cover rounded border-2 border-gray-200 shadow-sm">
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $setting->value ? 'Ganti dengan gambar baru:' : 'Upload Gambar:' }}
                            </label>
                            <input type="file" 
                                   name="value" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                                   {{ $setting->value ? '' : 'required' }}>
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF. Max: 5MB</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('site-settings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p><strong>Key:</strong> {{ $setting->key }}</p>
                    <p><strong>Group:</strong> {{ $setting->group }}</p>
                    <p class="mt-2">{{ $setting->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
