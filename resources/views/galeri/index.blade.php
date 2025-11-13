@extends('layouts.dashboard')

@section('title', 'Manajemen Galeri - Galeri Edu')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Galeri</h1>
            <p class="text-gray-600 mt-1">Kelola foto dan gambar untuk galeri website</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('galeri.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Upload Foto
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-images text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Galeri</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $galeri->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $galeri->where('status', 'aktif')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-folder text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Foto</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $galeri->sum(function($g) { return $g->fotos ? $g->fotos->count() : 0; }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Galeri Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galeri as $galeriItem)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="aspect-video bg-gray-200 flex items-center justify-center relative">
                    @if($galeriItem->fotos && $galeriItem->fotos->count() > 0)
                        @if($galeriItem->fotos->count() == 1)
                            <!-- Single photo -->
                            <img src="{{ asset('uploads/galeri/' . $galeriItem->fotos->first()->file) }}" 
                                 alt="{{ $galeriItem->post->judul ?? 'Foto' }}"
                                 class="w-full h-full object-cover">
                        @else
                            <!-- Multiple photos grid -->
                            <div class="grid grid-cols-2 grid-rows-2 w-full h-full gap-1">
                                @foreach($galeriItem->fotos->take(4) as $index => $foto)
                                    @if($index < 3)
                                        <div class="relative">
                                            <img src="{{ asset('uploads/galeri/' . $foto->file) }}" 
                                                 alt="{{ $galeriItem->post->judul ?? 'Foto' }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @elseif($index == 3)
                                        <div class="relative bg-black bg-opacity-50 flex items-center justify-center">
                                            <span class="text-white font-bold text-xl">+{{ $galeriItem->fotos->count() - 3 }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @else
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-900 mb-2">
                        {{ $galeriItem->post->judul ?? 'Tanpa Judul' }}
                    </h3>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-tag mr-1"></i>
                        <span>{{ $galeriItem->post->kategori->judul ?? 'Tanpa Kategori' }}</span>
                        
                        <span class="mx-2">•</span>
                        
                        <i class="fas fa-images mr-1"></i>
                        <span>{{ $galeriItem->fotos ? $galeriItem->fotos->count() : 0 }} foto</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $galeriItem->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($galeriItem->status) }}
                        </span>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('galeri.edit', $galeriItem) }}" 
                               class="text-indigo-600 hover:text-indigo-900" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button onclick="confirmDelete({{ $galeriItem->id }}, '{{ $galeriItem->post->judul ?? 'item' }}')" 
                                    class="text-red-600 hover:text-red-900" 
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-images text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium mb-2">Belum ada galeri</h3>
                    <p class="mb-4">Mulai dengan upload foto pertama</p>
                    <a href="{{ route('galeri.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                        <i class="fas fa-plus mr-2"></i>Upload Foto
                    </a>
                </div>
            </div>
        @endforelse
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
// Confirm Delete Function
function confirmDelete(galeriId, title) {
    if (confirm(`Apakah Anda yakin ingin menghapus galeri "${title}"?`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/galeri/${galeriId}`;
        form.submit();
    }
}
</script>
@endsection
