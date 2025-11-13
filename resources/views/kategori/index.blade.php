@extends('layouts.dashboard')

@section('title', 'Kategori')

@section('content')
<!-- Header Section -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
            <p class="text-gray-600 mt-1">Kelola kategori foto untuk organisasi galeri yang lebih baik</p>
        </div>
        <a href="{{ route('kategori.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center space-x-2 shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-folder text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                <p class="text-2xl font-bold text-gray-900">{{ $kategori->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Kategori Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $kategori->where('status', 'aktif')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-images text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Foto</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalFotos }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Kategori</h2>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari kategori..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="kategoriTableBody">
                @forelse($kategori as $index => $kategori)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $kategori->judul }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $kategori->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('kategori.edit', $kategori->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="openDeleteModal({{ $kategori->id }}, '{{ $kategori->judul }}')" 
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center space-y-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-folder text-gray-400 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Belum ada kategori</h3>
                                <p class="text-gray-500">Mulai dengan membuat kategori pertama Anda</p>
                            </div>
                            <a href="{{ route('kategori.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Buat Kategori Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="kategoriModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Kategori</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="kategoriForm" method="POST" action="{{ route('kategori.store') }}" class="p-6">
                @csrf
                <input type="hidden" id="kategoriId" name="kategori_id">
                <input type="hidden" name="_method" id="methodField" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" id="judul" name="judul" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan nama kategori">
                    </div>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                        <p class="text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus kategori <strong id="deleteKategoriName"></strong>?</p>
                
                <form id="deleteForm" method="POST" class="flex items-center justify-end space-x-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#kategoriTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value.toLowerCase();
    const rows = document.querySelectorAll('#kategoriTableBody tr');
    
    rows.forEach(row => {
        if (!status) {
            row.style.display = '';
            return;
        }
        
        const statusCell = row.querySelector('td:nth-child(4) span');
        if (statusCell) {
            const rowStatus = statusCell.textContent.toLowerCase();
            row.style.display = rowStatus.includes(status) ? '' : 'none';
        }
    });
});

// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('kategoriForm').reset();
    document.getElementById('kategoriId').value = '';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('kategoriForm').action = '{{ route("kategori.store") }}';
    document.getElementById('kategoriModal').classList.remove('hidden');
}

function openEditModal(id, judul, deskripsi) {
    document.getElementById('modalTitle').textContent = 'Edit Kategori';
    document.getElementById('kategoriId').value = id;
    document.getElementById('judul').value = judul;
    document.getElementById('deskripsi').value = deskripsi;
    document.getElementById('methodField').value = 'PUT';
    document.getElementById('kategoriForm').action = `/kategori/${id}`;
    document.getElementById('kategoriModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('kategoriModal').classList.add('hidden');
}

function openDeleteModal(id, judul) {
    document.getElementById('deleteKategoriName').textContent = judul;
    document.getElementById('deleteForm').action = `/kategori/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function toggleStatus(id) {
    // Implement status toggle functionality
    console.log('Toggle status for kategori:', id);
}

// Close modal when clicking outside
document.getElementById('kategoriModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
