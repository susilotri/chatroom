<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\ChatRoom;

    class DashboardController extends Controller
    {
        public function getStats()
        {
            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            $activeChatRooms = ChatRoom::where('status', true)->count();

            return response()->json([
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'active_chat_rooms' => $activeChatRooms,
            ]);
        }
    }