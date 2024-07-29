<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::without('teams', 'tasks')->paginate(100));
    }

    public function store(UserStoreRequest $request)
    {
        return response()->json(User::create($request->validated()));
    }

    public function update(UserUpdateRequest $request, User $priority)
    {
        $priority->update($request->validated());
        return response()->json($priority);
    }

    public function destroy(User $priority)
    {
        $priority->delete();
        return response()->json('Procedimento Realizado');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])/',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function updateFirstName(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->save();

        return response()->json(['message' => 'First name updated successfully'], 200);
    }

    //     public function markNotificationComplete(Request $request, $id)
    //     {
    //         $user = $request->user();
    //         $notification = $user->notifications()->where("id", $id)->first();
    //         if ($notification) {
    //             // $notification->update(['read_at' => now()]);
    //             $notification->delete();
    //         }

    //         return response()->json([
    //             "message"=> "success",
    //         ]);
    //     }

    //     public function markAllNotificationComplete(Request $request)
    //     {
    //         $user = $request->user();
    //         $user->unreadNotifications->markAsRead();

    //         return response()->json([
    //             "message"=> "success",
    //         ]);
    //     }

    public function markNotificationComplete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markAllNotificationComplete()
    {
        dd('oi');
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
