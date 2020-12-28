<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Staff;
use App\Http\Controllers\Controller;
use App\Http\Resources\Reference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registerstaff = Staff::where('is_deleted', 0)->get();                           
        return Reference::collection($registerstaff);
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
                'username' => 'required|string|max:255|unique:tb_users',
                'password' => 'required|string|min:6|confirmed',
                'contact_number' => '|min:11|max:11|confirmed',
              
            ]
        )->validate();

        $registerstaff = new Staff();
        $registerstaff->account_no = DB::select("select CreateAccountNo() as account_no")[0]->account_no;
        $registerstaff->username = $request->input('username');
        $registerstaff->password = Hash::make($request->input('password'));
        $registerstaff->staff_name = $request->input('staff_name');
        $registerstaff->contact_number = $request->input('contact_number');
        $registerstaff->department_id = $request->input('department_id');
        $registerstaff->user_type_id = $request->input('user_type_id');
         $registerstaff->id_number = $request->input('id_number');
        $registerstaff->created_datetime = Carbon::now();
        $registerstaff->created_by = Auth::user()->id;

        $registerstaff->save();

        //return json based from the resource data
        return ( new Reference( $registerstaff ))
                ->response()
                ->setStatusCode(201);
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
        $registerstaff = Staff::findOrFail($id);

        return ( new Reference( $registerstaff ) )
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
        // Validator::make($request->all(),
        //     [
        //         'username' => 'required|string|max:255|unique:b_users,username,'.$id,
        //         'password' => 'required|string|min:6|confirmed',
        //         'email' => 'required|string|email|max:255|unique:b_users,email,'.$id
                
        //     ]
        // )->validate();

        // $user = User::findOrFail($id);
        // $user->username = $request->input('username');
        // $user->password = Hash::make($request->input('password'));
        // $user->email = $request->input('email');
        // $user->firstname = $request->input('firstname');
        // $user->middlename = $request->input('middlename');
        // $user->lastname = $request->input('lastname');
        // $user->modified_datetime = Carbon::now();
        // $user->modified_by = Auth::user()->id;

        // $user->save();

        // //return json based from the resource data
        // return ( new Reference( $user ))
        //         ->response()
        //         ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage for deleting.
     * preventing force delete rather update the is_deleted = true
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // $user = User::findOrFail($id);
        // $user->is_deleted = 1;
        // $user->deleted_datetime = Carbon::now();
        // $user->deleted_by = Auth::user()->id;

        // //update classification based on the http json body that is sent
        // $user->save();

        // return ( new Reference( $user ) )
        //     ->response()
        //     ->setStatusCode(200);
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
