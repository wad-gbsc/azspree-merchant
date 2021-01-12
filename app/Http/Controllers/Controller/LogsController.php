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
use Hashids\Hashids;

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
            ->where('ismn.is_paid', 0)
            ->orderBy('issuance_hash', 'desc')
            ->get();
            $data['comr'] = DB::table('comr')
            ->select('azspree')
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
                    ->where('sohr.is_selected' , 0)
                    ->orderBy('sohr_hash', 'desc')
                    ->get();
    
            return $data;
    }
    public function PrintReport($id)
    {   
        
        $data['logs'] = IssuanceDetails::select(
                    'isdt.*'                     
        )                          
                        ->where('isdt.issuance_hash' , $id)
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
        $issuance->created_datetime = Carbon::now();
        $issuance->created_by = Auth::user()->sumr_hash;
    
        $issuance->save();
        
        $items = $request->input('items');
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

        $issuance_details_hash = IssuanceDetails::select('sohr_hash')->where('isdt.issuance_hash', $issuance->issuance_hash)
        ->get();
            
        foreach ($issuance_details_hash as $i)
        {
            DB::table('sohr')->where('sohr_hash', $i->sohr_hash)->update(['is_selected' => '1']);
        }
        
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
    public function update(Request $request, $id)
    { 
        Validator::make($request->all(),
        [
            'issued_to' => 'required',
        ]
        )->validate();

        
        $issuance = IssuanceMain::findOrFail($id);
        $issuance->issued_to = $request->input('issued_to');
        $issuance->created_datetime = Carbon::now();
        $issuance->created_by = Auth::user()->sumr_hash;

        $issuance->save();


        $issuance_details_hash = IssuanceDetails::select('sohr_hash')->where('isdt.issuance_hash', $issuance->issuance_hash)
        ->get(); 
    
        foreach ($issuance_details_hash as $i)
        {
            DB::table('sohr')->where('sohr_hash', $i->sohr_hash)->update(['is_selected' => '0']);
        }
        
        $items = $request->input('items');
        $old_items = IssuanceDetails::where('issuance_hash', $issuance->issuance_hash);
        $old_items->delete();

        

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

        $issuance_details_hash = IssuanceDetails::select('sohr_hash')->where('isdt.issuance_hash', $issuance->issuance_hash)
        ->get(); 
    
        foreach ($issuance_details_hash as $i)
        {
            DB::table('sohr')->where('sohr_hash', $i->sohr_hash)->update(['is_selected' => '1']);
        }   
        $data = IssuanceMain::select('ismn.*')
        ->findOrFail($issuance->issuance_hash);

        return ( new Reference( $issuance ) )
            ->response()
            ->setStatusCode(200);
    }

    public function DeleteIssuance($id)
    {   
        $issuance = IssuanceMain::findOrFail($id);

        $issuance->is_deleted = 1;

        //update classification based on the http json body that is sent
        $issuance->save();

        return ( new Reference( $issuance ) )
            ->response()
            ->setStatusCode(200);
    }
    
    public function show($id)
    {
        $data['issuance'] = IssuanceMain::select(
                            'ismn.*'
        )
                            ->findOrFail($id);
        
        $data['issuance_items'] = IssuanceDetails::select(
                                'isdt.*'
        )
                                ->where('is_deleted', 0)
                                ->where('issuance_hash', $id)
                                ->get();
        
        return $data;
    }
    public function MarkPaid(Request $request, $id)
    {   
        $issuance = IssuanceMain::findOrFail($id);
        $issuance->is_paid = 1;
        $issuance->update();

        // $items = $request->input('items');
        // $issuance = IssuanceDetails::where('issuance_hash', $issuance->issuance_hash);

        // $items_dataset = [];
        // foreach($items as $item)
        // {
        //     $items_dataset[] = [
        //         'issuance_hash'=>$issuance->issuance_hash,
        //         'sohr_hash'=>$item['sohr_hash'],
        //         'order_no'=>$item['order_no'],
        //         'seller_name'=>$item['seller_name'],
        //         'order_date'=>$item['order_date'],
        //         'payment_method'=>$item['payment_method'],
        //         'shipping_fee'=>$item['shipping_fee'],
        //         'seller_name'=>$item['seller_name'],
        //         'm_shipping_fee'=>$item['m_shipping_fee'],
        //         'total_qty'=>$item['total_qty'],
        //         'order_total'=>$item['order_total'],
        //         'azspree'=>$item['azspree'],
        //     ];
        // }

        // DB::table('isdt')->insert($items_dataset);

        // $issuance->is_paid = 1;

        // //update classification based on the http json body that is sent
        // $issuance->save();

        return ( new Reference( $issuance ) )
            ->response()
            ->setStatusCode(200);
    }
    

}