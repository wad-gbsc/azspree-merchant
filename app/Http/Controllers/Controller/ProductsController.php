<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use App\Models\Category;
use App\Models\SumrModel;
use App\Models\ImagesModel;
use App\Models\VariantModel;
use App\Http\Resources\Reference; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == 0) {
        $data['products'] = ProductsModel::select(
            'inmr.*',
            'prst.*',
            'sumr.shop_name',
            'sumr.seller_name',
            'inct.cat_name',
            'vrnt.*'        
)
                ->leftJoin('prst', 'prst.prst_hash', '=', 'inmr.is_verified')
                ->leftJoin('inct', 'inct.inct_hash', '=', 'inmr.inct_hash')
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'inmr.sumr_hash')
                ->leftJoin('vrnt', 'vrnt.inmr_hash', '=', 'inmr.inmr_hash')
                ->where('inmr.is_deleted', 0)
                ->where('inmr.sumr_hash', Auth::user()->sumr_hash)
                ->orderBy('is_verified', 'asc')
                ->groupBy('vrnt.inmr_hash')
                ->get();
       }else{
        $data['products'] = DB::table('inmr')->select(
            'inmr.*',
            'inct.cat_name',
            'sumr.shop_name',
            'sumr.seller_name',
            'vrnt.*'
)
                
                ->leftJoin('inct', 'inct.inct_hash', '=', 'inmr.inct_hash')
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'inmr.sumr_hash')
                ->leftJoin('vrnt', 'vrnt.inmr_hash', '=', 'inmr.inmr_hash')
                ->where('inmr.is_deleted', 0)
                ->orderBy('is_verified')
                ->groupBy('vrnt.inmr_hash')
                ->get();
       }
        $data['category'] = Category::where('is_deleted', 0)->orderBy('inct_hash')->get();
        $data['sumr'] = SumrModel::where('is_deleted', 0)->where('type', 0)->orderBy('sumr_hash')->get();
        
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
            'product_name' => 'required',
            'inct_hash' => 'required',
            'product_details' => 'required'
            
        ]
        )->setAttributeNames([
            'product_name' => 'Product Name',
            'inct_hash' => 'Category',
            'product_details' => 'Product Details'
            ])->validate();
        
        $products = new ProductsModel();
        $products->product_name = $request->input('product_name');
        $products->co_no = '01';
        $products->image_path = 'Default.jpg';
        $products->product_details = $request->input('product_details');
        $products->inct_hash = $request->input('inct_hash');
        $products->create_datetime = Carbon::now();
        $products->sumr_hash = Auth::user()->sumr_hash;
        $products->save();

        DB::table('inmr')->where('inmr_hash', $products->inmr_hash)->update(['image_path' =>  $products->sumr_hash.'/'.$products->inmr_hash.'/Default.jpg']);
        
        $path = storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$products->inmr_hash;
        File::makeDirectory($path , $mode = 0777, true, true);
        
        $name = "Default.jpg";
        \Image::make($request->image_path)->save(storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$products->inmr_hash.'/'.$name);
        $request->merge(['image_path' => $name]);   


        $items = $request->input('items');
        $items_dataset = [];

        foreach($items as $item)
        {
         

            $items_dataset[] = [
                'inmr_hash'=>$products->inmr_hash,
                'var_name'=> $item['var_name'],
                'onhand_qty'=> $item['onhand_qty'],
                'available_qty' => $item['available_qty'],
                'cost_amt' => $item['cost_amt'],
                'lengthsize' => $item['lengthsize'], 
                'height' => $item['height'],
                'width' => $item['width'], 
                'dimension' => $item['dimension'],
                'weight' => $item['weight'], 
        
            ];
      
        }
        // Validator::make($request->input('items'),
        // [   
        //     $item['var_name'] => 'required',
       
        // ])->validate();
        DB::table('vrnt')->insert($items_dataset);

        // return response()->json([
        //     "message" => "Done"
        // ]);
        return ( new Reference( $products ))
                ->response()
                ->setStatusCode(201);
    }

    public function uploadCreate(Request $request)
    { 
        
            // if (count($request->images)) {
            //     foreach ($request->images as $image) {
            //         $last_id = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->max('inmr_hash');
            //         $last_item = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->where('inmr_hash',$last_id)->max('inmr_hash');
            //         // $file_name = "Defualt.jpg";
            //         $file_name = strtolower(trim($image->getClientOriginalName()));
            //         $image->move('storage/app/public/products/'.Auth::user()->sumr_hash.'/'.$last_item ,$file_name);
            //         $items_dataset[] = [
            //             'inmr_hash' => $last_id,
            //             'path' => $file_name
            //         ];
            //         }
            //         DB::table('imgs')->insert($items_dataset);
            //     }
            if (count($request->images)) {
                $last_id = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->max('inmr_hash');
                $last_item = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->where('inmr_hash',$last_id)->max('inmr_hash');
                // if ($request->images[0]) {
                //     $file_name = "Default.jpg";
                //     $request->images[0]->move('storage/app/public/products/'.Auth::user()->sumr_hash.'/'.$last_item ,$file_name);
                //     DB::table('imgs')->insert(['inmr_hash' => $last_item,'path' => $file_name]);
                // }
                for ($lopp=0; $lopp < count($request->images); $lopp++) {
                    $file_name = strtolower(trim($request->images[$lopp]->getClientOriginalName()));
                    $request->images[$lopp]->move(storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$last_item ,$file_name);
                    DB::table('imgs')->insert(['inmr_hash' => $last_item,'path' => $file_name]);
                }
            }
        return response()->json([
            "message" => "Done"
        ]);
    }
    public function uploadUpdate(Request $request , $id)
    { 
        if (count($request->images)) {
            for ($lopp=0; $lopp < count($request->images); $lopp++) {
                $file_name = strtolower(trim($request->images[$lopp]->getClientOriginalName()));
                $request->images[$lopp]->move('storage/app/public/products/'.Auth::user()->sumr_hash.'/'.$id ,$file_name);
                DB::table('imgs')->insert(['inmr_hash' => $id,'path' => $file_name]);
            }
        
     }
        return response()->json([
            "message" => "Done"
        ]);
    }
    public function show($id)
    {
        $data['products'] = ProductsModel::findOrFail($id);

        $data['variant'] = VariantModel::select('vrnt.*')->where('vrnt.inmr_hash', $id)->where('vrnt.is_deleted', 0)->get();

        $data['images'] = ImagesModel::select('imgs.path')->where('imgs.inmr_hash', $id)->where('imgs.is_deleted', 0)->get();
                
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        
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
        $products = ProductsModel::findOrFail($id);
        
        Validator::make($request->all(),
            [
                'product_name' => 'required',
                'inct_hash' => 'required',
                'product_details' => 'required'
            ]
        )->validate();
        $products->product_name = $request->input('product_name');
        $products->product_details = $request->input('product_details');
        $products->inct_hash = $request->input('inct_hash');
        $products->update_datetime = Carbon::now();
        $products->sumr_hash = Auth::user()->sumr_hash;
        //update  based on the http json body that is sent
        $products->save();

        $items = $request->input('items');
        $old_items = VariantModel::where('inmr_hash', $products->inmr_hash);
        $old_items->delete();

        $items = $request->input('items');
        $items_dataset = [];

        foreach($items as $item)
        {
            $items_dataset[] = [
                'inmr_hash'=>$products->inmr_hash,
                'var_name'=> $item['var_name'],
                'onhand_qty'=> $item['onhand_qty'],
                'available_qty' => $item['available_qty'],
                'cost_amt' => $item['cost_amt'],
                'lengthsize' => $item['lengthsize'], 
                'height' => $item['height'],
                'width' => $item['width'], 
                'dimension' => $item['dimension'],
                'weight' => $item['weight'], 
        
            ];
        }
    
        DB::table('vrnt')->insert($items_dataset);

            if($request->image_path != Auth::user()->sumr_hash.'/'.$id.'/Default.jpg' || $request->image_path == '' ) {
                $DefaultImage = storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$products->inmr_hash.'/Default.jpg';
                @unlink($DefaultImage);
                $name = "Default.jpg";
                \Image::make($request->image_path)->save(storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$products->inmr_hash.'/'.$name);
                $request->merge(['image_path' => $name]);
            }

        return ( new Reference( $products ) )
            ->response()
            ->setStatusCode(200);
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
    public function delete($id)
    {   
        $products = ProductsModel::findOrFail($id);

        $products->is_deleted = 1;

        //update classification based on the http json body that is sent
        $products->save();

        return ( new Reference( $products ) )
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ApproveProduct($id)
    {
        $products = ProductsModel::findOrFail($id);

        $products->is_verified = 1;
        //update classification based on the http json body that is sent
        $products->save();

        return ( new Reference( $products ) )
            ->response()
            ->setStatusCode(200);
    }
    public function DisapproveProduct($id)
    {
        $products = ProductsModel::findOrFail($id);

        $products->is_verified = 2;
        //update classification based on the http json body that is sent
        $products->save();

        return ( new Reference( $products ))
            ->response()
            ->setStatusCode(200);
    }
    public function removeImages($id)
    {   
                $image = DB::table('imgs')->where('inmr_hash', $id)->get();
                
                for ($lopp=0; $lopp < count($image); $lopp++) {
                    $file = $image[$lopp]->path;
                    $filename = storage_path().'/app/public/products/'.Auth::user()->sumr_hash.'/'.$id.'/'.$file;
                    File::delete($filename);
                }
                DB::table('imgs')->where('inmr_hash', $id)->delete();
    
    }

    public function showVariant($id)
    {
        $data['variant'] = VariantModel::findOrFail($id);

        return $data;
    }
}