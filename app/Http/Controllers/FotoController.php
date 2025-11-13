<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FotoController extends Controller
{
    /**
     * Toggle like for a photo
     */
    public function toggleLike(Request $request, $id)
    {
        try {
            Log::info('Toggle like request received', [
                'foto_id' => $id,
                'user_id' => $request->session()->getId(),
                'request_data' => $request->all()
            ]);
            
            $foto = Foto::findOrFail($id);
            Log::info('Foto found', ['foto_id' => $foto->id, 'current_likes' => $foto->likes]);
            
            // For this public gallery, we'll use session-based likes
            // In a real application with user authentication, you would use auth()->id()
            $userId = $request->session()->getId();
            Log::info('User ID', ['user_id' => $userId]);
            
            // Check if user already liked this photo
            $existingLike = Like::where('user_id', $userId)
                               ->where('foto_id', $foto->id)
                               ->first();
            
            Log::info('Existing like check', ['existing_like' => $existingLike ? $existingLike->toArray() : null]);
            
            if ($existingLike) {
                // Unlike - remove the like
                $existingLike->delete();
                $foto->decrement('likes');
                $liked = false;
                Log::info('Photo unliked', ['foto_id' => $id, 'user_id' => $userId]);
            } else {
                // Like - add the like
                $like = Like::create([
                    'user_id' => $userId,
                    'foto_id' => $foto->id
                ]);
                Log::info('Like created', ['like' => $like->toArray()]);
                $foto->increment('likes');
                $liked = true;
                Log::info('Photo liked', ['foto_id' => $id, 'user_id' => $userId]);
            }
            
            // Refresh the foto model to get updated likes count
            $foto->refresh();
            
            return response()->json([
                'success' => true,
                'likes' => $foto->likes,
                'liked' => $liked
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling like', [
                'error' => $e->getMessage(),
                'foto_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui like: ' . $e->getMessage()
            ], 500);
        }
    }
}