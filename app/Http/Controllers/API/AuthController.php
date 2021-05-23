<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Register"},
     *      summary="Register",
     *      operationId="register",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass registration credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", example="pankaj"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *  @OA\Response(
     *      response=201,
     *      description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="user",
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Pankaj"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  example="pankaj@gmail.com"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  example="2021-05-22T18:28:18.000000Z"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  example="2021-05-22T18:28:18.000000Z"
     *              )
     *          ),
     *          @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9")
     *     )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *          property="errors",
     *          type="object",
     *          @OA\Property(
     *              property="email",
     *              type="string",
     *              example="The email has already been taken."
     *          )
     *       )
     *     )
     *  ),
     * ),
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Login"},
     *      summary="Login",
     *      operationId="Login",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="user",
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Pankaj"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="email",
     *                  example="pankaj@gmail.com"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  example="2021-05-22T18:28:18.000000Z"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  example="2021-05-22T18:28:18.000000Z"
     *              )
     *          ),
     *          @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9")
     *     )
     *  ),
     *  @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Invalid Credentials"),
     *     )
     *  ),
     * ),
     */
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'],Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
