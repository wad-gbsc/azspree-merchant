<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use App\Models\SumrModel;
use Illuminate\Support\Facades\Hash;
use Session;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt(array('is_deleted'=>0, 'email' => $credentials['email'], 'password' => $credentials['password']))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function changepassword(Request $request ,$id)
    // {
    //     $sohr = SohrModel::findOrFail($id);
        
    //     $sohr->order_stat = 4;
    //     $sohr->dh_accept_pickup_datetime = Carbon::now();
    //     $sohr->save();

    //     return ( new Reference( $sohr ) )
    //         ->response()
    //         ->setStatusCode(200);
    // }
    public function changepassword(Request $request ,$id)
    {
        $sumr = SumrModel::findOrFail($id);

     
        $sumr->password = Hash::make($request->input('new_password'));
        $sumr->is_first = 1;
        $sumr->save();
        
        return ( new Reference( $sumr ) )
            ->response()
            ->setStatusCode(200);
       
    }

  
    public function me()
    {
        return response()->json(auth()->user());
    }
     /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        session()->forget('rights');
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();

        // $rights = GroupRights::select('right_code')->where('user_group_id', $user->user_group_id)->get();


        // session()->put('rights', Reference::collection($rights));
        session()->save();
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 6,
            'user' => $user
            // 'rights' => $rights
        ]);
    }
}
