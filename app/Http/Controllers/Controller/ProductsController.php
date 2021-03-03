<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use App\Models\Category;
use App\Models\SumrModel;
use App\Models\ImagesModel;
use App\Http\Resources\Reference; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Storage;
use DB;

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
            'inct.cat_name'          
)
                ->leftJoin('prst', 'prst.prst_hash', '=', 'inmr.is_verified')
                ->leftJoin('inct', 'inct.inct_hash', '=', 'inmr.inct_hash')
                ->where('inmr.is_deleted', 0)
                ->where('inmr.sumr_hash', Auth::user()->sumr_hash)
                ->orderBy('inmr_hash', 'desc')
                ->get();
       }else{
        $data['products'] = DB::table('inmr')->select(
            'inmr.*',
            'inct.cat_name',
            'sumr.shop_name',
            'sumr.seller_name'
)
                
                ->leftJoin('inct', 'inct.inct_hash', '=', 'inmr.inct_hash')
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'inmr.sumr_hash')
                ->where('inmr.is_deleted', 0)
                ->orderBy('is_verified')->get();
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
        // Validator::make($request->all(),
        //     [
        //         'product_name' => 'required',
        //         'inct_hash' => 'required',
        //         'product_details' => 'required',
        //         'onhand_qty' => 'required',
        //         'available_qty' => 'required',
        //         'cost_amt' => 'required',
        //         'weight' => 'required'
        //     ]
        // )->validate();
        
        Validator::make($request->all(),
        [
            'product_name' => 'required',
            'inct_hash' => 'required',
            'product_details' => 'required',
            'onhand_qty' => 'required',
            'available_qty' => 'required',
            'cost_amt' => 'required',
            'weight' => 'required'
        ]
        )->setAttributeNames([
            'product_name' => 'Product Name',
            'inct_hash' => 'Category',
            'product_details' => 'Product Details',
            'onhand_qty' => 'On hand Quantity',
            'available_qty' => 'Available Quantity',
            'cost_amt' => 'Price',
            'weight' => 'Weight'
            ])->validate();

        
        $products = new ProductsModel();
        $products->product_name = $request->input('product_name');
        $products->co_no = '01';
        $products->image_path = 'Default.jpg';
        $products->product_details = $request->input('product_details');
        $products->inct_hash = $request->input('inct_hash');
        $products->onhand_qty = $request->input('onhand_qty');
        $products->available_qty = $request->input('available_qty');
        $products->cost_amt = $request->input('cost_amt');
        $products->inct_hash = $request->input('inct_hash');
        $products->lengthsize = $request->input('lengthsize');
        $products->height = $request->input('height');
        $products->width = $request->input('width');
        $products->dimension = ceil($request->input('dimension'));
        $products->weight = ceil($request->input('weight'));
        $products->create_datetime = Carbon::now();
        $products->sumr_hash = Auth::user()->sumr_hash;
        $products->save();

        DB::table('inmr')->where('inmr_hash', $products->inmr_hash)->update(['image_path' =>  $products->sumr_hash.'/'.$products->inmr_hash.'/Default.jpg']);

        // if (count($request->images)) {
        //     foreach ($request->images as $image) {
        //         $image->store('products/'.Auth::user()->sumr_hash.'/'.$products->inmr_hash);
        //     }
        // }
        
        return ( new Reference( $products ))
                ->response()
                ->setStatusCode(201);
    }

    public function upload(Request $request)
    {   

        
        $last_id = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->max('inmr_hash');
        $last_item = DB::table('inmr')->select('*')->where('sumr_hash' , Auth::user()->sumr_hash)->where('inmr_hash',  $last_id)->max('inmr_hash');
        $items_dataset = [];
        if (count($request->images)) {
            foreach ($request->images as $image) {
                $file_name = strtolower(trim($image->getClientOriginalName()));

                $image->move('storage/products/'.Auth::user()->sumr_hash.'/'.$last_item ,$file_name);
                $items_dataset[] = [
                    'inmr_hash' => $last_id,
                    'path' => $file_name
                ];
            }
       
        }
        DB::table('imgs')->insert($items_dataset);

        return response()->json([
            "message" => "Done"
        ]);
    }
    public function show($id)
    {
        $data['products'] = ProductsModel::findOrFail($id);

        $data['images'] = ImagesModel::select(
            'imgs.path')
            // 'inmr.*'

            //     ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'imgs.inmr_hash')
                ->where('imgs.inmr_hash', $id)
                ->where('imgs.is_deleted', 0)
                ->get();
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
                'product_details' => 'required',
                'onhand_qty' => 'required',
                'available_qty' => 'required',
                'cost_amt' => 'required'
                
            ]
        )->validate();
        $products->product_name = $request->input('product_name');
        $products->product_details = $request->input('product_details');
        $products->onhand_qty = $request->input('onhand_qty');
        $products->available_qty = $request->input('available_qty');
        $products->inct_hash = $request->input('inct_hash');
        $products->cost_amt = $request->input('cost_amt');
        $products->lengthsize = $request->input('lengthsize');
        $products->height = $request->input('height');
        $products->width = $request->input('width');
        $products->dimension = $request->input('dimension');
        $products->weight = $request->input('weight');
        $products->update_datetime = Carbon::now();
        $products->sumr_hash = Auth::user()->sumr_hash;

        //update  based on the http json body that is sent
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

        $products->is_verified = 3;
        //update classification based on the http json body that is sent
        $products->save();

        return ( new Reference( $products ))
            ->response()
            ->setStatusCode(200);
    }
    public function removeImages($id)
    {   
    // $path = DB::table('imgs')->select('*')->where('inmr_hash', $id)->where('is_deleted' , 0)->get();
    // $path = DB::table('imgs')->select('*')->where('inmr_hash', $id)->get();
    DB::table('imgs')->where('inmr_hash', $id)->update(['is_deleted' =>  1]);
    // $images = public_path('storage/products/'.Auth::user()->sumr_hash. '/'.$id);
    // $images =  Storage::delete('storage/products/'.Auth::user()->sumr_hash. '/'.$id);
    // dd($images);
    // $productImage = public_path('/storage/products/'.Auth::user()->sumr_hash. '/'.$id);
    // Storage::delete($productImage);
        
   \File::deleteDirectory(public_path('/storage/products/'.Auth::user()->sumr_hash. '/'.$id));
        // dd($path);

            // foreach($path as $p){
                // $image_path = public_path('/storage/products/'.Auth::user()->sumr_hash. '/'.$id);
                // @unlink($image_path);
                
            //  }
    return;
   
}
}