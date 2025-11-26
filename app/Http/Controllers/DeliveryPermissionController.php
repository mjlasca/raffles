<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPermission;
use App\Models\User;
use App\Services\RequestPermissionService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;

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
    public function index(Request $req)
    {
        $deliveryPermission = DeliveryPermission::orderBy('id', 'DESC');
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $deliveryPermission = $deliveryPermission->whereBetween('created_at',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }
        $deliveryPermission = $deliveryPermission->paginate('50');
        return view('delivery_permission.index', compact('deliveryPermission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($delivery_id)
    {
        $current_user = Auth::user();
        $delivery = Delivery::where('id',$delivery_id)->first();
        return view('delivery_permission.create', compact('current_user','delivery'));
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
        $deliveryPermission = DeliveryPermission::where('id',$id)->first();
        if($deliveryPermission->status == 1){
            return redirect()->route('delivery_permission.index');
        }
        $dateMin = Carbon::now()->format('Y-m-d');
        $dateMax = Carbon::now()->addDays(2)->format('Y-m-d');
        return view('delivery_permission.edit', compact('deliveryPermission','dateMin','dateMax'));
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
        $currentUser = Auth::user();
        DeliveryPermission::where('id',$id)->update([
            'date_permission' => $request->input('date_permission'),
            'status' => 1,
            'allow_user' => $currentUser->id
        ]);
        return redirect()->route('delivery_permission.index');
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
