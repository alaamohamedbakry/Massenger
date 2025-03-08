<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index($id = null)
    {
        $user = User::find(1);//Auth::user();
        $friends = User::where('id', '!=', $user->id)->get();
        $chats = $user->convegrations()->with([
            'lastmessage',
            'participents' => function ($builder) use ($user) {
                $builder->where('user_id', '!=', $user->id);
            }
        ])->get();
        $messages = [];
        $active_chat = null;
        if ($id) {
            $chat = $chats->where('id', $id)->first();
            $messages = $chat->messages()->with('user')->paginate();
        }
        // return $chats;
        return view('massenger', [
            'friends' => $friends,
            'chats' => $chats,
            'active_chat' => $active_chat,
            'messages' => $messages,
        ]);
    }
}
