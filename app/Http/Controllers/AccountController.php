<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($user->id)
            ],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = Auth::user();
        $email = $request->input('email');

        // ensure user requests for their own account
        if ($email !== $user->email) {
            return response()->json(['success' => false, 'message' => 'Email mismatch'], 422);
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => __($status)], 500);
    }
}
