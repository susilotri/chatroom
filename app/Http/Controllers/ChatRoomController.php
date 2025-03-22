<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string'
        ]);

        $chatRoom = ChatRoom::create([
            'name' => $request->name,
            'created_by' => $request->user()->id
        ]);
        ChatRoomUser::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Chat room berhasil dibuat.',
            'chat_room' => $chatRoom
        ], 201);
    }

    public function inviteUser(Request $request, $chatRoomId){
        $request ->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        $existingUser = ChatRoomUser::where('chat_room_id', $chatRoomId)
        ->where('user_id', $request->email)
        ->first();

        if ($existingUser) {
            return response()->json(['message' => 'User sudah terdaftar di chat room ini.'], 400);
        }

        $user = User::where('email', $request->email)->first(); 

        ChatRoomUser::create([
            'chat_room_id' => $chatRoomId,
            'user_id' => $user->id,
            'status' => 'invited'
        ]);

        return response()->json([
            'message' => 'User berhasil diundang ke chat room.',
            'chat_room' => $chatRoom
        ]);
    }

    public function respondToInvite(Request $request, $chatRoomId)
    {
        $request->validate([
            'response' => 'required|in:accept,reject',
        ]);

        $chatRoomUser = ChatRoomUser::where('chat_room_id', $chatRoomId)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        if ($request->response === 'accept') {
            $chatRoomUser->status = 'joined';
            $chatRoomUser->save();
            return response()->json(['message' => 'You have joined the chat room']);
        } else {
            return response()->json(['message' => 'You rejected the invite'], 200);
        }
    }

    public function listActiveChatRooms()
    {
        $chatRooms = ChatRoom::where('status', 'active')
            ->withCount('chatRoomUsers')
            ->get();

        return response()->json($chatRooms);
    }

    public function myChatRooms()
    {
        $chatRooms = ChatRoom::whereHas('chatRoomUsers', function ($query) {
            $query->where('user_id', Auth::id())->where('status', 'joined');
        })->get();

        return response()->json($chatRooms);
    }

    public function joinChatRoom($chatRoomId)
    {
        $chatRoom = ChatRoom::where('id', $chatRoomId)->where('status', 'active')->firstOrFail();

        // Cek apakah user sudah join
        $existing = ChatRoomUser::where('chat_room_id', $chatRoomId)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existing) {
            return response()->json(['message' => 'You are already in this chat room'], 400);
        }

        // Masukkan user ke chat room
        ChatRoomUser::create([
            'chat_room_id' => $chatRoomId,
            'user_id' => Auth::id(),
            'status' => 'joined',
        ]);

        return response()->json([
            'message' => 'Joined chat room successfully',
            'chat_room' => $chatRoom
        ]);
    }

    public function getChatRoomDetails($chatRoomId)
    {
        $chatRoom = ChatRoom::with(['chatRoomUsers' => function ($query) {
            $query->where('status', 'joined')->with('user');
        }])->findOrFail($chatRoomId);

        return response()->json($chatRoom);
    }



    public function leave($id){
        $chatRoomUser = ChatRoomUser::where('chat_room_id', $id)
        ->where('user_id', Auth::id())
        ->first();

        if (!$chatRoomUser) {
            return response()->json(['message' => 'Anda belum bergabung di chat room ini.'], 400);
        }

        $chatRoomUser->delete();

        return response()->json([
            'message' => 'Berhasil meninggalkan chat room.'
        ]);
    }



}
