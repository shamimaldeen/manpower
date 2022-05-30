<?php

namespace App\Http\Controllers\BackEndCon\Accounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\HajjPayment;
use App\Hajj;
use App\Bank;
use App\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Deposit;


class CashInDepositController extends Controller
{
    private $controllerInfo;
    private $hajj_type;
    private $hajj_type_no;

    public function __construct()
    {
        $this->hajj_type = 'Haji';
        $this->hajj_type_no = 1;

        $this->controllerInfo = (object) array(
            'title' => 'Deposit',
            'actionButtons' => true,
            'routeNamePrefix' => 'deposit-list',
        );
    }




	// //show  page old
 //    public function cash_in_deposit()
 //    {
 //        $deposit = Deposit::orderBy('id', 'DESC')->get();

 //        $hajj_type = $this->hajj_type;
 //        $payments = Deposit::with(['hajj' => function ($q) {
 //            $q->with('customer');
 //        }])->get();
        
 //    	return view('Admin.accounts.cash_in_deposit.index', compact('deposit', 'hajj_type', 'payments'));
 //    }





//show  page new
public function cash_in_deposit()
    {
        $deposit = Deposit::orderBy('id', 'DESC')
                   ->join('banks','banks.b_id','=','deposits.b_id')
                   ->get();


        $hajj_type = $this->hajj_type;
        $payments = Deposit::with(['hajj' => function ($q) {
            $q->with('customer');
        }])
        ->join('banks','banks.b_id','=','deposits.b_id','left')
        ->get();
        
        return view('Admin.accounts.cash_in_deposit.index', compact('deposit', 'hajj_type', 'payments'));
    }









    // //add  page old
    // public function add_cash_in_deposit()
    // {
    //     $controllerInfo = $this->controllerInfo;
    //    $banks = Bank::all();
    //     return view('Admin.accounts.cash_in_deposit.create', compact('controllerInfo'),['banks'=>$banks]);
    // }




//add  page new
    public function add_cash_in_deposit()
    {
        $controllerInfo = $this->controllerInfo;
       $banks = Bank::all();
        return view('Admin.accounts.cash_in_deposit.create', compact('controllerInfo'),['banks'=>$banks]);
    }











    // //save data old
    // public function save_cash_deposit(Request $request)
    // {
         




    //   $dep = $request->validate([
    //     'voucher_name' => 'required',
    //         'type' => 'required|numeric',
    //         'depositor_name' => 'required',
    //         'bank' => 'nullable',
    //         // 'bank_name' => 'nullable',
    //         'bank_branch_name' => 'nullable',
    //         'cheque_number' => 'nullable',
    //         'deposit_date' => 'required',
    //         'amount' => 'required|numeric',
    //         'status' => 'required|numeric',
    // ]);

    //     $dep = new Deposit();
    //     $dep->voucher_name = $request->voucher_name;
    //     $dep->type = $request->type;
    //     $dep->depositor_name = $request->depositor_name;
    //     $dep->b_id = $request->bank;
    //     $dep->bank_branch_name = $request->bank_branch_name;
    //     $dep->cheque_number = $request->cheque_number;
    //     $dep->deposit_date = $request->deposit_date;
    //     $dep->amount = $request->amount;
    //     $dep->status = $request->status;
    //     $dep->save();
    //     return redirect('cash-in-deposit')->with('message','Cash in deposit added successfully');






    //    // dd($request);


    //     //  $validatedData = Validator::make($request->all(), array(
            
    //     //     'voucher_name' => 'required',
    //     //     'type' => 'required|numeric',
    //     //     'depositor_name' => 'required',
    //     //     'bank' => 'nullable',
    //     //     // 'bank_name' => 'nullable',
    //     //     'bank_branch_name' => 'nullable',
    //     //     'cheque_number' => 'nullable',
    //     //     'deposit_date' => 'required',
    //     //     'amount' => 'required|numeric',
    //     //     'status' => 'required|numeric',
    //     // ))->validate();

    //     // $payment = Deposit::create($validatedData);

    //     // dd($payment);

    //     // if ($payment) {
    //     //     Session::flash('success', $this->controllerInfo->title . ' Created Successfully');
    //     //     return redirect()->route('cash-in-deposit');
    //     // } else {
    //     //     Session::flash('error', 'Whoops! Failed to Create ' . $this->controllerInfo->title);
    //     //     return redirect('cash-in-deposit');
    //     // }
    // }







//save data new
    public function save_cash_deposit(Request $request)
    {
         




      $dep = $request->validate([
        // 'voucher_name' => 'required',
            'type' => 'required|numeric',
            'depositor_name' => 'required',
            'bank' => 'nullable',
            // 'bank_name' => 'nullable',
            'bank_branch_name' => 'nullable',
            'cheque_number' => 'nullable',
            'deposit_date' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required|numeric',
    ]);

        $dep = new Deposit();
        // $dep->voucher_name = $request->voucher_name;
        $dep->type = $request->type;
        $dep->depositor_name = $request->depositor_name;
        $dep->b_id = $request->bank;
        $dep->bank_branch_name = $request->bank_branch_name;
        $dep->cheque_number = $request->cheque_number;
        $dep->deposit_date = date('Y-m-d',strtotime($request->deposit_date));
        $dep->amount = $request->amount;
        $dep->status = $request->status;
        $dep->save();


   
    $id = Deposit::orderBy('id', 'DESC')
                        ->select('id')
                        ->get();

        // return $id; exit();
        $depo = Deposit::find($id[0]->id);
        $depo->voucher_name = sprintf("VOU-%04d", $id[0]->id);
        $depo->save();




        return redirect('cash-in-deposit')->with('message','Cash in deposit added successfully');






       // dd($request);


        //  $validatedData = Validator::make($request->all(), array(
            
        //     'voucher_name' => 'required',
        //     'type' => 'required|numeric',
        //     'depositor_name' => 'required',
        //     'bank' => 'nullable',
        //     // 'bank_name' => 'nullable',
        //     'bank_branch_name' => 'nullable',
        //     'cheque_number' => 'nullable',
        //     'deposit_date' => 'required',
        //     'amount' => 'required|numeric',
        //     'status' => 'required|numeric',
        // ))->validate();

        // $payment = Deposit::create($validatedData);

        // dd($payment);

        // if ($payment) {
        //     Session::flash('success', $this->controllerInfo->title . ' Created Successfully');
        //     return redirect()->route('cash-in-deposit');
        // } else {
        //     Session::flash('error', 'Whoops! Failed to Create ' . $this->controllerInfo->title);
        //     return redirect('cash-in-deposit');
        // }
    }





    // //edit old
    // public function deposit_edit($id)
    // {
    //     $hajj_type = $this->hajj_type;
    //     $hajj_payment = Deposit::FindOrFail($id);
    //     $haji = Hajj::with(['customer', 'payments'])->whereHas('payments', function ($q) use ($hajj_payment) {
    //         $q->where('hajj_id', $hajj_payment->hajj_id);
    //     })->first();
        
    //     return view('Admin.accounts.cash_in_deposit.edit', compact('hajj_type', 'hajj_payment'));
    // }



//edit new
 public function deposit_edit($id)
    {
        $hajj_type = $this->hajj_type;
        $hajj_payment = Deposit::FindOrFail($id);
        $banks = Bank::orderBy('banks.b_id', 'DESC')
                        ->get();
        $bank_row = Bank::orderBy('banks.b_id', 'DESC')
                          ->join('deposits', 'deposits.b_id', '=', 'banks.b_id')
                          ->where('deposits.id', '=', $id)
                          ->select('banks.b_name', 'banks.b_id')
                          ->first();

        // return $bank_row; exit();
        $haji = Hajj::with(['customer', 'payments'])->whereHas('payments', function ($q) use ($hajj_payment) {
            $q->where('hajj_id', $hajj_payment->hajj_id);
        })->first();
       
        return view('Admin.accounts.cash_in_deposit.edit', compact('bank_row','banks','hajj_type', 'hajj_payment'));
    }






    // //update old
    // public function update_deposit(Request $request, $id)
    // {
    //     $validatedData = Validator::make($request->all(), array(
            
    //         'voucher_name' => 'required|unique:hajj_payments,voucher_name, ' . $id . ',id',
    //         'type' => 'required|numeric',
    //         'depositor_name' => 'required',
    //         'deposit_date' => 'required',
    //         'amount' => 'required',
    //         'status' => 'required|numeric',
    //     ))->validate();

    //     $data = $request->all();
    //     $payment = Deposit::FindOrFail($id)->update($data);
    //     if ($payment) {
    //         Session::flash('success', 'Payment Updated Successfully');
    //         return redirect('cash-in-deposit');
    //     } else {
    //         Session::flash('error', 'Whoops! Failed to Update Payment');
    //         return redirect()->back()->withInput();
    //     }
    // }

    // //delete
    // public function delete_deposit(Request $request, $id)
    // {
    //     $delete = Deposit::find($id)->delete();
    //     return back();
    // }



//update new
    public function update_deposit(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), array(
           
            // 'voucher_name' => 'required|unique:hajj_payments,voucher_name, ' . $id . ',id',
            'type' => 'required|numeric',
            'depositor_name' => 'required',
            'bank' => 'nullable',
            'deposit_date' => 'required',
            'amount' => 'required',
            'status' => 'required|numeric',
        ))->validate();


        $dep = Deposit::find($id);

        // $dep->voucher_name = $request->voucher_name;
        $dep->type = $request->type;
        $dep->depositor_name = $request->depositor_name;
        $dep->b_id = $request->bank;
        $dep->bank_branch_name = $request->bank_branch_name;
        $dep->cheque_number = $request->cheque_number;
         $dep->deposit_date = date('Y-m-d',strtotime($request->deposit_date));
        // $dep->deposit_date = $request->deposit_date;
        $dep->amount = $request->amount;
        $dep->status = $request->status;
        $dep->save();

        return redirect('cash-in-deposit')->with('message','Cash in deposit added successfully');
        // $data = $request->all();
        // $payment = Deposit::FindOrFail($id)->update($data);
        // if ($payment) {
        //     Session::flash('success', 'Payment Updated Successfully');
        //     return redirect('cash-in-deposit');
        // } else {
        //     Session::flash('error', 'Whoops! Failed to Update Payment');
        //     return redirect()->back()->withInput();
        // }
    }





//delete
    public function delete_deposit(Request $request, $id)
    {
        $delete = Deposit::find($id)->delete();
        return back();
    }






    
}
