<?php

namespace App\Http\Controllers\BackEndCon\Accounts;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashInHandController extends Controller
{
    private $controllerInfo;

    public function __construct()
    {
        $this->controllerInfo = (object) array(
            'title' => 'Cash In Hand',
            'actionButtons' => true,
            'routeNamePrefix' => 'cash-in-hand',
        );



        $this->info = (object) array(
            'title' => 'Daily Cash In Hand',
            'actionButtons' => true,
            'routeNamePrefix' => 'daily-cash-in-hand',
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
        $show_table = false;
        $input = [
          'start_date' => null,
          'end_date' => null,
        ];
        return view('Admin.accounts.cash-in-hand.index', compact('controllerInfo', 'show_table', 'input'));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $controllerInfo = $this->controllerInfo;
        $show_table = true;
        $input = $request->input();
        DB::enableQueryLog();
        $left_table = DB::table('hajj_payments')
            ->select('deposit_date', DB::raw("SUM(hajj_payments.amount) AS payment_amount"), 'expense_date', 'expenses.amount AS expense_amount')
            ->join('expenses', 'hajj_payments.deposit_date', '=', 'expenses.expense_date', 'left')
            ->groupBy('deposit_date');
        if (isset($request->start_date) || isset($request->end_date)) {
            $left_table = $left_table->whereBetween('deposit_date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
        }
        $right_table = DB::table('hajj_payments')
            ->select('deposit_date', 'hajj_payments.amount AS payment_amount', 'expense_date', DB::raw("SUM(expenses.amount) AS expense_amount"))
            ->join('expenses', 'hajj_payments.deposit_date', '=', 'expenses.expense_date', 'right')
            ->groupBy('expense_date');
        if (isset($request->start_date) || isset($request->end_date)) {
            $right_table = $right_table->whereBetween('expense_date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
        }
        $unionTable = $left_table->union($right_table);
        $balances = DB::query()->fromSub($unionTable, 'a')
            ->select(DB::raw('IFNULL(a.deposit_date, a.expense_date) AS `date`'))
            ->addSelect(DB::raw('IFNULL(a.payment_amount, 0) AS total_deposit'))
            ->addSelect(DB::raw('IFNULL(a.expense_amount, 0) AS total_expenses'))
            ->addSelect(DB::raw('(IFNULL(a.payment_amount, 0) - IFNULL(a.expense_amount, 0)) AS cash_in_hand'))
            ->orderBy('date');
        $total = DB::query()->fromSub($balances, 't')->selectRaw('SUM(t.cash_in_hand) AS total')->first();
        $balances = $balances->get();
        $sql = DB::getQueryLog();
        return view('Admin.accounts.cash-in-hand.index', compact('controllerInfo', 'balances', 'total', 'show_table', 'input', 'sql'));
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
        //
    }




public function deposit_expense()
    {
        $controllerInfo = $this->info;
        $show_table = false;
        $input = [
          'start_date' => null,
          'end_date' => null,
        ];
        return view('Admin.accounts.cash-in-hand.index_update', compact('controllerInfo', 'show_table', 'input'));
    }

    public function deposit_expense_stor(Request $request)
    {
//        // return $request; exit();
//        // $start_date = '20201202';
//        $start_date = date('Y-m-d', strtotime($request->start_date));
//       // dd($start_date);
//        // $end_date = date('Ymd', strtotime($request->end_date));
//        $controllerInfo = $this->info;
//        $show_table = true;
//        $input = $request->input();
//        DB::enableQueryLog();
//        $left_table = DB::table('deposits')
//            ->select('deposit_date', DB::raw("SUM(deposits.amount) AS payment_amount"), 'expense_date', 'expenses.amount AS expense_amount')
//            ->join('expenses', 'deposits.deposit_date', '=', 'expenses.expense_date', 'left')
//            ->groupBy('deposit_date');
//        if (isset($request->start_date)) {
//            $left_table = $left_table->where('deposit_date', [Carbon::parse($request->start_date)->format('Y-m-d')]);
//        }
//        $right_table = DB::table('deposits')
//            ->select('deposit_date', 'deposits.amount AS payment_amount', 'expense_date', DB::raw("SUM(expenses.amount) AS expense_amount"))
//            ->join('expenses', 'deposits.deposit_date', '=', 'expenses.expense_date', 'right')
//            ->groupBy('expense_date');
//        if (isset($request->start_date)) {
//            $right_table = $right_table->where('expense_date', [Carbon::parse($request->start_date)->format('Y-m-d')]);
//        }
//        $unionTable = $left_table->union($right_table);
//        $balances = DB::query()->fromSub($unionTable, 'a')
//            ->select(DB::raw('IFNULL(a.deposit_date, a.expense_date) AS `date`'))
//            ->addSelect(DB::raw('IFNULL(a.payment_amount, 0) AS total_deposit'))
//            ->addSelect(DB::raw('IFNULL(a.expense_amount, 0) AS total_expenses'))
//            ->addSelect(DB::raw('(IFNULL(a.payment_amount, 0) - IFNULL(a.expense_amount, 0)) AS cash_in_hand'))
//            ->orderBy('date');
//        $total = DB::query()->fromSub($balances, 't')->selectRaw('SUM(t.cash_in_hand) AS total')->first();
//        $balances = $balances->get();
//        $sql = DB::getQueryLog();
//
//        $dep = DB::table('deposits')
//
//                        ->where('deposits.deposit_date', '=', $start_date)
//                        ->sum('deposits.amount');
//
//        $exp = DB::table('expenses')
//
//                        ->where('expenses.expense_date', '=', $start_date)
//                        ->sum('expenses.amount');
        // return $exp; exit();


        $controllerInfo = $this->controllerInfo;
        $show_table = true;
        $input = $request->input();
        $start_date = date('Y-m-d', strtotime($request->start_date));
       // dd($start_date);
        DB::enableQueryLog();
        $left_table = DB::table('hajj_payments')
            ->select('deposit_date', DB::raw("SUM(hajj_payments.amount) AS payment_amount"), 'expense_date', 'expenses.amount AS expense_amount')
            ->join('expenses', 'hajj_payments.deposit_date', '=', 'expenses.expense_date', 'left')
            ->groupBy('deposit_date');
        if (isset($request->start_date)) {
            $left_table = $left_table->where('deposit_date', [Carbon::parse($request->start_date)->format('Y-m-d')]);
        }

        $right_table = DB::table('hajj_payments')
            ->select('deposit_date', 'hajj_payments.amount AS payment_amount', 'expense_date', DB::raw("SUM(expenses.amount) AS expense_amount"))
            ->join('expenses', 'hajj_payments.deposit_date', '=', 'expenses.expense_date', 'right')
            ->groupBy('expense_date');
        if (isset($request->start_date)) {
            $right_table = $right_table->where('expense_date', [Carbon::parse($request->start_date)->format('Y-m-d')]);
        }
        $unionTable = $left_table->union($right_table);
        $balances = DB::query()->fromSub($unionTable, 'a')
            ->select(DB::raw('IFNULL(a.deposit_date, a.expense_date) AS `date`'))
            ->addSelect(DB::raw('IFNULL(a.payment_amount, 0) AS total_deposit'))
            ->addSelect(DB::raw('IFNULL(a.expense_amount, 0) AS total_expenses'))
            ->addSelect(DB::raw('(IFNULL(a.payment_amount, 0) - IFNULL(a.expense_amount, 0)) AS cash_in_hand'))
            ->orderBy('date');
        $total = DB::query()->fromSub($balances, 't')->selectRaw('SUM(t.cash_in_hand) AS total')->first();
       // dd($total);

        $balances = $balances->get();
        //dd($balances);
        $sql = DB::getQueryLog();
       // dd($sql);

        $dep = DB::table('hajj_payments')
            ->where('hajj_payments.deposit_date', '=', $start_date)
            ->sum('hajj_payments.amount');
        //dd($dep);

        $dep_pre_all = DB::table('hajj_payments')
            ->where('hajj_payments.deposit_date', '<', $start_date)
            ->sum('hajj_payments.amount');
       // dd($dep_pre_all);


        $exp = DB::table('expenses')

            ->where('expenses.expense_date', '=', $start_date)
            ->sum('expenses.amount');

        $exp_all = DB::table('expenses')

            ->where('expenses.expense_date', '<', $start_date)
            ->sum('expenses.amount');
            $pre_total_hand_cash = $dep_pre_all- $exp_all;
          //  dd($pre_total_hand_cash);


        return view('Admin.accounts.cash-in-hand.index_update', compact('start_date','dep','exp','controllerInfo', 'balances', 'total', 'show_table', 'input', 'sql','pre_total_hand_cash'));
    }







}
