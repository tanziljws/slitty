@extends('layouts.dashboard')

@section('title', 'Detail Galeri - Galeri Edu')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Galeri</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap galeri foto</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('galeri.edit', $galeri) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('galeri.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Galeri Info -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Galeri</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Judul</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $galeri->post->judul ?? 'Tanpa Judul' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Kategori</label>
                        <span class="inline-flex px-2 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $galeri->post->kategori->judul ?? 'Tanpa Kategori' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full
                            {{ $galeri->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($galeri->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat</label>
                        <p class="text-gray-800">{{ $galeri->post->created_at ? $galeri->post->created_at->format('d M Y H:i') : 'Tidak diketahui' }}</p>
                    </div>
                </div>
                
                @if($galeri->post->isi)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Deskripsi</label>
                    <p class="text-gray-800">{{ $galeri->post->isi }}</p>
                </div>
                @endif
            </div>

            <!-- Photos Grid -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Foto Galeri</h2>
                
                @if($galeri->fotos && $galeri->fotos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($galeri->fotos as $foto)
                            <div class="relative group aspect-square">
                                <img src="{{ asset('uploads/galeri/' . $foto->file) }}" 
                                     alt="Foto galeri" 
                                     class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal('{{ asset('uploads/galeri/' . $foto->file) }}', '{{ $galeri->post->judul ?? 'Foto' }}')">
                                
                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <button onclick="openImageModal('{{ asset('uploads/galeri/' . $foto->file) }}', '{{ $galeri->post->judul ?? 'Foto' }}')"
                                            class="bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 p-2 rounded-full transition-all">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-image text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium mb-2">Belum ada foto</h3>
                        <p class="mb-4">Galeri ini belum memiliki foto</p>
                        <a href="{{ route('galeri.edit', $galeri) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Foto
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('galeri.edit', $galeri) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Galeri
                    </a>
                    
                    <button onclick="toggleStatus({{ $galeri->id }}, '{{ $galeri->status }}')"
                            class="w-full flex items-center justify-center px-4 py-2 {{ $galeri->status === 'aktif' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md transition-colors">
                        <i class="fas fa-toggle-{{ $galeri->status === 'aktif' ? 'on' : 'off' }} mr-2"></i>
                        {{ $galeri->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    
                    <button onclick="confirmDelete({{ $galeri->id }}, '{{ $galeri->post->judul ?? 'galeri' }}')"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Hapus Galeri
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Foto</span>
                        <span class="font-semibold text-gray-800">{{ $galeri->fotos ? $galeri->fotos->count() : 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        <span class="font-semibold {{ $galeri->status === 'aktif' ? 'text-green-600' : 'text-gray-600' }}">
                            {{ ucfirst($galeri->status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Terakhir Update</span>
                        <span class="font-semibold text-gray-800">
                            {{ $galeri->post->updated_at ? $galeri->post->updated_at->format('d M Y') : 'Tidak diketahui' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Modal Image" class="max-w-full max-h-full rounded-lg">
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImage').alt = title;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function toggleStatus(galeriId, currentStatus) {
    const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
    const action = newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan';
    
    if (confirm(`Apakah Anda yakin ingin ${action} galeri ini?`)) {
        fetch(`/galeri/${galeriId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status galeri');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
}

function confirmDelete(galeriId, title) {
    if (confirm(`Apakah Anda yakin ingin menghapus galeri "${title}"?`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/galeri/${galeriId}`;
        form.submit();
    }
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
