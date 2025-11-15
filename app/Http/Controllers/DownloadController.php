<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

/**
 * Download Controller
 * 
 * Features:
 * - Captcha verification before download (simple math question)
 * - Rate limiting: 5 downloads per IP per hour
 * - Download token expires in 5 minutes
 * - No login required for public gallery downloads
 */
class DownloadController extends Controller
{
    /**
     * Verify captcha and prepare download
     */
    public function verifyCaptcha(Request $request)
    {
        $request->validate([
            'captcha_answer' => 'required|numeric',
            'captcha_session' => 'required',
            'file_path' => 'required'
        ]);

        // Get captcha data from cache
        $captchaData = Cache::get('captcha_' . $request->captcha_session);
        
        if (!$captchaData) {
            return response()->json([
                'success' => false,
                'message' => 'Captcha expired. Please refresh.'
            ], 400);
        }

        // Verify answer
        if ((int)$request->captcha_answer !== (int)$captchaData['answer']) {
            return response()->json([
                'success' => false,
                'message' => 'Captcha salah. Silakan coba lagi.'
            ], 400);
        }

        // Check rate limit (max 5 downloads per IP per hour)
        $ip = $request->ip();
        $downloadKey = 'download_count_' . $ip;
        $downloadCount = Cache::get($downloadKey, 0);

        if ($downloadCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Batas download tercapai. Silakan coba lagi dalam 1 jam.'
            ], 429);
        }

        // Increment download count
        Cache::put($downloadKey, $downloadCount + 1, now()->addHour());

        // Clear captcha
        Cache::forget('captcha_' . $request->captcha_session);

        // Generate download token
        $downloadToken = bin2hex(random_bytes(32));
        Cache::put('download_token_' . $downloadToken, $request->file_path, now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'download_token' => $downloadToken
        ]);
    }

    /**
     * Download file with token
     */
    public function download(Request $request, $token)
    {
        Log::info('Download request received', [
            'token' => $token,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $filePath = Cache::get('download_token_' . $token);

        if (!$filePath) {
            Log::warning('Download token not found or expired', ['token' => $token]);
            abort(404, 'Download link expired or invalid');
        }

        $fullPath = public_path('uploads/galeri/' . $filePath);

        Log::info('Attempting to download file', [
            'file_path' => $filePath,
            'full_path' => $fullPath,
            'file_exists' => file_exists($fullPath)
        ]);

        if (!file_exists($fullPath)) {
            Log::error('File not found', ['full_path' => $fullPath]);
            abort(404, 'File not found');
        }

        // Delete token after use
        Cache::forget('download_token_' . $token);

        Log::info('File download successful', ['file_path' => $filePath]);
        
        // Set headers for download
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($fullPath) . '"',
            'Content-Length' => filesize($fullPath),
        ];
        
        return Response::download($fullPath, basename($fullPath), $headers);
    }

    /**
     * Generate captcha
     */
    public function generateCaptcha(Request $request)
    {
        try {
            $num1 = rand(1, 10);
            $num2 = rand(1, 10);
            $answer = $num1 + $num2;
            
            $sessionId = bin2hex(random_bytes(16));
            
            // Store captcha in cache for 5 minutes
            Cache::put('captcha_' . $sessionId, [
                'answer' => $answer,
                'created_at' => now()
            ], now()->addMinutes(5));

            Log::info('Captcha generated', [
                'session_id' => $sessionId,
                'question' => "$num1 + $num2",
                'answer' => $answer
            ]);

            return response()->json([
                'success' => true,
                'question' => "$num1 + $num2 = ?",
                'session_id' => $sessionId
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating captcha: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat captcha. Silakan coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}