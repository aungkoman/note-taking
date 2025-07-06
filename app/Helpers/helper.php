<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

if (!function_exists('get_authenticated_user')) {
    function get_authenticated_user()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $user->id;
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is missing or malformed'], 400);
        }catch (\Exception) {
            return null;
        }
    }

}
