<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
  use ApiResponseTrait;

  /**
   * Register a new user
   *
   * @param RegisterRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    $validated = $request->validated();
    try {
      if ($request->expectsJson()) {
        $user = User::create($validated);
        return $this->success('Registration Successfully!', $user, 'user', Response::HTTP_OK);
      }
      return $this->error('Data is not valid!!', null, Response::HTTP_UNPROCESSABLE_ENTITY);
    } catch (Throwable $exception) {
      Log::info($exception);
      return $this->error('Something went wrong!', null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * Login a user
   *
   * @param LoginRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    try {

      $request->validated();

      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return $this->error('Invalid Email or Password!', null, Response::HTTP_UNAUTHORIZED);
      }

      $token = $user->createToken($user->email)->plainTextToken;

      return $this->authenticateWithToken($token, $user);
    } catch (Throwable $exception) {
      Log::info($exception);
      return $this->error('Something went wrong!', null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * Logout a user
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(): JsonResponse
  {
    try {
      auth()->guard('api')->logout();
      return $this->success('User logged out successfully!', null, 'user', Response::HTTP_UNAUTHORIZED);
    } catch (Throwable $exception) {
      Log::info($exception);
      return $this->error('Sorry, something went wrong!', null, Response::HTTP_UNAUTHORIZED);
    }
  }

  /**
   * Get authenticated user
   *
   * @return JsonResponse
   */
  public function getAuthenticatedUser(): JsonResponse
  {
    if (!auth()->guard('api')->check()) {
      return $this->error('You are not authorized!!', null, Response::HTTP_UNAUTHORIZED);
    }

    return $this->success(
      '',
      auth()->guard('api')->user(),
      'user',
      Response::HTTP_OK
    );
  }
}
