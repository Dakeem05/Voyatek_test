<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class AuthenticatedSessionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('BlogApp')->accessToken;

            return $this->successResponse(['token' => $token], 'Login was successful.');
                    
        } else{
            return $this->unauthorizedResponse();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy()
    {
        if (auth()->guard('api')->check()) {
            if ($this->deleteUserToken(auth()->guard('api')->user())) {
                return $this->successResponse(null,'User logged out successfully');
            }
            else {
                return $this->errorResponse('User not logged out');
            }
        }

        return $this->unauthorizedResponse('Unauthorized action');
    }

    public function getUser()
    {
        $user = User::where('id', auth()->user()->id)->first();
        if ($user) {
            return $this->successResponse($user, 'User data', 200);
        } else {
            return $this->unauthorizedResponse('Unauthorized');
        }
    }
    
    public function deleteUserToken(\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        try {
            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            $user_tokens = $user->tokens;

            foreach ($user_tokens as $token) {
                $tokenRepository->revokeAccessToken($token->id);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

                DB::table('oauth_access_tokens')->where('id', $token->id)->delete();
            }

            return true;
        }
        catch (\Exception $e) {
            Log::channel('site_issues')->error($e->getMessage());

            return false;
        }
    }
}
