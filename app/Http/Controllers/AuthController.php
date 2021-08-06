<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="registerUser",
     *      tags={"Auth"},
     *      summary="Register new user",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request looks like",
     *          @OA\JsonContent(
     *              required={"username","email","password"},
     *              @OA\Property(property="name", type="string", format="text", example="User1"),
     *              @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfuly created",
     *       )
     *     )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        return response()->json([
            "status" => true,
            "message" => 'Success created'
        ])->setStatusCode(201);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="loginUser",
     *      tags={"Auth"},
     *      summary="Login",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request looks like",
     *          @OA\JsonContent(
     *              required={"name","password"},
     *              @OA\Property(property="name", type="string", format="text", example="User1"),
     *              @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfuly login",
     *       )
     *     )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => request('name'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        return app()->handle($request);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout",
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfuly login",
     *       )
     *     )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return 'Success logout';
    }

    public function error()
    {
        return response()->json([
            "status" => false,
            "message" => 'unauthorized'
        ])->setStatusCode(400);
    }
}
