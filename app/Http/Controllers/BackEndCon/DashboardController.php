<?php

namespace App\Http\Controllers\BackEndCon;

use App\Customer;
use App\Expense;
use App\Group;
use App\Hajj;
use App\Agent;
use App\HajjPayment;

use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $date = Carbon::now()->format('Y-m-d');
        $total_amount = 0;
        $total_paid_amount = 0;
        $total_due = 0 ;

        //Total Paid
        $hajjs = Hajj::get();
      //  dd(count($hajjs));

     if (count($hajjs) > 0 ) {
         foreach ($hajjs as $hajj) {
             $id[] = $hajj->id;
             $package_id[] = $hajj->package_id;
             $customer_id[] = $hajj->customer_id;
         }


         // Total Paid Amount
         $paids = HajjPayment::whereIn('hajj_id', $id)->select('amount')->get();



         foreach ($paids as $paid) {
             $total_paid_amount += $paid->amount;
         }

         // Total Amount
         $total_amounts = Hajj::join('packages', 'hajjs.package_id', '=', 'packages.id')
             ->whereIn('packages.id', $package_id)
             ->whereIn('hajjs.customer_id', $customer_id)
             ->select('packages.total_price')
             ->get();

         foreach ($total_amounts as $total) {
             $total_amount += $total->total_price;
         }

         // Total Due
         $total_due = $total_amount - $total_paid_amount;

     }


        //Todays Deposit
        $deposit_amounts = HajjPayment::where('deposit_date',$date)
                 ->select('amount')->get();
        $todays_deposit = 0;
        foreach ($deposit_amounts as $deposit){
            $todays_deposit += $deposit->amount;
        }

        //Todays expense
        $expense_amounts = Expense::where('expense_date',$date)
            ->select('amount')->get();
        $today_expense = 0;
        foreach ($expense_amounts as $expense){
            $today_expense += $expense->amount;
        }




        $agents = Agent::get();
        $groups = Group::get();


        $totalCounts = (object) array(
            'total_amount' => $total_amount,
            'total_paid_amount' => $total_paid_amount,
            'total_due_amount' => $total_due,
            'total_deposit' => $todays_deposit,
            'todays_total_expense' => $today_expense,
        );

        $agent_calculation=[];
        $group_calculation=[];
        return view('Admin.dashboard', compact('totalCounts','agents','groups','agent_calculation','group_calculation'));
    }

       public function agent_wise_calculation( Request $request)
       {

           $date = Carbon::now()->format('Y-m-d');
           $total_amount=0;
           $total_paid_amount=0;
           $total_due = 0 ;
           //Total Paid
           $hajjs = Hajj::get();

           if (count($hajjs) > 0 ) {
               foreach ($hajjs as $hajj) {
                   $id[] = $hajj->id;
                   $package_id[] = $hajj->package_id;
                   $customer_id[] = $hajj->customer_id;
               }

               // Total Paid Amount
               $paids = HajjPayment::whereIn('hajj_id', $id)->select('amount')->get();

               foreach ($paids as $paid) {
                   $total_paid_amount += $paid->amount;
               }

               // Total Amount
               $total_amounts = Hajj::join('packages', 'hajjs.package_id', '=', 'packages.id')
                   ->whereIn('packages.id', $package_id)
                   ->whereIn('hajjs.customer_id', $customer_id)
                   ->select('packages.total_price')
                   ->get();

               foreach ($total_amounts as $total) {
                   $total_amount += $total->total_price;
               }

               // Total Due
               $total_due = $total_amount - $total_paid_amount;

           }


           //Todays Deposit
           $deposit_amounts = HajjPayment::where('deposit_date',$date)
               ->select('amount')->get();
           $todays_deposit = 0;
           foreach ($deposit_amounts as $deposit){
               $todays_deposit += $deposit->amount;
           }

           //Todays expense
           $expense_amounts = Expense::where('expense_date',$date)
               ->select('amount')->get();
           $today_expense = 0;
           foreach ($expense_amounts as $expense){
               $today_expense += $expense->amount;
           }

           $agents = Agent::get();
           $groups = Group::get();

           $totalCounts = (object) array(
               'total_amount' => $total_amount,
               'total_paid_amount' => $total_paid_amount,
               'total_due_amount' => $total_due,
               'total_deposit' => $todays_deposit,
               'todays_total_expense' => $today_expense,
           );


           // start agent calculation
           $agen_cal_id = $request->agent_id;
           if ($agen_cal_id == null){
               $agent_calculation=[];
           }else{
               $agent_calculation = Hajj::select('hajjs.*')->with(['customer','package','payments'])
                   ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
                   ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
                   ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
                   ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
                   ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
                   ->groupBy('hajjs.id')
                   ->groupBy('hajj_payments.hajj_id')
                   ->where('customers.agent_id',$agen_cal_id)
                   ->get();
           }

           $group_calculation=[];
           return view('Admin.dashboard', compact('totalCounts','agents','groups','agent_calculation','group_calculation'));
       }



    public function group_wise_calculation( Request $request)
    {

        $date = Carbon::now()->format('Y-m-d');

        $total_amount=0;
        $total_paid_amount=0;
        $total_due = 0 ;

        //Total Paid
        $hajjs = Hajj::get();


        if (count($hajjs) > 0 ) {
            foreach ($hajjs as $hajj) {
                $id[] = $hajj->id;
                $package_id[] = $hajj->package_id;
                $customer_id[] = $hajj->customer_id;
            }

            // Total Paid Amount
            $paids = HajjPayment::whereIn('hajj_id', $id)->select('amount')->get();

            foreach ($paids as $paid) {
                $total_paid_amount += $paid->amount;
            }

            // Total Amount
            $total_amounts = Hajj::join('packages', 'hajjs.package_id', '=', 'packages.id')
                ->whereIn('packages.id', $package_id)
                ->whereIn('hajjs.customer_id', $customer_id)
                ->select('packages.total_price')
                ->get();

            foreach ($total_amounts as $total) {
                $total_amount += $total->total_price;
            }

            // Total Due
            $total_due = $total_amount - $total_paid_amount;
        }


        //Todays Deposit
        $deposit_amounts = HajjPayment::where('deposit_date',$date)
            ->select('amount')->get();
        $todays_deposit = 0;
        foreach ($deposit_amounts as $deposit){
            $todays_deposit += $deposit->amount;
        }

        //Todays expense
        $expense_amounts = Expense::where('expense_date',$date)
            ->select('amount')->get();
        $today_expense = 0;
        foreach ($expense_amounts as $expense){
            $today_expense += $expense->amount;
        }

        $agents = Agent::get();
        $groups = Group::get();

        $totalCounts = (object) array(
            'total_amount' => $total_amount,
            'total_paid_amount' => $total_paid_amount,
            'total_due_amount' => $total_due,
            'total_deposit' => $todays_deposit,
            'todays_total_expense' => $today_expense,
        );


        // start agent calculation
        $agen_cal_id = $request->agent_id;
        if ($agen_cal_id == null){
            $agent_calculation=[];
        }else{
            $agent_calculation = Hajj::select('hajjs.*')->with(['customer','package','payments'])
                ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
                ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
                ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
                ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
                ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
                ->groupBy('hajjs.id')
                ->groupBy('hajj_payments.hajj_id')
                ->where('customers.agent_id',$agen_cal_id)
                ->get();
        }


        // start agent calculation
        $group_cal_id = $request->group_id;
        if ($group_cal_id == null){
            $group_calculation=[];
        }else{
            $group_calculation = Hajj::select('hajjs.*')->with(['customer','package','payments'])
                ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
                ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
                ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
                ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
                ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
                ->groupBy('hajjs.id')
                ->groupBy('hajj_payments.hajj_id')
                ->where('customers.group_id',$group_cal_id)
                ->get();


        }

       // dd($group_calculation);
        return view('Admin.dashboard', compact('totalCounts','agents','groups','agent_calculation','group_calculation'));
    }



}
