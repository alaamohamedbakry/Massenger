<?php

namespace App\Http\Controllers;

use App\Models\Convegration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConvegrationController extends Controller
{
    public function index(){
        $user=auth()->user();
        return $user->convegrations()->paginate();
    }

    public function show(Convegration $convegration){
        return response()->json($convegration->load('participents')->toArray());

    }

    public function addParticipants(Request $request, Convegration $convegration)
{
    // التحقق من أن user_id موجود في جدول users
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    // إضافة المستخدم إلى المحادثة
    $convegration->participents()->attach($request->user_id, [
        'joined_at' => now(), // يمكن استخدام Carbon::now() أيضًا
    ]);

    return response()->json(['message' => 'Participant added successfully']);
}
public function removeparticipants(Request $request, Convegration $convegration)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    // التأكد من أن المستخدم مشارك بالفعل قبل الحذف
    if (!$convegration->participents()->where('user_id', $request->user_id)->exists()) {
        return response()->json(['message' => 'User is not a participant'], 404);
    }

    // حذف المشارك
    $convegration->participents()->detach($request->user_id);

    return response()->json(['message' => 'Participant removed successfully']);
}

}
