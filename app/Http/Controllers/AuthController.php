<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use App\Models\SumrModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Session;
use DB; 

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

    public function updateProfile(Request $request)
    {
    
        $sumr = SumrModel::findOrFail(Auth::user()->sumr_hash);

        Validator::make($request->all(),
        [
            'shop_name' => 'required',
            'seller_name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'prov_hash' => 'required',
            'city_hash' => 'required',
            'brgy' => 'required'
        ]
        )->setAttributeNames([
            'shop_name' => 'Shop Name',
            'seller_name' => 'Your Name',
            'email' => 'Email',
            'contact' => 'Contact',
            'prov_hash' => 'Province',
            'city_hash' => 'City',
            'brgy' => 'Barangay'
            ])->validate();

            $sumr->shop_name = $request->input('shop_name');
            $sumr->seller_name = $request->input('seller_name');
            $sumr->email = $request->input('email');
            $sumr->contact = $request->input('contact');
            $sumr->prov_hash = $request->input('prov_hash');
            $sumr->city_hash = $request->input('city_hash');
            $sumr->brgy = $request->input('brgy');
            $sumr->sumr_address = $request->input('sumr_address');
            $sumr->update_datetime = Carbon::now();
            $sumr->save();
    
        $currentPhoto = Auth::user()->photo;
        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('/images/profile/').$name);
            $request->merge(['photo' => $name]);
            
            $userPhoto = public_path('images/profile/').$currentPhoto;
            if (Auth::user()->photo != "default.png") {
                @unlink($userPhoto);
            }
          $sumr = DB::table('sumr')->where('sumr_hash', Auth::user()->sumr_hash)->update(['photo' => $name]);
         
        }

        $currentCover = Auth::user()->cover;
        if($request->cover != $currentCover){
            $nameCover = time().'.' . explode('/', explode(':', substr($request->cover, 0, strpos($request->cover, ';')))[1])[1];

            \Image::make($request->cover)->save(public_path('/images/cover/').$nameCover);
            $request->merge(['cover' => $nameCover]);

            
            $userCover = public_path('images/cover/').$currentCover;

            if (Auth::user()->cover != "default.jpg") {
                @unlink($userCover);
            }

          $sumr = DB::table('sumr')->where('sumr_hash', Auth::user()->sumr_hash)->update(['cover' => $nameCover]);
         
        }
        return ['message' => "Success"];
    }

    public function ProfileInfo() 
    {
        $data['sumr'] = SumrModel::select(
            'sumr.*',
            'prov.province',
            'm_city.m_city',
            'brgy.barangay'
                        
        )
                ->leftJoin('prov', 'prov.prov_hash', '=', 'sumr.prov_hash')
                ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sumr.brgy')
                ->where('sumr.sumr_hash', Auth::user()->sumr_hash)
                ->findOrFail(Auth::user()->sumr_hash);


        $data['prov'] = DB::table('prov')->get(); 
        $data['m_city'] = DB::table('m_city')->get();    
        $data['brgy'] = DB::table('brgy')->get();    
        return $data;
    }
}
