<!--begin: Form Wizard Step 3-->


<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
    <div class="row">
        <div class="col-6">
            <div style="text-align: center">
                <h5 >Flight Details</h5>
            </div>
            <hr>

            <div class="form-group row">
                <label for="airlines"
                       class="col-4 col-form-label text-right">
                    AirLines  </label>
                <div class="col-8">
                    <input class="form-control" type="text" id="airlines" name="airlines" value="{{  $customer->airlines }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="pnr"
                       class="col-4 col-form-label text-right">
                    PNR </label>
                <div class="col-8">
                    <input class="form-control" type="text" id="pnr" name="pnr" value="{{  $customer->pnr }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="flight_date"
                       class="col-4 col-form-label text-right">
                    Flight Date </label>
                <div class="col-8">
                    <input class="form-control" type="date" id="flight_date" name="flight_date" value="{{  $customer->flight_date }}">
                </div>
            </div>


        </div>

        <div class="col-6">


            <div style="text-align: center">
                <h5 >Stamping Details</h5>
            </div>
            <hr>
            <div class="form-group row">
                <label for="stamping_date"
                       class="col-4 col-form-label text-right">
                    Stamping Date </label>
                <div class="col-8">
                    <input class="form-control" type="date" id="stamping_date" name="stamping_date" value="{{  $customer->stamping_date }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="expire_date"
                       class="col-4 col-form-label text-right">
                    Visa-Expire Date </label>
                <div class="col-8">
                    <input class="form-control" type="date" id="expire_date" name="expire_date" value="{{  $customer->expire_date }}">
                </div>
            </div>


            {{--@if( !$customer->id)--}}
            {{--<div class="form-group row">--}}
                {{--<label for="package_id" class="col-4 col-form-label">Package *</label>--}}
                {{--<div class="col-8">--}}
                {{--<select class="form-control kt-selectpicker" data-size="5" data-live-search="true" name="package_id" id="package_id">--}}
                        {{--<option>Select a Package </option>--}}
                        {{--@foreach($packages as $package)--}}
                            {{--<option value="{{$package->id }}" >--}}
                                {{--{{ $package->package_name }}--}}
                            {{--</option>--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}



            {{--<div class="form-group row" style="display: none;">--}}
                {{--<label for="type" class="col-4 col-form-label">Payment Type*</label>--}}
                {{--<div class="col-8">--}}
                    {{--<input class="form-control" type="text" value="1" name="payment_type" placeholder="" required>--}}
                {{--</div>--}}
            {{--</div>--}}


            {{--<div class="form-group row">--}}
                {{--<label for="depositor_name" class="col-4 col-form-label">--}}
                    {{--Depositor Name *--}}
                {{--</label>--}}
                {{--<div class="col-8">--}}
                    {{--<input class="form-control" type="text" id="depositor_name" value="Self" name="depositor_name"--}}
                            {{--placeholder="" required>--}}
                    {{--value="{{ old('depositor_name', $hajj_payment->depositor_name) }}"--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--@php--}}

                {{--$todays = \Carbon\Carbon::today();--}}
                {{--$today = \Carbon\Carbon::parse($todays)->format('Y-m-d');--}}
            {{--@endphp--}}

            {{--<div class="form-group row">--}}
                {{--<label for="deposit_date" class="col-4 col-form-label">Deposit Date *</label>--}}
                {{--<div class="col-8">--}}
                    {{--<input class="form-control" type="date" id="deposit_date" value="{{$today}}" name="deposit_date"--}}
                           {{--placeholder="">--}}
                {{--</div>--}}
            {{--</div>--}}



            {{--<div class="form-group row">--}}
                {{--<label for="amount" class="col-4 col-form-label">--}}
                    {{--Amount *--}}
                {{--</label>--}}
                {{--<div class="col-8">--}}
                    {{--<input class="form-control" type="text" id="amount" name="amount"--}}
                            {{--placeholder="" required>--}}
                    {{--value="{{ old('amount', $hajj_payment->amount) }}"--}}
                {{--</div>--}}
            {{--</div>--}}


            {{--<div class="form-group row" style="display: none;">--}}
                {{--<label for="status" class="col-4 col-form-label">Payment Status *</label>--}}
                {{--<div class="col-8">--}}
                    {{--<input class="form-control" type="text"  value="1" name="payment_status"--}}
                           {{--placeholder="" required>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--@endif--}}
        </div>

    </div>

    <div class="row">


    </div>
</div>

<!--end: Form Wizard Step 3-->
