@extends('layouts.dashboard')

@section('title', 'Edit Agenda')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Agenda</h1>
            <p class="text-gray-600 mt-1">Perbarui agenda kegiatan</p>
        </div>
        <a href="{{ route('agenda.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
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
        <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Agenda <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $agenda->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('description', $agenda->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Label Tanggal <span class="text-red-500">*</span></label>
                    <input type="text" name="date_label" value="{{ old('date_label', $agenda->date_label) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Senin, Periode Akhir Semester" required>
                    <p class="text-xs text-gray-500 mt-1">Contoh: Senin, Periode Akhir Semester</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu <span class="text-red-500">*</span></label>
                    <input type="text" name="time" value="{{ old('time', $agenda->time) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="08:00 - 10:00 WIB" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Acara (Opsional)</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $agenda->event_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="aktif" {{ old('status', $agenda->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $agenda->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan Tampilan</label>
                <input type="number" name="order" value="{{ old('order', $agenda->order) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Angka lebih kecil tampil lebih dulu</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('agenda.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
