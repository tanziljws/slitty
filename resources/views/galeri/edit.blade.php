@extends('layouts.dashboard')

@section('title', 'Edit Galeri - Galeri Edu')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Galeri</h1>
            <p class="text-gray-600 mt-1">Edit foto dan informasi galeri</p>
        </div>
        <a href="{{ route('galeri.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('galeri.update', $galeri) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Galeri</label>
                        <input type="text" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $galeri->post->judul ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select id="kategori_id" 
                                name="kategori_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" 
                                        {{ old('kategori_id', $galeri->post->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('deskripsi', $galeri->post->isi ?? '') }}</textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="aktif" {{ old('status', $galeri->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $galeri->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Current Photos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                        <div class="grid grid-cols-2 gap-4">
                            @forelse($galeri->fotos as $foto)
                                <div class="relative">
                                    <img src="{{ asset('uploads/galeri/' . $foto->file) }}" 
                                         alt="Foto galeri" 
                                         class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute top-2 right-2">
                                        <button type="button" 
                                                onclick="deletePhoto({{ $foto->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8 text-gray-500">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <p>Belum ada foto</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upload New Photos -->
                    <div>
                        <label for="fotos" class="block text-sm font-medium text-gray-700 mb-2">Tambah Foto Baru</label>
                        <input type="file" 
                               id="fotos" 
                               name="fotos[]" 
                               multiple 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Pilih multiple file untuk upload beberapa foto sekaligus</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('galeri.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Photo Form (Hidden) -->
<form id="deletePhotoForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function deletePhoto(fotoId) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        const form = document.getElementById('deletePhotoForm');
        // Use route helper or direct path
        form.action = `/galeri/foto/${fotoId}`;
        form.submit();
        
        // Prevent any default behavior that might cause navigation issues
        return false;
    }
    return false;
}
</script>
@endsection
