<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomerPassport;

class HomeController extends Controller
{


    public function checkindex()
    {
        $passports='';
        $passports_id = '';
        return view('passport-check',compact('passports','passports_id'))->with('not_permitted', 'Sorry! Your provided information does not Match.');

    }

    public function check_status(Request $request)
    {

       // dd($request->all());
        $phone = $request->phone;
        $passport_no = $request->passport_no;
        $passports_id = CustomerPassport::with(['passportStatuses' => function ($q) {
            $q->orderBy('date', 'DESC');
        }])
            ->join('customers','customer_passports.id','=','customers.passport_id')
            ->where('customers.mobile',$phone)
            ->where('customer_passports.passport_no',$passport_no)
            ->select('customer_passports.*')
            ->first();
        //dd($passports_id);
        if ($passports_id != null){
        $passports = CustomerPassport::with(['passportStatuses' => function ($q) {
            $q->orderBy('date', 'DESC');
        }])
            ->join('customers','customer_passports.id','=','customers.passport_id')
            ->join('passport_history','customer_passports.id','=','passport_history.passport_id')
            ->where('customers.mobile',$phone)
            ->where('customer_passports.passport_no',$passport_no)
            ->where('passport_history.passport_id',$passports_id->id)
            ->select('customer_passports.*','passport_history.*','customers.*')
            ->get();

       // dd($passports);
        return view('passport-check',compact('passports','passports_id'));
     }else {
            return redirect('/passport-check')->with('not_permitted', 'Sorry! Your provided information does not Match.');
        }
    }













}
