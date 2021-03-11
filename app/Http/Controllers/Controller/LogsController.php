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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\Generator;


// ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// set_time_limit(300);
// ini_set("pcre.backtrack_limit", "5000000");

class LogsController extends Controller
{
    
    public function logs()
    { 
        
            $data['sumr'] = DB::table('sumr')->select('*') ->where('sumr.type', '0')->get();
            $data['issuancemain'] = DB::table('ismn')->select(
                'ismn.*',
                'sumr.seller_name'
                )
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'ismn.issued_to')
                ->where('ismn.is_deleted', 0)
                ->orderBy('issuance_hash', 'desc')
                ->get();

            $data['comr'] = DB::table('comr')->select('azspree')->get();

            $data['sohr'] = SohrModel::select(
                'sohr.*',
                'user.fullname',
                'sumr.*'
                // 'city.*',
                // 'm_city.*',
                // 'odst.*' ,
                // 'regn.*'                       
    )                          
                    // ->leftJoin('regn', 'regn.regn_hash', '=', 'sohr.regn_hash')
                    // ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
                    ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                    ->leftJoin('sumr', 'sohr.sumr_hash', '=', 'sumr.sumr_hash')
                    // ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                    // ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                    ->where('sohr.is_selected' , 0)
                    ->where('sohr.order_stat' , 7)
                    // ->where('sohr.status_user' , 5)
                    ->orderBy('sohr_hash', 'desc')
                    ->get();
    
            return $data;
    }
    public function PrintReport($from_date, $to_date)
    {   
        $data['invoices'] = IssuanceMain::select(
            'ismn.*',
            'isdt.*'
            )

            ->leftJoin('isdt', 'isdt.issuance_hash', '=', 'ismn.issuance_hash')
            ->where('ismn.is_deleted' , 0)
            ->where('ismn.is_paid' , 1)
            ->orderBy('issuance_no', 'asc');

        if($from_date != 0 || $to_date != 0)
        {
            $data['invoices']->whereRaw('DATE(created_datetime) BETWEEN DATE("'.$from_date.'") AND DATE("'.$to_date.'")');
        }
        
        $data['invoices'] = $data['invoices']->get();  

        $data['date_from'] = $from_date;
        $data['date_to'] = $to_date;

        $mpdf = new Mpdf();
        $content = view('logs.report')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        
    }
    
    public function PrintInvoice($id)
    {   
        $data['issuances'] = DB::table('ismn')->select(
            'ismn.*',
            'sumr.*'
            )
        
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'ismn.issued_to')
                ->where('issuance_hash', $id)->get();

        $data['invoices'] = IssuanceDetails::select(
            'isdt.*',
            'sohr.*',
            'soln.*',
            'inmr.*'
            )
                ->leftJoin('sohr', 'sohr.sohr_hash', '=', 'isdt.sohr_hash')
                ->leftJoin('soln', 'soln.sohr_hash', '=', 'sohr.sohr_hash')
                ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'soln.inmr_hash')
                ->where('isdt.issuance_hash' , $id)
                ->orderBy('issuance_details_hash', 'desc');


        $data['details'] = IssuanceDetails::select(
            'isdt.*'
        )
                
                ->where('isdt.issuance_hash' , $id)
                ->orderBy('issuance_details_hash', 'desc');
        
                
        $data['details'] = $data['details']->get();
        $data['invoices'] = $data['invoices']->get();

        $mpdf = new Mpdf();
        $content = view('logs.invoice')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
    }

    // public function PrintReport() {

    //     $data['invoices'] = IssuanceDetails::select(
    //         'isdt.*',
    //         'sohr.*',
    //         'soln.*',
    //         'inmr.*',
    //         'odst.*'
    //         )
    //             ->leftJoin('sohr', 'sohr.sohr_hash', '=', 'isdt.sohr_hash')
    //             ->leftJoin('soln', 'soln.sohr_hash', '=', 'sohr.sohr_hash')
    //             ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'soln.inmr_hash')
    //             ->leftJoin('odst', 'odst.order_hash', '=', 'sohr.order_stat')
    //             // ->where('isdt.issuance_hash' , $id)
    //             ->orderBy('issuance_details_hash', 'desc');
        
        
    //     $data['invoices'] = $data['invoices']->get();

    //     $mpdf = new Mpdf();
    //     $content = view('logs.report')->with($data);
    //     $mpdf->WriteHTML($content);
    //     $mpdf->Output();
    // }
    public function create(Request $request)
    {
        // Validator::make($request->all(),
        //     [
        //         'issued_to' => 'required',
        //     ]
        // )->validate();

        $y = date("Y");

        
        $last_in = IssuanceMain::select('issuance_hash')->max('issuance_hash' );
        $issuance = new IssuanceMain();
        $issuance->issuance_no = 'IN-'. $y .'-'.str_pad($last_in + 1,5,"0",STR_PAD_LEFT);
        // $issuance->issuance_no = DB::raw('CreateIssuanceNo()');
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
                'order_subtotal'=>$item['order_subtotal'],
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
        // Validator::make($request->all(),
        // [
        //     'issued_to' => 'required',
        // ]
        // )->validate();

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
        $data['issuance'] = IssuanceMain::select('ismn.*') ->findOrFail($id);
        
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
        // Validator::make($request->all(),
        // [
        //     'transaction_no' => 'required',
            
        //     'transaction_date' => 'required',

        // ]
        // )->validate();

        $issuance = IssuanceMain::findOrFail($id);
        $issuance->transaction_no = $request->input('transaction_no');
        $issuance->bank_fee = $request->input('bank_fee');
        $issuance->transaction_date = date('Y-m-d', strtotime($request->input('transaction_date')));
        $issuance->is_paid = 1;
        $issuance->update();

        return ( new Reference( $issuance ) )
            ->response()
            ->setStatusCode(200);
    }

    public function printTerms() 
    {
        $mpdf = new Mpdf();
        $content = view('logs.terms');
        $mpdf->WriteHTML($content);
        $mpdf->Output();
    }
    

}