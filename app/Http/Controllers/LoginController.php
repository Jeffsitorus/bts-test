<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\AuthTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\BaseHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginController extends Controller
{
    use AuthTrait;

    public function login(SignInRequest $request)
    {
        $credential = $request->email;

        $user = $this->getUser($credential);

        if (!$this->isPasswordMatch($request->password, $user->password)) {
            throw new BaseHttpException(Response::HTTP_UNPROCESSABLE_ENTITY, trans('auth.failed'));
        }

        DB::beginTransaction();
        try {
            $response = $this->loginResponse($user);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json(['data' => $response, 'status' => true, 'message' => 'Login successfully', 'code' => 200], 200);
    }

    public function loginResponse(User $user): array
    {
        $token = $user->createToken($user->email)->accessToken;

        return [
            'email' => $user->email,
            'token' => $token,
            'username' => $user->username,
        ];
    }

    public function isPasswordMatch(string $rawPassword, string $hashedPassword): bool
    {
        return Hash::check($rawPassword, $hashedPassword);
    }

    public function getUser(string $email): User
    {
        try {
            $user = User::query();
            if ($this->isEmail($email)) {
                return $user->where('email', $email)->firstOrFail();
            }
        } catch (ModelNotFoundException $e) {
            throw new BaseHttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'Your account not registered');
        }
    }
}
