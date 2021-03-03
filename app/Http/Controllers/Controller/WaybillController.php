<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SohrModel;
use App\Models\SumrModel;
use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// set_time_limit(300);
// ini_set("pcre.backtrack_limit", "5000000");

class WaybillController extends Controller
{

    public function PrintWaybill($id)
    {   
        $data['distribution'] = SumrModel::select(
            'sumr.*',
            'm_city.*',
            'brgy.*',
            'prov.*'
        )                
                        ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sumr.brgy')
                        ->leftJoin('prov', 'prov.prov_hash', '=', 'sumr.prov_hash')
                        ->where('sumr.type' , 1);

        $data['sumr'] = SohrModel::select(
                'sohr.sumr_hash',
                'sumr.*',
                'm_city.*',
                'brgy.*',
                'prov.*' 
            )                
                            ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                            ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                            ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                            ->leftJoin('prov', 'prov.prov_hash', '=', 'sumr.prov_hash')
                            ->where('sohr.sohr_hash' , $id);

        $data['waybill'] = SohrModel::select(
            'sohr.*',
            'sumr.*',
            'm_city.*',
            'brgy.*',
            'city.*',
            'prov.province'           
        )                
                        ->leftJoin('prov', 'prov.prov_hash', '=', 'sohr.prov_hash')
                        ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                        ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                        ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                        ->where('sohr.sohr_hash' , $id);
        
        
        $data['waybill'] = $data['waybill']->get();
        $data['sumr'] = $data['sumr']->get();
        $data['distribution'] = $data['distribution']->get();    

        $mpdf = new Mpdf();
        
        $mpdf->AddPageByArray([
            'margin-left' => 3,
            'margin-right' => 3,
            'margin-top' => 3,
            'margin-bottom' => .5,
        ]);
        $content = view('logs.waybill')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();

    }
    public function PrintDeliveryForm($id)
    {
        $data['sumr'] = SohrModel::select(
            'sohr.sumr_hash',
            'sumr.*',
            'm_city.*',
            'brgy.*',
            'prov.*' 
        )                
                        ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                        ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                        ->leftJoin('prov', 'prov.prov_hash', '=', 'sumr.prov_hash')
                        ->where('sohr.sohr_hash' , $id);

        $data['sohr'] = SohrModel::select(
            'sohr.*',
            'sumr.*',
            'm_city.*',
            'brgy.*',
            'city.*',
            'prov.province',
            'user.email'       
        )                
                        ->leftJoin('prov', 'prov.prov_hash', '=', 'sohr.prov_hash')
                        ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                        ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.city_hash')
                        ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                        ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                        ->where('sohr.sohr_hash' , $id);
        
        $data['users'] = SohrModel::select(
            'sohr.*',
            'brgy.*',
            'city.*',
            'prov.province',
            'user.*'       
        )                
                        ->leftJoin('prov', 'prov.prov_hash', '=', 'sohr.prov_hash')
                        ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                        ->leftJoin('user', 'user.user_hash', '=', 'sohr.user_hash')
                        ->where('sohr.sohr_hash' , $id);

        $data['soln'] = SohrModel::select(
            'sohr.*',
            'soln.*',
            'inmr.*'
        )                
                        ->leftJoin('soln', 'soln.sohr_hash', '=', 'sohr.sohr_hash')
                        ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'soln.inmr_hash')
                        ->where('sohr.sohr_hash' , $id);

        
        $data['soln'] = $data['soln']->get();
        $data['users'] = $data['users']->get();
        $data['sohr'] = $data['sohr']->get();
        $data['sumr'] = $data['sumr']->get();
            
        $mpdf = new Mpdf();
        $mpdf->AddPageByArray([
            'margin-left' => 3,
            'margin-right' => 3,
            'margin-top' => 3,
            'margin-bottom' => .5,
        ]);
        $delivery2 = view('logs.deliveryform2')->with($data);
        $delivery3 = view('logs.deliveryform3')->with($data);
        $content = view('logs.deliveryform')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="" />');
        $mpdf->WriteHTML($delivery2);
        $mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="" />');
        $mpdf->WriteHTML($delivery3);
        $mpdf->Output();
        

        
    }

}