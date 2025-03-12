<?php

namespace App\Http\Controllers;

use App\Models\Convegration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index($id = null)
    {

        $user = Auth::user();

        $friends = User::where('id', '!=', $user->id)->get();

        $chats = $user->convegrations()->with([
            'lastmessage',
            'participents' => function ($query) use ($user) {
                $query->where('user_id', '!=', $user->id);
            }
        ])->get();

        $messages = [];
        $active_chat = new Convegration();

        if ($id) {
            $chat = $chats->where('id', $id)->first();

            $messages = $chat->messages()->with('user')->paginate(); // ✅ إصلاح مشكلة جلب الرسائل
        }
        return view('massenger', [
            'friends' => $friends,
            'chats' => $chats,
            'active_chat' => $active_chat, // ✅ الآن `active_chat` يحتوي على المحادثة النشطة
            'messages' => $messages,
        ]);
    }

}
