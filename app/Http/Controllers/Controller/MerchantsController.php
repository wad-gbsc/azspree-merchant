<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use App\Models\SumrModel;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MerchantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['merchants'] = SumrModel::where('is_deleted', 0)->orderBy('sumr_hash', 'desc')->get();
        
        $data['prov'] = DB::table('prov')->get(); 
        $data['m_city'] = DB::table('m_city')->get();    
        $data['brgy'] = DB::table('brgy')->get();

        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
            'seller_name' => 'Merchant Name',
            'email' => 'Email Address',
            'contact' => 'Contact No.',
            'prov_hash' => 'Province',
            'city_hash' => 'City',
            'brgy' => 'Barangay'
            ])->validate();


        $merchant = new SumrModel();
        $merchant->shop_name = $request->input('shop_name');
        $merchant->seller_name = $request->input('seller_name');
        $merchant->email = $request->input('email');
        $merchant->contact = $request->input('contact');
        $merchant->password =  Hash::make("123456");
        $merchant->prov_hash = $request->input('prov_hash');
        $merchant->city_hash = $request->input('city_hash');
        $merchant->brgy = $request->input('brgy');
        $merchant->sumr_address = $request->input('sumr_address');
        $merchant->type = 0;
        $merchant->photo = "default.png";
        $merchant->cover = "default.jpg";
        $merchant->create_datetime = Carbon::now();
        $merchant->save();
        
        //return json based from the resource data
        return ( new Reference( $merchant ))
                ->response()
                ->setStatusCode(201);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $merchant = SumrModel::findOrFail($id);

        return ( new Reference( $merchant ) )
            ->response()
            ->setStatusCode(200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {   
        $merchant = SumrModel::findOrFail($id);
        $merchant->is_deleted = 1;
        //update classification based on the http json body that is sent
        $merchant->save();

        return ( new Reference( $merchant ) )
            ->response()
            ->setStatusCode(200);
    }
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}