<?php

namespace App\Http\Controllers;

use App\Events\MassegeCreated;
use App\Models\Convegration;
use App\Models\Messege;
use App\Models\Recipient;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MassegesController extends Controller
{
    /**
     * عرض الرسائل في محادثة معينة.
     */
    public function index($id)
    {
        $user = User::find(1); //Auth::user();
        $convegration = $user->convegrations()->findOrFail($id);
        return $convegration->messages()->paginate();
    }

    /**
     * تخزين رسالة جديدة.
     */
    public function store(Request $request)
    {
        $request->validate([
            'massege' => 'required|string',
            'convegration_id' => [
                Rule::requiredIf(fn() => !$request->input('user_id')),
                'integer',
                'exists:convegrations,id'
            ],
            'user_id' => [
                Rule::requiredIf(fn() => !$request->input('convegration_id')),
                'integer',
                'exists:users,id'
            ],
        ]);

        $user = User::find(1); //Auth::user();
        $convegration_id = $request->post('convegration_id');
        $user_id = $request->post('user_id');

        DB::beginTransaction();
        try {
            if ($convegration_id) {
                $convegration = $user->convegrations()->findOrFail($convegration_id);
            } else {
                $convegration = Convegration::where('type', 'peer')
                    ->whereHas('participents', function ($query) use ($user_id, $user) {
                        $query->join('participents as participents2', 'participents2.convegration_id', '=', 'participents.convegration_id')
                            ->where('participents.user_id', $user_id)
                            ->where('participents2.user_id', $user->id);
                    })->first();
            }

            if (!$convegration) {
                $convegration = Convegration::create([
                    'user_id' => $user->id,
                    'type' => 'peer'
                ]);
                $convegration->participents()->syncWithoutDetaching([
                    $user->id => ['joined_at' => now(), 'role' => 'rentar'],
                    $user_id => ['joined_at' => now(), 'role' => 'rentar']
                ]);
            }

            $message = $convegration->messages()->create([
                'user_id' => $user->id,
                'body' => $request->post('massege')
            ]);

            foreach ($convegration->participents as $participant) {
                Recipient::create([
                    'user_id' => $participant->id,
                    'massege_id' => $message->id,
                ]);
            }

            $convegration->update(['last_massege_id' => $message->id]);
            DB::commit();

            broadcast(new MassegeCreated($message));


        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json($message, 201);
    }

    /**
     * حذف رسالة معينة.
     */
    public function destroy($id)
    {
        Recipient::where(['user_id' => Auth::id(), 'massege_id' => $id])->delete();
        return response()->json(['message' => 'Massege deleted']);
    }
}
