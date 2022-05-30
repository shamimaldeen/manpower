<?php

namespace App\Http\Controllers\BackEndCon;

use App\AttachedDocument;
use App\Customer;
use App\Package;
use App\CustomerPassport;
use App\District;
use App\Group;
use App\Agent;
use App\Hajj;
use App\Bank;
use App\HajjPayment;

use App\Http\Controllers\Controller;
use App\MahramRelation;
use App\PassportHistory;
use App\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customers = Customer::all();
        return view('Admin.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $groups = Group::select('id', 'group_name')->get();
        $agent = Agent::select('id', 'name')->get();
        $districts = District::orderBy('name')->get();
        $registered_customers = Customer::all();
        $service_types = ServiceType::all();
        $mahram_relationships = MahramRelation::all();
        $packages = Package::where('package_type', $this->hajj_type_no)->where('status', 1)->get();

       // $haji = Hajj::with('customer')->findOrFail($hajj_id);
        $haji = Hajj::all();
       // dd($haji);
        $banks = Bank::all();
        //$hajj_payment = HajjPayment::FindOrFail($id);
        $hajj_payment = HajjPayment::all();
       // dd($hajj_payment);
        return view('Admin.customer.form', compact('hajj_payment','banks','haji','packages','agent','districts', 'groups', 'registered_customers', 'service_types', 'mahram_relationships'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $toValidate = array(
            'given_name' => 'required',
            'sur_name' => 'required',
            'date_of_birth' => 'required',
            'package_name' => 'required',
            'total_price' => 'required',
            'expiry_date' => 'required',
            'passport_no' => 'required',

//            'photo' => 'mimes:jpeg,jpg,png|max:500',

        );
        $toValidate['nrb_residence_country'] = ($request->resident_type == 'NRB') ? 'required' : 'nullable';
        $validator = Validator::make($request->all(), $toValidate);
        $data = $request->all();
       // dd($data);

        $data['date_of_birth'] = Carbon::parse($data['date_of_birth'])->format('Y-m-d');
        $data['email'] = strtolower($data['email']);
        $data['sur_name'] = strtoupper($data['sur_name']);
        $data['given_name'] = strtoupper($data['given_name']);
        $data['amount'] = $data['amount'];

        if ($request->ajax()) {

            return response()->json($this->ajaxStore($data, $validator, $request), 200);
        } else {

            // if request is not a AJAX request
            $validator->validate();
            if ($request->hasFile('photo')) {
                $upload = $this->uploadFile($request->photo, 'uploads/customers/images');
                if ($upload) {
                    $data['photo'] = $upload;
                } else {
                    Session::flash('error', 'Whoops! Failed to upload photo');
                    return redirect()->back()->withInput();
                }
            }
            $customer = Customer::create($data);
            if ($customer) {
                Session::flash('success', 'Customer Created Successfully');
                return redirect()->route('groups.index');
            } else {
                Session::flash('error', 'Whoops! Failed to Create Customer');
                return redirect()->back()->withInput();
            }
        }
    }

    /**
     * @param $validator
     * @param $request
     * @return array|bool
     */
    private function ajaxStore($data, $validator, Request $request) {
        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 'success' => false, 'type' => 'error', 'status' => 422];
        }
        if ($request->hasFile('photo')) {
            $upload = $this->uploadFile($request->photo, 'uploads/customers/images');
            if ($upload) {
                $data['photo'] = $upload;
            } else {
                return ['message' => 'Whoops! Failed to upload photo', 'success' => false, 'type' => 'error', 'status' => 422];
            }
        }

        if (isset($request->passport_no)) {
            if ($passport = $this->createPassport($request)) {
                if ($passport['success'] == false) {
                    return $passport;
                } else {
                    $data['passport_id'] = $passport['data'];
                }
            }
        }
        $remove_passport_data = array(
            'passport_no', 'passport_type','expiry_date',
        );
        array_push($remove_passport_data, 'document', 'document_title');
        foreach ($remove_passport_data as $key) {
            unset($data[$key]);
        }

        $customer = Customer::create($data);
        $data['package_name'] =$data['package_name'] ;
        $data['package_type'] = 1 ;
        $data['total_price'] =$data['total_price'] ;
        $data['status'] = 1 ;
        $packages = Package:: create($data);


        $packages = Package::orderBy('id','DESC')->first();
        $customer_id = Customer::orderBy('id','DESC')->first();
        $data['type'] = $this->hajj_type_no;

        $data['customer_id'] = $customer_id->id;
        $data['package_id'] = $packages->id;
       // $data['payment_status'] = $data['payment_status'] ;
        $last_haji = Hajj::where('type', $this->hajj_type_no)->orderBy('id','DESC')->first();
        $data['serial_no'] = 'CS' . '-' . (($last_haji ? explode('-', $last_haji->serial_no)[1] : 1000) + 1);
        $haji = Hajj::create($data);

        $haji_info = Hajj::orderBy('id','DESC')->first();


        $data['hajj_id'] = $haji_info->id;
        $data['type'] = 1 ;
        if ($data['amount'] == null){
           // dd('ok');
            $data['amount'] = 0;
        }else{
           // dd('done');
            $data['amount'] = $data['amount'] ;
        }

        $data['deposit_date'] = Carbon::parse($haji_info->created_at)->format('Y-m-d');
        $data['status'] = 1 ;


         HajjPayment::create($data);



     //   dd($customer);
        $updated_customer = $customer->update(['serial_no' => $customer->id + 1000, 'tracking_no' => 'SN' . getSixDigitNumber($customer->id)]);
        if ($updated_customer) {
            $uploadDocument = $this->uploadDocuments($request, $customer);
            if ($uploadDocument['success'] == false) {
                return ['data' => $customer, 'message' => 'Customer Created Successfully But failed to upload documents', 'success' => true, 'type' => 'success', 'status' => 200];
            }
            return ['data' => $customer, 'message' => 'Customer Created Successfully', 'success' => true, 'type' => 'success', 'status' => 200];
        } else {
            return ['message' => 'Whoops! Failed to create Customer.', 'success' => false, 'type' => 'error', 'status' => 400];
        }
    }

    public function show($id)
    {
        $customer = Customer::with('group', 'passport', 'maharam', 'dependent', 'documents', 'hajj.payments')->FindOrFail($id);

        //dd($customer);

        $customer_address = Customer::where('customers.id', '=', $id)
                                ->join('upazilas', 'upazilas.id', '=', 'customers.current_police_station')
                                ->first();
        $customer_district = Customer::where('customers.id', '=', $id)
                                ->join('districts', 'districts.id', '=', 'customers.current_district')
                                ->first();
        $permanent_upazila = Customer::where('customers.id', '=', $id)
                                ->join('upazilas', 'upazilas.id', '=', 'customers.permanent_police_station')
                                ->first();
        $permanent_district = Customer::where('customers.id', '=', $id)
                                ->join('districts', 'districts.id', '=', 'customers.permanent_district')
                                ->first();

        // return $customer_address; exit();
        return view('Admin.customer.show', compact('customer', 'customer_address', 'customer_district', 'permanent_upazila', 'permanent_district'));
    }

    public function edit($id)
    {
        $customer = Customer::with('documents')->findOrFail($id);
       // dd($customer);
        $haji = '';
        $hajj_payment = '';

        $hajj = Hajj::where('customer_id',$customer->id)->first();

       if ($hajj != null) {
           $haji = Hajj::with('customer', 'package')->findOrFail($hajj->id);
           $hajj_payment = HajjPayment::where('hajj_id', $haji->id)->first();
       }
        $groups = Group::select('id', 'group_name')->get();
        $agent = Agent::select('id', 'name')->get();
        $districts = District::orderBy('name')->get();
        $registered_customers = Customer::where('id', '<>', $id)->get();
        $passports = CustomerPassport::all();
        $passport_info = CustomerPassport::where('id',$customer->passport_id)->first();
        $packages = Package::where('package_type', $this->hajj_type_no)->where('status', 1)->get();
        $service_types = ServiceType::all();
        $mahram_relationships = MahramRelation::all();
        return view('Admin.customer.form', compact('hajj_payment','packages','haji','hajj','passport_info','agent','customer', 'districts', 'groups', 'registered_customers', 'passports', 'service_types', 'mahram_relationships'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), array(
            'given_name' => 'required',
            'sur_name' => 'required',
            'date_of_birth' => 'required',
            'package_name' => 'required',
            'total_price' => 'required',
//            'photo' => 'mimes:jpeg,jpg,png|max:500',
            'passport_no' => 'required',
            'expiry_date' => 'required',
        ));

        $data = $request->all();
       // dd($data);

        $data['date_of_birth'] = Carbon::parse($data['date_of_birth'])->format('Y-m-d');
//        $data['email'] = strtolower($data['email']);
        $data['passport_no'] = $request->passport_no;
        $data['passport_type'] = $request->passport_type;
        $data['issue_date'] = Carbon::parse($data['issue_date'])->format('Y-m-d');
        $data['expiry_date'] = Carbon::parse($data['expiry_date'])->format('Y-m-d');

        $data['amount'] = $request->amount;
        $data['payment_type'] = $request->payment_type;
       // $data['deposit_date'] = $request->deposit_date;

        $data['payment_status'] = $request->payment_status;
       // $data['issue_location'] = $request->issue_location;

        $customer = Customer::find($id);
      //  dd($customer);
        $passport = CustomerPassport::find($customer->passport_id);
        $passport->update($data);


        $package = Package::where('id',$request->package_id)->first();
        $data['package_name'] = $request->package_name;
        $data['package_type'] = 1 ;
        $data['total_price'] = $request->total_price;
        $data['status'] = 1 ;
        $package->update($data);
        $haji_info = Hajj::where('customer_id',$id)->where('package_id',$request->package_id)->first();

        $hajjpayment = HajjPayment::where('hajj_id',$haji_info->id)->orderBy('id','DESC')->first();
        $data['amount'] = $request->amount;
        $data['deposit_date'] = Carbon::parse($haji_info->updated_at)->format('Y-m-d');
       // dd( $data['deposit_date']);
        $hajjpayment->update($data);
        if ($request->ajax()) {

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors(), 'success' => false, 'type' => 'error', 'status' => 422], 200);
            }

            $remove_data = array();
            array_push($remove_data, 'document', 'document_title');
            foreach ($remove_data as $key) {
                unset($data[$key]);
            }
            $customer = Customer::find($id);
            if ($request->hasFile('photo')) {
                $upload = $this->uploadFile($request->photo, 'uploads/customers/images');
                if ($upload) {
                    File::delete('uploads/customers/images/' . $customer->photo);
                    $data['photo'] = $upload;
                } else {
                    return response()->json(['message' => 'Whoops! Failed to upload photo', 'success' => false, 'type' => 'error', 'status' => 422], 200);
                }
            }
            if (isset($request->document)) {
                foreach ($request->document as $key => $document){
                    $attachDocument = new \App\Services\AttachedDocument();
                    $file_name = $attachDocument->uploadDocument($document);
                    $attachDocumentData = [
                        'title' => $request->document_title[$key],
                        'document' => $file_name
                    ];
                    if (!$attachDocument->attachDocument($customer->id, $attachDocumentData)) {
                        return response()->json(['message' => 'Whoops! Failed to upload document', 'success' => false, 'type' => 'error', 'status' => 422], 200);
                    }
                }
            }
            $updated = false;
            if ($customer) {
                $updated = $customer->update($data);
            }


            if ($updated) {
                return response()->json(['data' => $customer, 'message' => 'Customer updated Successfully', 'success' => true, 'type' => 'success', 'status' => 200], 200);
            } else {
                return response()->json(['message' => 'Whoops! Failed to update Customer.', 'success' => false, 'type' => 'error', 'status' => 400], 200);
            }
        } else { // if request is not a AJAX request

            $validator->validate();
            $customer = Customer::findOrFail($id);
            if ($request->hasFile('photo')) {
                $upload = $this->uploadFile($request->photo, 'uploads/customers/images');
                if ($upload) {
                    File::delete('uploads/customers/images/' . $customer->photo);
                    $data['photo'] = $upload;
                } else {
                    Session::flash('error', 'Whoops! Failed to upload photo');
                    return redirect()->back()->withInput();
                }
            }
            $updated = false;
            if ($customer) {
                $updated = $customer->update($data);

            }

            if ($updated) {
                Session::flash('success', 'Customer updated Successfully');
                return redirect()->route('groups.index');
            } else {
                Session::flash('error', 'Whoops! Failed to update Customer');
                return redirect()->back()->withInput();
            }
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
        $customer = Customer::find($id);
         $passport_id = $customer->passport_id;
         if ($passport_id){
             $passport = CustomerPassport::where('id',$passport_id)->first();
             if ($passport !=null){
                 $passport->delete();
             }
             $passport_history = PassportHistory::where('passport_id',$passport_id)->get();
             if ($passport_history){
                 foreach ($passport_history as $data) {
                     $hidtory_id = $data->id;
                     $p_h = PassportHistory::where('id', $hidtory_id)->first();
                     $p_h->delete();
                 }
             }
         }

         $hajj = Hajj::where('customer_id',$id)->first();
            if ($hajj !=null){
                $hajj_payment = HajjPayment::where('hajj_id',$hajj->id)->get();
                if ($hajj_payment) {
                    foreach ($hajj_payment as $data) {
                        $hajjid = $data->id;
                        $h_p = HajjPayment::where('id', $hajjid)->first();
                        $h_p->delete();
                    }
                }
               $package = Package::where('id',$hajj->package_id)->first();
                if ($package !=null){
                    $package->delete();
                }

                $hajj->delete();

             }


             $attach = AttachedDocument::where('customer_id',$id)->get();
            if ($attach){
                foreach ($attach as $data) {
                    $attach_id = $data->id;
                    $attch = AttachedDocument::where('id', $attach_id)->first();
                    $attch->delete();
                }
            }


        if ($customer->delete()) {
            File::delete('uploads/customers/images/' . $customer->photo);
            return response()->json(['success' => true, 'message' => 'Customer Deleted Successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Whoops! Customer Not Deleted'], 200);
        }
    }

    /**
     * Upload File Universal Function
     *
     * @param UploadedFile $file
     * @param string $UploadPath
     * @return \Symfony\Component\HttpFoundation\File\File|bool
     */
    private function uploadFile(UploadedFile $file, string $UploadPath = 'uploads')
    {
        $fileName = time() . ' - ' . $file->getClientOriginalName();
        $uploaded = $file->move(public_path($UploadPath), $fileName);
        if ($uploaded) {
            list($width, $height, $type, $attr) = getimagesize(public_path($UploadPath).'/'.$fileName);
            if ($attr != 'width="300" height="400"') {
                $image = Image::make(public_path($UploadPath).'/'.$fileName)->resize(300, 400)->save();
            }
            return $fileName;
        } else {
            return false;
        }
    }

    private function uploadDocuments(Request $request, $customer)
    {
        if (isset($request->document)) {
            foreach ($request->document as $key => $document){
                $attachDocument = new \App\Services\AttachedDocument();
                $file_name = $attachDocument->uploadDocument($document);
                $attachDocumentData = [
                    'title' => $request->document_title[$key],
                    'document' => $file_name
                ];
                if (!$attachDocument->attachDocument($customer->id, $attachDocumentData)) {
                    return ['message' => 'Whoops! Failed to upload document', 'success' => false, 'type' => 'error', 'status' => 422];
                }
            }
            return ['message' => 'Document Uploaded Successfully', 'success' => true, 'type' => 'success', 'status' => 200];
        } else {
            return ['message' => 'Document Not Submitted', 'success' => true, 'type' => 'success', 'status' => 200];
        }
    }

    /**
     * Create Passport
     *
     * @param Request $request
     * @return array|bool
     */
    private function createPassport(Request $request)
    {
        $validator = Validator::make($request->all(), array(
            'passport_no' => 'required|unique:customer_passports',
            'date_of_birth' => 'required',
            'passport_type' => 'required',
            'expiry_date' => 'required',
        ));
        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors(), 'type' => 'validation-error', 'status' => 422];
        }
        $data = array(
            'full_name' => $request->sur_name . ' ' . $request->given_name,
            'passport_no' => $request->passport_no,
            'date_of_birth' => Carbon::parse($request->date_of_birth)->format('Y-m-d'),
            'passport_type' => $request->passport_type,
            'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d'),

        );

        $passport = CustomerPassport::create($data);
        if ($passport) {
            return ['success' => true, 'data' => $passport->id, 'status' => 200];
        } else {
            return ['success' => false, 'errors' => 'Error in creating passport!', 'type' => 'create-error', 'status' => 422];
        }
    }

    public function customerInfoPDF($id)
    {
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $customer = Customer::with('group', 'passport', 'maharam', 'dependent')->FindOrFail($id);
//        return view('Admin.customer.customer-info-pdf', compact('customer'));
        $pdf = PDF::loadView('Admin.customer.customer-info-pdf', compact('customer'))
            ->setOptions([
                'defaultPaperSize' => 'A4',
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => true,
                'isPhpEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'fullBase' => true,
            ]);
        return $pdf->stream();
//        return $pdf->download('invoice.pdf');
    }




    public function customerInfoPrint($id)
    {
        $customer = Customer::with('group', 'passport', 'maharam', 'dependent', 'documents', 'hajj.payments')->FindOrFail($id);

        // dd($customer);
        $customer_address = Customer::where('customers.id', '=', $id)
            ->join('upazilas', 'upazilas.id', '=', 'customers.current_police_station')
            ->first();
        $customer_district = Customer::where('customers.id', '=', $id)
            ->join('districts', 'districts.id', '=', 'customers.current_district')
            ->first();
        $permanent_upazila = Customer::where('customers.id', '=', $id)
            ->join('upazilas', 'upazilas.id', '=', 'customers.permanent_police_station')
            ->first();
        $permanent_district = Customer::where('customers.id', '=', $id)
            ->join('districts', 'districts.id', '=', 'customers.permanent_district')
            ->first();
        $details = DB::table('dev_app_details')->get();

        // return $customer_address; exit();
        return view('Admin.customer.print', compact('details','customer', 'customer_address', 'customer_district', 'permanent_upazila', 'permanent_district'));
    }

}
