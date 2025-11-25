<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPermission;
use App\Models\User;
use App\Services\RequestPermissionService;
use Illuminate\Http\Request;

class DeliveryPermissionController extends Controller
{
    protected $permissionService;

    public function __construct(RequestPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryPermission = DeliveryPermission::get();
        return view('delivery_permission.index', compact('deliveryPermission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        if(!empty($request->input('current_user')) && !empty($request->input('delivery_id'))){
            $user = User::where('id',$request->input('current_user'))->first();
            if($user){
                $this->permissionService->createRequest($user,$request->input('delivery_id'));
                return view('delivery_permission.pending');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
