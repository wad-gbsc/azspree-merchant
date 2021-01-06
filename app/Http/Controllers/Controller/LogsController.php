<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SohrModel;
use App\Models\IssuanceMain;
use App\Models\SolnModel;
use App\Models\ProductsModel;
use App\Models\UserModel;
use App\Models\SumrModel;
use App\Models\DHSFModel;
use App\Models\DHTFModel;
use App\Models\ComrModel;
use App\Models\OrderStatusModel;
use App\Http\Resources\Reference;
use App\Models\IssuanceDetails;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Auth;
use App\User;
use Mpdf\Mpdf;

class LOgsController extends Controller
{

    public function logs()
    { 

            $data['sumr'] = DB::table('sumr')
            ->select('*')
            ->where('sumr.type', '0')
            ->get();

            $data['issuancemain'] = DB::table('ismn')
            ->select('*')
            ->where('ismn.is_deleted', 0)
            ->orderBy('issuance_hash', 'desc')
            ->get();

    // {
    //         $data['logs'] = SohrModel::select(
    //             'sohr.*',
    //             'user.*',
    //             'sumr.*',
    //             'm_city.*',
    //             'odst.*'                        
    // )                          
    //                 ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
    //                 ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
    //                 ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
    //                 ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
    //                 ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
    //                 ->orderBy('sohr_hash', 'asc')
    //                 ->get();
    // }            
            $data['comr'] = DB::table('comr')
            ->select('azspree')
            // ->groupby('co_no')
            ->get();
            $data['sohr'] = SohrModel::select(
                'sohr.*',
                'user.fullname',
                'sumr.*',
                'city.*',
                'm_city.*',
                'odst.*' ,
                'regn.*'                       
    )                          
                    ->leftJoin('regn', 'regn.regn_hash', '=', 'sohr.regn_hash')
                    ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                    ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                    ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                    ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                    ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                    // ->where('sohr.sumr_hash' , Auth::user()->sumr_hash)
                    // ->orwhere('sohr.where_dh' , Auth::user()->sumr_hash)
                    ->orderBy('sohr_hash', 'desc')
                    ->get();
    
            return $data;
    }
    public function PrintReport($id)
    {
    //     $data['logs'] = SohrModel::select(
    //             'sohr.*',
    //             'user.*',
    //             'sumr.*',
    //             'm_city.*',
    //             'odst.*'                        
    // )                          
    //                 ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
    //                 ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
    //                 ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
    //                 ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
    //                 ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
    //                 ->orderBy('sohr_hash', 'desc');
        
        $data['logs'] = IssuanceDetails::select(
                    'isdt.*'                     
        )                          
                        // ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                        // ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                        // ->leftJoin('soln', 'soln.soln_hash', '=', 'sohr.sohr_hash')
                        // ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                        // ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                        ->orwhere('isdt.issuance_hash' , $id)
                        ->orderBy('issuance_details_hash', 'desc');

        $data['logs'] = $data['logs']->get();
        $mpdf = new Mpdf();
        $content = view('logs.logs')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
    }


    public function create(Request $request)
    {
        Validator::make($request->all(),
            [
                'issued_to' => 'required',
            ]
        )->validate();

        $issuance = new IssuanceMain();
        $issuance->issuance_no = DB::raw('CreateIssuanceNo()');
        $issuance->issued_to = $request->input('issued_to');
        // $issuance->issuance_datetime = date('Y-m-d H:i:s', strtotime($request->input('issuance_datetime')));
        // $issuance->issuance_remarks = $request->input('issuance_remarks');
        // $issuance->total_amount = $request->input('total_amount');
        $issuance->created_datetime = Carbon::now();
        $issuance->created_by = Auth::user()->sumr_hash;
    
        $issuance->save();
        
        $items = $request->input('items');
        
        // // //first way
        // foreach($items as $item)
        // {
        //     $purchase_item = new PurchaseOrderItems;
        //     $purchase_item->purchase_order_id = $purchase->purchase_order_id;
        //     $purchase_item->product_id = $item['product_id'];
        //     $purchase_item->product_cost = $item['product_cost'];
        //     $purchase_item->save();
        // }

        //second way
        $items_dataset = [];
        foreach($items as $item)
        {
            $items_dataset[] = [
                'issuance_hash'=>$issuance->issuance_hash,
                'sohr_hash'=>$item['sohr_hash'],
                'order_no'=>$item['order_no'],
                'seller_name'=>$item['seller_name'],
                'order_date'=>$item['order_date'],
                'payment_method'=>$item['payment_method'],
                'shipping_fee'=>$item['shipping_fee'],
                'seller_name'=>$item['seller_name'],
                'm_shipping_fee'=>$item['m_shipping_fee'],
                'total_qty'=>$item['total_qty'],
                'order_total'=>$item['order_total'],
                'azspree'=>$item['azspree'],
                
            ];
        }

        DB::table('isdt')->insert($items_dataset);

        // //return json based from the resource data 
        // $data['issuancemain'] = DB::table('ismn')
        // ->select('*')
        // ->where('ismn.is_deleted', 0)
        // ->findOrFail($issuance->issuance_hash);

        $data = IssuanceMain::select('ismn.*')
        ->findOrFail($issuance->issuance_hash);

        return ( new Reference( $data ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

}