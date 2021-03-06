<?php

namespace App\Http\Controllers\BackEndCon;

use App\Customer;
use App\HajjPayment;
use App\Http\Controllers\Controller;
use App\Hajj;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;
use function foo\func;

class HajjController extends Controller
{
    private $hajj_type;
    private $hajj_type_no;

    public function __construct()
    {
        $this->hajj_type = 'Customer Service';
        $this->hajj_type_no = 1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $hajj_type = $this->hajj_type;
        DB::connection()->enableQueryLog();
        $hajis = Hajj::select('hajjs.*')->with(['customer','package'])
            ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
            ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
            ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
            ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
            ->groupBy('hajjs.id')
            ->groupBy('hajj_payments.hajj_id')
            ->where('hajjs.type', $this->hajj_type_no)->get();

        //dd($hajis);
        DB::getQueryLog();
        return view('Admin.hajj.index', compact('hajj_type', 'hajis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $hajj_type = $this->hajj_type;
        $customers = Customer::all();
        $packages = Package::where('package_type', $this->hajj_type_no)->where('status', 1)->get();

        return view('Admin.hajj.form', compact('hajj_type', 'customers', 'packages'));
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
        $validatedData = Validator::make($request->all(), $this->dataToValidate())->validate();

        $data = $request->all();

        $data['type'] = $this->hajj_type_no;
        $last_haji = Hajj::where('type', $this->hajj_type_no)->latest()->first();
        $data['serial_no'] = 'CS' . '-' . (($last_haji ? explode('-', $last_haji->serial_no)[1] : 1000) + 1);
        $haji = Hajj::create($data);
        if ($haji) {
            Session::flash('success', 'Customer Service Created Successfully');
            return redirect()->route('package-service.index');
        } else {
            Session::flash('error', 'Whoops! Failed to Create Customer Service');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $haji = Hajj::FindOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {

        $hajj_type = $this->hajj_type;
        $haji = Hajj::FindOrFail($id);
       // dd($haji);
        $customers = Customer::all();
        $packages = Package::where('package_type', $this->hajj_type_no)->where('status', 1)->get();
        return view('Admin.hajj.form', compact('hajj_type', 'customers', 'packages', 'haji'));
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
        $dataToValidate = $this->dataToValidate();
        unset($dataToValidate['customer_id']);
        $validatedData = Validator::make($request->all(), $dataToValidate)->validate();

        $data = $request->all();
        $data['type'] = $this->hajj_type_no;
        $package = Hajj::FindOrFail($id)->update($data);
        if ($package) {
            Session::flash('success', 'Customer Service Updated Successfully');
            return redirect()->route('package-service.index');
        } else {
            Session::flash('error', 'Whoops! Failed to Update Customer Service');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $delete = Hajj::find($id)->delete();
        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Customer Service Deleted Successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Whoops! Customer Service Not Deleted'], 200);
        }
    }

    private function dataToValidate() {
        return array(
            'customer_id' => 'required|numeric',
            'package_id' => 'required|numeric'

        );
    }


    public function group_wise_history($id)
    {
       // dd($id);
        $hajj_type = $this->hajj_type;
        DB::connection()->enableQueryLog();
        $hajis = Hajj::select('hajjs.*')->with(['customer','package','payments'])
            ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
            ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
            ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
            ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
            ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
            ->groupBy('hajjs.id')
            ->groupBy('hajj_payments.hajj_id')
            ->where('hajjs.type', $this->hajj_type_no)
            ->where('customers.group_id',$id)
            ->get();

        // dd($hajis);
        DB::getQueryLog();
        return view('Admin.group.group-wise-history', compact('hajj_type', 'hajis'));
    }

    public function agent_wise_history($id)
    {
       // dd($id);
        $hajj_type = $this->hajj_type;
        DB::connection()->enableQueryLog();
        $hajis = Hajj::select('hajjs.*')->with(['customer','package','payments'])
            ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
            ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
            ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
            ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
            ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
            ->groupBy('hajjs.id')
            ->groupBy('hajj_payments.hajj_id')
            ->where('hajjs.type', $this->hajj_type_no)
            ->where('customers.agent_id',$id)
            ->get();

        DB::getQueryLog();
        return view('Admin.agent_customer.agent-wise-history', compact('hajj_type', 'hajis'));
    }


    public function date_wise_report()
    {
        $hajjs = [];
        $start ='';


        $end ='';

        $details = DB::table('dev_app_details')->get();
        return view('Admin.reports.date_wise_report',compact('hajjs','details','start','end'));
    }

    public function date_wise_report_show( Request $request)
    {


        $start = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d');
        $end = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d');



       // $hajj_payments = HajjPayment::where('deposit_date','>',$start)->where('deposit_date','<',$end)->get();


        $hajjs = Hajj::select('hajjs.*')->with(['customer','package','payments'])
            ->addSelect(DB::raw('SUM(hajj_payments.amount) as paid_amount'))
            ->addSelect(DB::raw('CAST(packages.total_price - SUM(hajj_payments.amount) AS DECIMAL(10,2)) AS due_amount'))
            ->join('hajj_payments', 'hajjs.id', '=', 'hajj_payments.hajj_id', 'left')
            ->join('packages', 'hajjs.package_id', '=', 'packages.id', 'left')
            ->join('customers', 'hajjs.customer_id', '=', 'customers.id', 'left')
            ->whereDate('hajj_payments.deposit_date','>=',$start)->whereDate('hajj_payments.deposit_date','<=',$end)
            ->groupBy('hajjs.id')
            ->groupBy('hajj_payments.hajj_id')
            ->get();

        $details = DB::table('dev_app_details')->get();

        return view('Admin.reports.date_wise_report',compact('hajjs','details','start','end'));
    }
}
