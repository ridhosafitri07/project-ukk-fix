<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    /**
     * Get all user notifications
     * Used for real-time updates
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $notifications = NotificationService::getUserNotifications();
            $unreadCount = NotificationService::getUnreadCount();

            return response()->json([
                'success' => true,
                'message' => 'Notifications fetched successfully',
                'data' => [
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount,
                    'total' => count($notifications)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get unread count only
     * Lighter endpoint for polling
     */
    public function getUnreadCountOnly(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'count' => 0
                ], 401);
            }

            $count = NotificationService::getUnreadCount();

            return response()->json([
                'success' => true,
                'count' => $count,
                'timestamp' => now()->timestamp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }
}
