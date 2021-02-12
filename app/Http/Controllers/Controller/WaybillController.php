<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SohrModel;
use App\Models\SumrModel;
use Mpdf\Mpdf;
// use SimpleSoftwareIO\QrCode\Generator;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
set_time_limit(300);
ini_set("pcre.backtrack_limit", "5000000");

class WaybillController extends Controller
{

    public function PrintWaybill($id)
    {   
            $data['sumr'] = SohrModel::select(
                'sohr.sumr_hash',
                'sumr.*',
                'm_city.*',
                'brgy.*',
                'prov.*' 
            )                
                            ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'sohr.sumr_hash')
                            ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
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
                        ->leftJoin('m_city', 'm_city.city_hash', '=', 'sumr.m_city')
                        ->leftJoin('city', 'city.city_hash', '=', 'sohr.city_hash')
                        ->leftJoin('brgy', 'brgy.brgy_hash', '=', 'sohr.brgy_hash')
                        ->where('sohr.sohr_hash' , $id);
        
        $data['waybill'] = $data['waybill']->get();
        $data['sumr'] = $data['sumr']->get();

        // $qrcode = new Generator;
        // $data['qr'] = $qrcode->size(100)->generate('ORDER NO.');

        // $qrcode = new Generator;
        // $data['qr'] = $qrcode->size(100)->generate($qr);
        // $qrcode = QrCode::size(130)->generate('Make a qrcode without Laravel!');
            
        $mpdf = new \Mpdf\Mpdf();
        $content = view('logs.waybill')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();

    }


}