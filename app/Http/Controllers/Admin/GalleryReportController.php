<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Foto;
use App\Models\Post;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GalleryReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategoriId = $request->input('kategori_id');
        $status = $request->input('status');

        // Build query
        $query = Galery::with(['post.kategori', 'fotos']);

        // Apply filters
        if ($startDate && $endDate) {
            $query->whereHas('post', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        if ($kategoriId) {
            $query->whereHas('post', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $galeries = $query->get();

        // Calculate statistics
        $statistics = $this->calculateStatistics($galeries);

        // Get all categories for filter
        $categories = Kategori::all();

        return view('admin.reports.galeri', compact('galeries', 'statistics', 'categories', 'startDate', 'endDate', 'kategoriId', 'status'));
    }

    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategoriId = $request->input('kategori_id');
        $status = $request->input('status');

        // Build query
        $query = Galery::with(['post.kategori', 'fotos']);

        // Apply filters
        if ($startDate && $endDate) {
            $query->whereHas('post', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        if ($kategoriId) {
            $query->whereHas('post', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $galeries = $query->get();

        // Calculate statistics
        $statistics = $this->calculateStatistics($galeries);

        // Get category name if filtered
        $categoryName = null;
        if ($kategoriId) {
            $category = Kategori::find($kategoriId);
            $categoryName = $category ? $category->judul : null;
        }

        // Prepare data for PDF
        $data = [
            'galeries' => $galeries,
            'statistics' => $statistics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryName' => $categoryName,
            'status' => $status,
            'generatedDate' => Carbon::now()->format('d F Y H:i'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.reports.galeri-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'laporan-galeri-' . Carbon::now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    private function calculateStatistics($galeries)
    {
        $totalGaleries = $galeries->count();
        $totalPhotos = 0;
        $totalAktif = 0;
        $totalNonaktif = 0;
        $categoriesCount = [];

        foreach ($galeries as $galeri) {
            // Count photos
            $totalPhotos += $galeri->fotos->count();

            // Count by status
            if ($galeri->status === 'aktif') {
                $totalAktif++;
            } else {
                $totalNonaktif++;
            }

            // Count by category
            if ($galeri->post && $galeri->post->kategori) {
                $categoryName = $galeri->post->kategori->judul;
                if (!isset($categoriesCount[$categoryName])) {
                    $categoriesCount[$categoryName] = 0;
                }
                $categoriesCount[$categoryName]++;
            }
        }

        // Sort categories by count
        arsort($categoriesCount);

        return [
            'total_galeries' => $totalGaleries,
            'total_photos' => $totalPhotos,
            'total_aktif' => $totalAktif,
            'total_nonaktif' => $totalNonaktif,
            'avg_photos_per_gallery' => $totalGaleries > 0 ? round($totalPhotos / $totalGaleries, 2) : 0,
            'categories_count' => $categoriesCount,
            'most_popular_category' => !empty($categoriesCount) ? array_key_first($categoriesCount) : null,
        ];
    }
}
