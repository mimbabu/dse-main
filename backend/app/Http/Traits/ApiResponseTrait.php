<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
  /**
   * Send a success response.
   *
   * @param  string  $message
   * @param  array  $data
   * @param  string  $data_key
   * @param  int  $status_code
   * @return \Illuminate\Http\JsonResponse
   */
  protected function success(string $message, $data = [], string $data_key, int $status_code): JsonResponse
  {
    return response()->json([
      'isSuccess' => true,
      'message' => $message,
      'data' => [$data_key => $data]
    ], $status_code);
  }

  /**
   * Send a error response.
   *
   * @param  string  $message
   * @param  array  $data
   * @param  int  $status_code
   * @return \Illuminate\Http\JsonResponse
   */
  protected function error(string $message, $data = [], int $status_code): JsonResponse
  {
    return response()->json([
      'isSuccess' => false,
      'error' => $message,
      'data' => $data
    ], $status_code);
  }

  /**
   * Send a error response.
   *
   * @param  string  $token
   * @param  object  $user
   * @return \Illuminate\Http\JsonResponse
   */
  protected function authenticateWithToken(string $token, object $user): JsonResponse
  {
    return response()->json([
      'isSuccess' => true,
      'message' => 'Authentication successful',
      'data' => ['user' => $user, "token" => $token]
    ], Response::HTTP_OK);
  }
}
