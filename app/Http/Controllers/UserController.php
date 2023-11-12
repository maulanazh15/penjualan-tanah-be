<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFileUploadHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();

        return $this->success($users);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        // $messages = $validator->messages()->toJson();

        // dd($messages);
        if ($validator->fails()) {
            return $this->error('Register user failed. ', 400, $validator->messages());
        }
        $user = User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken('api-token-user-' . $user->id)->plainTextToken;
        // $request->session()->regenerate();

        // $csrf_token = csrf_token();

        return $this->success([
            'token' => $token,
            // 'X-CSRF-TOKEN' =>  $csrf_token,
            'user' => new UserResource($user),
        ], "User Login Successfully");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('username', $request->input('username'))->first();
            $token = $user->createToken('api-token-' . $user->id)->plainTextToken;
            
            // $request->session()->regenerate();

            // $csrf_token = csrf_token();

            return $this->success([
                'token' => $token,
                // 'X-CSRF-TOKEN' =>   $csrf_token,
                'user' => new UserResource($user),
            ], "User Login Successfully");
        }

        return $this->error('Username atau password mungkin salah');
    }

    public function get(Request $request)
    {
        // The authenticated user
        if ($request->user()) {
            return $this->success([
                'user' => new UserResource($request->user())
            ], 'Get current user success', 200);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
            return $this->success(null, 'User logged out successfully');
        }
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('User not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'unique:users,username,' . $user->id,
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            // 'password' => 'sometimes|required|confirmed',
            'photo_profile' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('Update user failed.', 400, $validator->messages());
        }
        // dd($request->all());
        $user->username = $request->input('username') ?? $user->username;
        $user->name = $request->input('name') ?? $user->name;
        $user->email = $request->input('email') ?? $user->email;

        // if ($request->has('password')) {
        //     $user->password = Hash::make($request->input('password'));
        // }

        if ($request->hasFile('photo_profile')) {
            $user->photo_profile = ModelFileUploadHelper::modelFileUpdate($user, 'photo_profile', $request->file('photo_profile'));
        }

        $user->save();

        return $this->success(new UserResource($user), 'User data has been updated successfully', 200);
    }

    public function getPhotoProfile(Request $request)
    {

        $data = $request->validate(
           [ 
            'user_id' => 'required',
            ]
        );

        $user = User::where('id', $data['user_id'])->first();

        // dd($user);
        $path = '/public/users/photo-profile/'.($user->photo_profile ?? 'go-you-jung.jpg');
        // $path = $user->photo_profile ?? 'go-you-jung.jpg';
        // dd($path);
        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $mimeType = Storage::mimeType($path);

            return response($file, 200, [
                'Content-Type' => $mimeType,
                'Connection' => 'keep-alive',
            ]);
        } 

        return $this->error('User photo profile not found', 404);
    }

    // public function pusherAuth(Request $request){
    //     $key = env('PUSHER_APP_KEY');
    //     $secret = env('PUSHER_APP_SECRET');
    //     $channel_name = $request->channel_name;
    //     $socket_id = $request->socket_id;
    //     $string_to_sign = $socket_id.':'.$channel_name;
    //     $signature = hash_hmac('sha256', $string_to_sign, $secret);
    //     return response()->json(['auth' => $key.':'.$signature]);
    // }
}
