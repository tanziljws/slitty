@extends('layouts.dashboard')

@section('title', 'Tambah Informasi')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Informasi</h1>
            <p class="text-gray-600 mt-1">Buat informasi baru untuk ditampilkan di halaman user</p>
        </div>
        <a href="{{ route('informasi.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('informasi.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Informasi <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ikon (Font Awesome) <span class="text-red-500">*</span></label>
                    <input type="text" name="icon" value="{{ old('icon', 'fa-info-circle') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="fa-trophy, fa-graduation-cap" required>
                    <p class="text-xs text-gray-500 mt-1">Contoh: fa-trophy, fa-laptop, fa-users</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampilan</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Angka lebih kecil tampil lebih dulu</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Informasi
                </button>
                <a href="{{ route('informasi.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
