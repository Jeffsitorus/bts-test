<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function handle(Request $request)
    {
        //insert user
        $input = $request->all();
        if ($request->password) {
            $input['password'] = Hash::make($input['password']);
        }
        $user = User::create($input);

        //data for response
        $data['email']      = $user->email;
        $data['token']      = $user->createToken($user->email)->accessToken;
        $data['username']   = $user->username;

        return response()->json(['data' => $data, 'message' => 'Account registered successfully!', 'status' => 'success']);
    }
}
