<?php

namespace App\Http\Controllers\BackEndCon;

use App\Customer;
use App\CustomerPassport;
use App\Http\Controllers\Controller;
use App\PassportHistory;
use App\PassportStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;

class PassportHistoryController extends Controller
{
    private $controllerInfo;
    private $controllerInfovisa;
    private $controllerInfostamping;

    public function __construct()
    {
        $this->controllerInfo = (object) array(
            'title' => 'Passport Alert History',
            'routeNamePrefix' => 'passport-history',
        );

        $this->controllerInfovisa = (object) array(
            'title' => 'Visa-Expire Alert History',
            'routeNamePrefix' => 'passport-history',
        );
        $this->controllerInfostamping = (object) array(
            'title' => 'Medical-Expire Alert History',
            'routeNamePrefix' => 'passport-history',
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $controllerInfo = $this->controllerInfo;
        $passports = CustomerPassport::with(['passportStatuses' => function ($q) {
            $q->orderBy('date', 'DESC');
        }])->get();
      //dd($passports);
//        return $passports;
        return view('Admin.passport-history.index', compact('controllerInfo', 'passports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $delete = PassportStatus::find($id)->delete();
        if ($delete) {
            return response()->json(['success' => true, 'message' => $this->controllerInfo->title . ' Deleted Successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Whoops! ' . $this->controllerInfo->title . ' Not Deleted'], 200);
        }
    }

    public function changeStatus(Request $request)
    {
        $validatedData = Validator::make($request->all(), array(
            'passport_id' => 'required|numeric',
            'passport_status_id' => 'required|numeric|not_in:0',
        ))->validate();

        $passportHistory = PassportHistory::create($validatedData);
        if ($passportHistory) {
            Session::flash('success', $this->controllerInfo->title . ' Created Successfully');
            return redirect()->route($this->controllerInfo->routeNamePrefix . '.index');
        } else {
            Session::flash('error', 'Whoops! Failed to Create ' . $this->controllerInfo->title);
            return redirect()->route($this->controllerInfo->routeNamePrefix . '.index');
        }
    }

    public function printReceipt($id)
    {
        $passport = CustomerPassport::with(['passportStatuses' => function ($q) {
            $q->orderBy('date', 'DESC')->first();
        }])->find($id);
        $details = DB::table('dev_app_details')->get();


      //  dd($passport);
        return view('Admin.passport-history.receipt', compact('passport','details'));
    }






    public function passport_alert()
    {

        $controllerInfo = $this->controllerInfo;
//        $next_date = Carbon::now()->addMonth(12)->format('Y-m-d');
//        $c_date = Carbon::now()->format('Y-m-d');
//
//        $details = CustomerPassport::with(['passportStatuses' => function ($q) {
//            $q->orderBy('date', 'DESC');
//        }])->where('expiry_date', '<=', $next_date)->get();


        $c_date =Carbon::now()->format('Y-m-d');
        $passports = CustomerPassport::get();
        $details ='';
        foreach ($passports as $val){
            $id[] = $val->id;
            $first_date =Carbon::parse($val->expiry_date)->subDays(200)->format('Y-m-d') ;
            $last_date =$val->expiry_date ;
            if ($c_date >= $first_date && $c_date <= $last_date ){
                $details =CustomerPassport::with(['passportStatuses' => function ($q) {
                    $q->orderBy('date', 'DESC');
                }])->whereIn('id',$id)->where('expiry_date','>=',$first_date)->where('expiry_date','<=',$last_date)->get();

            }

        }


        return view('Admin.passport-history.passport-alert', compact('controllerInfo','details'));
    }



    public function visa_expire_alert()
    {

        $controllerInfo = $this->controllerInfovisa;
        $c_date =Carbon::now()->format('Y-m-d');
        $visa_customers =Customer::get();
        $visa_details = '';
        foreach ($visa_customers as $val){
            $id[] = $val->id;
            $first_date =Carbon::parse($val->expire_date)->subDays(15)->format('Y-m-d') ;
            $last_date =$val->expire_date ;
            if ($c_date >= $first_date && $c_date <= $last_date ){
                $visa_details =Customer::join('customer_passports','customers.passport_id','=','customer_passports.id')
                             ->whereIn('customers.id',$id)->where('customers.expire_date','>=',$first_date)->where('customers.expire_date','<=',$last_date)
                             ->select('customers.*','customer_passports.passport_no')
                             ->get();
            }
        }
        return view('Admin.passport-history.visa-expire', compact('controllerInfo','visa_details'));
    }

    public function medical_expire_alert()
    {

        $controllerInfo = $this->controllerInfostamping;

        $c_date =Carbon::now()->format('Y-m-d');
        $customers =Customer::get();
        $stamping_details = '';
        foreach ($customers as $val){
            $id[] = $val->id;
            $first_date =Carbon::parse($val->m_expiry_date)->subDays(15)->format('Y-m-d') ;
            $last_date =$val->m_expiry_date ;
            if ($c_date >= $first_date && $c_date <= $last_date ){
                $stamping_details =Customer::join('customer_passports','customers.passport_id','=','customer_passports.id')
                                  ->whereIn('customers.id',$id)->where('customers.m_expiry_date','>=',$first_date)->where('customers.m_expiry_date','<=',$last_date)
                                  ->select('customers.*','customer_passports.passport_no')
                                 ->get();
            }
        }
        return view('Admin.passport-history.stamping_alert', compact('controllerInfo','stamping_details'));
    }









}
