<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('uploads/project-info/') . '/' . project()->app_icon}}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>

        thead{
           background: #b6c1cb;
        }
    </style>
</head>
<body>
<div  style="margin-left: 100px; min-height: 300px;">
<h1>  Check Passport </h1>

 <form  action="{{route('passport.check.status')}}" method="post"
           class="kt-form kt-form--label-right">
    @csrf

          {{--<label for="fname">Phone Number:</label>--}}
          {{--<input type="text"  id="fname"  name="phone" placeholder="Phone Number" required><br><br>--}}

          {{--<label for="lname"> Passport Number:</label>--}}
          {{--<input type="text" id="lname"  name="passport_no" placeholder="Passport Number" required><br><br>--}}

          {{--<input type="submit" value="Submit">--}}

<div class="col-md-4">
     <div class="form-group">
         <label for="m_date"
                class="col-6 col-form-label text-right">
             Phone Number: </label>
         <div class="col-6">
             <input type="text" class="form-control"   id="fname"  name="phone" placeholder="Phone Number" required>
         </div>
     </div>

     <div class="form-group ">
         <label for="m_ins_name"
                class="col-6 col-form-label text-right">
             Passport Number: </label>
         <div class="col-6">
             <input type="text" class="form-control"    id="lname"  name="passport_no" placeholder="Passport Number" required>
         </div>
     </div>
     <div>
         <input type="submit" class="btn btn-success" value="Submit">
     </div>

</div>





   </form>
  </div>
@if(session()->has('not_permitted'))
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

@php


        if($passports){
            $id = $passports[0]->passport_id;

            $customers = DB::table('customers')->where('passport_id',$id)->first();
             // dd($customers);
            $customer_id  = $customers->id;

            $hajj = DB::table('hajjs')
                  ->join('packages','hajjs.package_id','=','packages.id')
                   ->where('customer_id',$customer_id)
                   ->select('hajjs.id','packages.total_price')
                   ->first();
           $hajj_id = $hajj->id;

           $payments = DB::table('hajj_payments')
                   ->where('hajj_id',$hajj_id)
                   ->get();
                  // dd($payments);
    }
@endphp


@if($passports)
            <div style="text-align: center">
                <h1>{{ $passports[0]->full_name }} - {{ $passports[0]->passport_no }}</h1>
                <hr>
            </div>
              <div class="row">
                <div class="col-md-5" style="margin-left:102px;">
                    <h4>Passport Status</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th> Passport Current Status</th>
                            <th>Last Status Updated</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($passports as $passport)
                            <tr id="tr-{{ $passport->id }}">
                                @php
                                $status_name =DB::table('passport_statuses')->where('id',$passport->passport_status_id)->first();
                                @endphp
                                <td>{{$status_name->status_name}}</td>
                                <td>{{$passport->date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                  <div class="col-md-5" style="margin-left: 70px;">
                      <h4>Medical Info</h4>
                      <table class="table table-bordered">
                          <thead>
                          <tr>
                              <th>Medical Date </th>
                              <th>Medical Expiry Date</th>
                              <th>Medical Institute</th>
                              <th>Medical Status</th>
                          </tr>
                          </thead>
                          <tbody>

                          @php
                        //  dd($customers)

                          @endphp
                          @if($customers)
                              <tr id="tr-{{ $customers->id }}">
                                  <td>{{  $customers->m_date }}</td>
                                  <td>{{ $customers->m_expiry_date }}</td>
                                  <td>{{$customers->m_ins_name}}</td>
                                  @if( $customers->m_date !=null ||  $customers->m_expiry_date !=null )
                                  @if($customers->m_status == 1 )
                                      <td>Fit</td>
                                  @else
                                      <td>Unfit</td>
                                  @endif
                                  @endif

                              </tr>
                          @endif
                          </tbody>
                      </table>
                  </div>


       </div>
@endif



@php
    if($passports_id != null){
           $id=$passports_id->id;
           $customers = DB::table('customers')->where('passport_id',$id)->first();

           }

@endphp

   @if($passports_id )
            <div class="row">

                <div class="col-md-5" style="margin-left: 102px;">
                    <h4> Flight Info</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Airlines</th>
                            <th>PNR</th>
                            <th> Flight Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($customers)
                            <tr id="tr-{{ $customers->id }}">
                                <td>{{$customers->airlines}} </td>
                                <td>{{$customers->pnr}} </td>
                                <td>{{ $customers->flight_date}}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-md-5" style="margin-left: 70px;">
                    <h4> Stamping Info</h4>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Stamping Date</th>
                            <th>Visa-Expire Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($customers)
                        <tr id="tr-{{ $customers->id }}">
                              <td>{{ $customers->stamping_date  }}</td>
                             <td>{{ $customers->expire_date }}</td>
                          </tr>

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
         <div class="row">
             @if($passports[0]->type != 3 && $passports[0]->agent_id == null )
                 <div class="col-md-5" style="margin-left:102px;">
                     <h4> Payment History</h4>
                     <table class="table table-bordered">
                         <thead>
                         <tr>
                             <th>Date</th>
                             <th>Paid</th>
                             <th>Due</th>

                         </tr>
                         </thead>
                         <tbody>
                         @if($payments)
                             @foreach($payments as $payment)
                                 @if($payment->amount > 0)
                                 <tr id="tr-{{ $payment->id }}">
                                     <td>{{ $payment->deposit_date }}</td>
                                     <td>{{$payment->amount}} BDT</td>
                                     <td>{{ ($hajj->total_price) - ($payment->amount)  }} BDT</td>
                                 </tr>
                                 @endif
                             @endforeach
                         @endif
                         </tbody>
                     </table>
                 </div>
             @endif
       </div>
    @endif
</body>
</html>


