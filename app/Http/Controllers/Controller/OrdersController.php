<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SohrModel;
use App\Models\SolnModel;
use App\Models\ProductsModel;
use App\Models\UserModel;
use App\Models\SumrModel;
use App\Models\DHSFModel;
use App\Models\DHTFModel;
use App\Models\OrderStatusModel;
use App\Http\Resources\Reference; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use App\User;
use DB;
use Intervention\Image\Point;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sumr($id)
    {
        $sumr = SumrModel::findOrFail($id);

        return ( new Reference( $sumr ) )
        ->response()
        ->setStatusCode(200);
    }

    public function index(Request $request)
    {
            $data['comr'] = DB::table('comr')->select('transfer_fee')->get();
{
            $data['sohr'] = SohrModel::select(
                        'sohr.*',
                        'user.fullname',
                        'sumr.*',
                        'city.city',
                        'm_city.m_city',
                        'odst.*' ,
                        'regn.*'                       
)                          
                            ->leftJoin('regn', 'regn.regn_hash', '=', 'sohr.regn_hash')
                            ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                            ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                            ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                            ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                            ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                            ->where('sohr.sumr_hash' , Auth::user()->sumr_hash)
                            ->orwhere('sohr.where_dh' , Auth::user()->sumr_hash)
                            ->orderBy('sohr_hash', 'desc')
                            ->get();
}
{
                    $data['soln'] = SolnModel::select(
                        'soln.*',
                        'inmr.product_name',
                        'sohr.*'
)
    
                            ->leftJoin('sohr', 'sohr.sohr_hash', '=', 'soln.sohr_hash')
                            ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'soln.inmr_hash')
                            ->orderBy('soln.sohr_hash' , 'desc')
                            ->get();
}
{
                    $data['default'] = SumrModel::select(
                        'sumr.*',
                        'm_city.*'
                        
)
                            // ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'dhsf.sumr_hash')
                            ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                            ->where('sumr.type', '1')
                            ->get();
}

{
                    $data['brgy'] = DB::table('brgy')->select(
                        'brgy.*',
                        'sumr.*'
)
                                        ->leftJoin('sumr', 'sumr.brgy', '=', 'brgy.brgy_hash')
                                        ->where('sumr.brgy', Auth::user()->brgy)
                                            ->get();
}
                
                    return $data;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

   
    public function orders()
{
{
            $data['orders'] = SohrModel::select(
                'sohr.*',
                'user.fullname',
                'sumr.*',
                'odst.*'                        
)                          
                    ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                    ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                    ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
                    ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                    ->where('sohr.sumr_hash', Auth::user()->sumr_hash)
                    ->where('sohr.order_stat', 1)
                    ->orderBy('sohr_hash', 'asc')
                    ->get();
}
{
            $data['cancellations'] = SohrModel::select(
                'sohr.*',
                'user.*',
                'sumr.*',
                'odst.*'                        
)                          
                            ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                            ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                            ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
                            ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                            ->where('sohr.sumr_hash', Auth::user()->sumr_hash)
                            ->where('sohr.order_stat', 9)
                            ->orderBy('sohr_hash', 'asc')
                            ->get();
}
{
            $data['shipments'] = SohrModel::select(
                'sohr.*',
                'user.*',
                'sumr.*',
                'odst.*'                        
)                          
                            ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                            ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                            ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
                            ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                            ->where('sohr.sumr_hash', Auth::user()->sumr_hash)
                            ->where('sohr.order_stat' ,'>=', 2)
                            ->where('sohr.order_stat' ,'<=', 5)
                            ->orderBy('sohr_hash', 'asc')
                            ->get();
}
{
            $data['soln'] = SolnModel::select(
                    'soln.*',
                    'inmr.product_name',
                    'sohr.sohr_hash'
)

                        ->leftJoin('sohr', 'sohr.sohr_hash', '=', 'soln.sohr_hash')
                        ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'soln.inmr_hash')
                        ->orderBy('soln.sohr_hash' , 'desc')
                        ->get();
}
            return $data;
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function logs()
{ 
{
        $data['logs'] = SohrModel::select(
            'sohr.*',
            'user.*',
            'sumr.*',
            'm_city.*',
            'odst.*'                        
)                          
                ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
                ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                ->where('sohr.sumr_hash', Auth::user()->sumr_hash)
                ->orderBy('sohr_hash', 'desc')
                ->get();
}
                return $data;
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = SohrModel::findOrFail($id);

        return ( new Reference( $show ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function AcceptNewOrder(Request $request ,$id)
    {   

        // $letter = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        // $number = substr(str_shuffle("0123456789"), 0, 8);

        $check = SohrModel::select('*')
        ->where('sohr_hash', $id)
        ->where('is_cancel', 1)
        ->get();

        if(count($check) < 1) {
        $sohr = SohrModel::findOrFail($id);
        $sohr->tf_shipping = $request->input('m_shipping_fee');
        $sohr->where_dh = $request->input('selectdhTodeliver');
        $sohr->order_stat = $request->input('selected');
        $sohr->status_user = 2;
        $sohr->accept_datetime = Carbon::now();
        $sohr->to_pick_datetime = date('Y-m-d', strtotime($request->input('to_pick_datetime')));
        $sohr->is_cancel = 0;
        // $sohr->tracking_no = $letter  .$number;
        $sohr->save();
        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(202);
        }else{
            return response()->json(['danger'=>'The Order already cancelled'], 400 );
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DeclineNewOrder(Request $request ,$id)
    {
        $sohr = SohrModel::findOrFail($id);
        $sohr->is_cancel = 1;
        $sohr->order_stat = 9;
        $sohr->status_user = 6;
        $sohr->decline_neworder_remarks = $request->input('decline_neworder_remarks');
        $sohr->decline_neworder_datetime = Carbon::now();
     
        $sohr->save();

        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AcceptIntransit(Request $request ,$id)
    {
        $sohr = SohrModel::findOrFail($id);
        
        $sohr->order_stat = 5;
        $sohr->dh_accept_order_datetime = Carbon::now();
        $sohr->save();
        
        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $refresh = SohrModel::all();

        return (new Reference( $refresh ))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AcceptIntransitPickup(Request $request ,$id)
    {
        $sohr = SohrModel::findOrFail($id);
        
        $sohr->order_stat = 4;
        $sohr->dh_accept_pickup_datetime = Carbon::now();
        $sohr->save();

        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
    }

    public function Todeliver(Request $request ,$id)
    {
        $sohr = SohrModel::findOrFail($id);
        
        $sohr->order_stat = 6;
        $sohr->to_deliver_datetime = Carbon::now();
        $sohr->save();

        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function DeclineIntransit(Request $request ,$id)
    {
        $sohr = SohrModel::findOrFail($id);
        $sohr->is_cancel = 1;
        $sohr->order_stat = 1;
        $sohr->decline_intransit_remarks = $request->input('decline_intransit_remarks');
        $sohr->cancel_by = Auth::user()->seller_name;
        $sohr->decline_intransit_datetime = Carbon::now();
     
        $sohr->save();

        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function Delivered(Request $request ,$id)
    {   
        // $az_pouch = DB::table('comr')->select('az_pouch')->get();
        $sohr = SohrModel::findOrFail($id);
        $sohr->order_stat = 7;
        $points = $sohr->order_total / 100;

        DB::table('user')->where('user_hash', $sohr->user_hash)->increment('az_pouch', $points);
        
        // $user = DB::table('user')->select('*')->where('user_hash',  $sohr->user_hash)->get(); 

        $myorder = SolnModel::select('inmr_hash', 'qty')
                ->where('soln.sohr_hash', $id)
                ->get(); 
            
                foreach ($myorder as $order)
                {
                    DB::table('inmr')->where('inmr_hash', $order->inmr_hash)->increment('sales',$order->qty);
                }
        $sohr->delivered_datetime = Carbon::now();
        $sohr->completed_datetime = date('y:m:d H:i:s', strtotime('+3 days'));
        $sohr->save();
        // sleep(10);
        // if ($sohr->status_user != 5) {
        //     $sohr->status_user = 5;
        //     // DB::table('sohr')->where('sohr_hash', $id)->update(['status_user', 5]);
        // }
        
        return ( new Reference( $sohr ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkIfUsed($id)
    {
        $exists = 'false';

        // if(SohrModel::where('sohr_hash', '=', $id)
        //     ->where('is_cancel', 0)){
        //     $exists = 'true';
        //     }
        return $exists;
    }
  
}
