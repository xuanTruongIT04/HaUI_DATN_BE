<?php

namespace App\Http\Controllers\Users;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;

use App\Http\Requests\Users\Auth\RegisterRequest;
use App\Http\Requests\Users\Auth\LoginRequest;
use App\Http\Requests\Users\Auth\ResetPasswordRequest;
use App\Http\Requests\Users\Auth\ChangePasswordRequest;
use App\Http\Requests\Users\Auth\ForgetPasswordRequest;
use App\Http\Requests\Users\Auth\VerificationRequest;
use App\Jobs\SendResetPasswordJob;
use App\Jobs\VerificationAccountJob;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\PasswordReset;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            "username" => $request->username,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        if ($user) {
            return response()->json([$user, "status" => true]);
        } else {
            return response()->json(["status" => false]);
        }

    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::guard("user")->attempt($credentials);
        if (!$token) {
            return response()->json([
                'errors' => [
                    'password' => ['Wrong password, please try again!']
                ]
            ], 401);
        }
        $user = Auth::guard("user")->user();
        // Kiểm tra user tồn tại và trạng thái user
        $statusUser = array_keys(CONSTANT::STATUS_USER);
        if (!$user || $user->status !== $statusUser[0]) {
            return response()->json([
                'errors' => [
                    'account' => ['User not authenticated or found, please try again!']
                ]
            ], 401);
        }

        return $this->respondWithToken($token);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::guard("user")->user();
        if (!$user) {
            return response()->json([
                'error' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'user' => $user,
            'status' => true
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard("user")->factory()->getTTL() * 60
        ]);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->sendErrorResponse('User not found');
        }

        $token = Str::random(60);

        PasswordReset::updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetUrl = env("APP_FRONT_URL") . 'reset-password/' . $token;
        dispatch(new SendResetPasswordJob($email, $resetUrl));
        return $this->sendSuccessResponse('Password reset email has been sent to your email,
         please check within 30 minutes to change your password');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $password = $request->password;
        $token = $request->token;
        $resetRecord = PasswordReset::where('token', $token)->first();
        if (!$resetRecord)
            return $this->sendErrorResponse('Invalid token');

        $hashedPassword = Hash::make($password);

        User::where('email', $resetRecord->email)
            ->update(['password' => $hashedPassword]);

        PasswordReset::where('token', $token)
            ->delete();

        return $this->sendSuccessResponse('Password reset successful');
    }

    public function checkToken(Request $request)
    {
        $token = $request->input('token');
        $validToken = PasswordReset::where('token', $token)->first();

        if (!$validToken) {
            return response()->json(false);
        } else {
            $createdAt = strtotime($validToken->created_at);
            $currentTime = time();
            $timeDifference = ($currentTime - $createdAt) / 60;
            if ($timeDifference > 30) {
                return response()->json('expired');
            } else {
                return response()->json($validToken);
            }
        }
    }
    /**
     * reset password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::guard("user")->user();

        // Check if the current password matches the user's password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'status' => 401, 'title' => "Current password is incorrect"]);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->sendSuccessResponse('Password changed successfully');
    }

    public function verificationSend(VerificationRequest $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->sendErrorResponse('User not found');
        }

        $token = Str::random(60);

        PasswordReset::updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
        if ($user)
            $idUser = $user->id;
        $resetUrl = env("APP_FRONT_URL") . 'verify-account/' . $idUser . "/" . $token;
        dispatch(new VerificationAccountJob($email, $resetUrl));
        return $this->sendSuccessResponse('Link verification account has been sent to your email,
         please check within 30 minutes to verification');
    }

    public function verificationGet(Request $request)
    {
        $idUser = $request->idUser;
        $statusUser = array_keys(CONSTANT::STATUS_USER);

        $dataUpdate = [
            "email_verified_at" => now(),
            "status" => $statusUser[0]
        ];

        $user = User::where('id', $idUser)
            ->update($dataUpdate);

        return $this->sendSuccessResponse(['user' => $user]);
    }

    public function logout()
    {
        try {
            Auth::guard('user')->logout();
            return $this->sendSuccessResponse(['logout' => true]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }


}