<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RequestStore;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['answer' => 'url format should be: */api/user/{id}']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Валидация
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|string',
                'password' => 'required|string|confirmed',
            ]
        );

        $data = $request->toArray();

        // Проверка существования пользователя
        $exist = User::where('email', $data['email'])->first();


        if (!empty($exist)) {
            $answer = 'This user exists';
            return response()->json(['answer' => $answer]);
        }

        if ($validate->fails()) {
            $error = $validate->errors();
            return response()->json(['error' => $error]);
        }

        if (empty($exist)) {

            // Создание пользователя
            $user = User::firstOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'api_token' => hash('sha256', Str::random(80)),
                ]
            );
            $answer = 'User created';

            return response()->json(['answer' => $answer, 'api_token' => $user->api_token]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'api_token' => 'required|string',
            ]
        );

        $user = User::find($id);

        if ($user->api_token !== $validate->validate()['api_token']) {
            return response()->json(['error' => 'Invalid IP token']);
        }

        if ($user) {
            return $user->toArray();
        }
        return response()->json(['answer' => 'User not found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Валидация
        $validate = Validator::make(
            $request->all(),
            [
                'api_token' => 'required|string',
                'name' => 'nullable|string',
                'email' => 'nullable|email|string',
            ]
        );

        $data = $request->toArray();
        // Проверка существования пользователя
        $user = User::find($id) ?? false;

        $api_token = $validate->validate()['api_token'];

        if ($user->api_token !== $api_token) {
            return response()->json(['error' => 'Invalid IP token']);
        }
        if (empty($user)) {
            $answer = 'This user not exists';
            return response()->json(['answer' => $answer]);
        }

        if ($validate->fails()) {
            $error = $validate->errors();
            return response()->json(['error' => $error]);
        }


        if ($user && $user->api_token == $api_token) {

            if (!empty($data['name']) && $user->name == $data['name']) {
                unset($data['name']);
            }
            if (!empty($data['email']) && $user->email == $data['email']) {
                unset($data['email']);
            }

            $answer = 'Not updated, the data is the same';
            if (!empty($data)) {
                $user->update($data);
                $answer = 'User updated';
            }

            return response()->json(['answer' => $answer]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'api_token' => 'required|string',
            ]
        );

        $user = User::find($id) ?? false;

        $api_token = $validate->validate()['api_token'];

        if ($user && $user->api_token == $api_token) {
            $user->delete();
            $answer = 'User deleted';
            return response()->json(['answer' => $answer]);
        }

        $answer = 'User not exists';

        return response()->json(['answer' => $answer]);
    }
}