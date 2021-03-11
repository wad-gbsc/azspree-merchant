<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommentsModel;
use App\Http\Resources\Reference; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == 0) {
        $data['comments'] = CommentsModel::select(
            'cmnt.*',
            'user.fullname',
            'sumr.seller_name',
            'inmr.product_name'                    
)                          
                ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'cmnt.inmr_hash')
                ->leftJoin('user', 'user.user_hash', '=', 'cmnt.user_hash')
                ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'cmnt.sumr_hash')
                ->where('cmnt.sumr_hash' , Auth::user()->sumr_hash)
                ->get();
        }else if (Auth::user()->type == 2) {
            $data['comments'] = CommentsModel::select(
                'cmnt.*',
                'user.fullname',
                'sumr.seller_name',
                'inmr.product_name'                    
    )                          
                    ->leftJoin('inmr', 'inmr.inmr_hash', '=', 'cmnt.inmr_hash')
                    ->leftJoin('user', 'user.user_hash', '=', 'cmnt.user_hash')
                    ->leftJoin('sumr', 'sumr.sumr_hash', '=', 'cmnt.sumr_hash')
                    ->get();
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function commentslength()
    {
        
        $data['accounts'] = count(CommentsModel::where('answer_status', 0)->get());
        return $data;

    }

    public function create()
    {
        //
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
        $show = CommentsModel::findOrFail($id);

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
        $comment = CommentsModel::findOrFail($id);
        
        Validator::make($request->all(),
            [
                'answer' => 'required'
            ]
        )->validate();

        $comment->answer = $request->input('answer');
        $comment->answer_status = 1;
        $comment->updated_datetime = Carbon::now();

        //update  based on the http json body that is sent
        $comment->save();

        return ( new Reference( $comment ) )
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
}
