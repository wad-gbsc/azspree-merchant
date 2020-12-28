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
use App\Models\ComrModel;
use App\Models\OrderStatusModel;
use App\Http\Resources\Reference; 
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
                    ->orderBy('sohr_hash', 'desc')
                    ->get();
    }            
            $data['comr'] = DB::table('comr')
            ->select('azspree')
            // ->groupby('co_no')
            ->get();

            return $data;
    }
    public function PrintReport($from_date, $to_date)
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
                    ->orderBy('sohr_hash', 'desc');

        if($from_date != 0 || $to_date != 0)
        {
            $data['logs']->whereRaw('DATE(order_date) BETWEEN DATE("'.$from_date.'") AND DATE("'.$to_date.'")');
        }

        $data['logs'] = $data['logs']->get();

        $data['date_from'] = $from_date;
        $data['date_to'] = $to_date;

        $mpdf = new Mpdf();
        $content = view('logs.logs')->with($data);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
    }

}