<!--begin: Form Wizard Step 1-->
<div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
    @csrf
    @if(isset($customer->id)) @method('PUT') @endif
    <div class="row">
        <div class="col-6">
            <div class="form-group row">
                <label for="sur_name"
                       class="col-3 col-form-label text-right text-right">
                    First Name *
                </label>
                <div class="col-9">
                    <input class="form-control" type="text" id="sur_name"
                           name="sur_name"
                           value="{{ old('sur_name', $customer->sur_name) }}"
                           onkeyup="this.value = this.value.toUpperCase();"
                           placeholder="First Name" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="given_name"
                       class="col-3 col-form-label text-right text-right">
                    Last Name *
                </label>
                <div class="col-9">
                    <input class="form-control" type="text" id="given_name"
                           name="given_name"
                           value="{{ old('given_name', $customer->given_name) }}"
                           onkeyup="this.value = this.value.toUpperCase();"
                           placeholder="last Name" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="date_of_birth" class="col-3 col-form-label text-right">
                    Date of Birth *
                </label>
                <div class="col-9">
                    <input class="form-control" type="date" name="date_of_birth"
                           value="{{ $customer->date_of_birth }}"
                            >
                </div>
            </div>


            <div class="form-group row">
                <label for="gender" class="col-3 col-form-label text-right">Gender
                    *</label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker" name="gender"
                            id="gender"
                            @change="changeGender($event)">
                        <option
                                value="1" {{ old('gender', $customer->gender) != 2 ? 'selected' : '' }}>
                            Male
                        </option>
                        <option
                                value="2" {{ old('gender', $customer->gender) == 2 ? 'selected' : '' }}>
                            Female
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="marital_status" class="col-3 col-form-label text-right">
                    Marital Status *</label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker"
                            name="marital_status"
                            id="marital_status" required>
                        <option
                                value="1" {{ old('marital_status', $customer->marital_status) != 2 || old('marital_status', $customer->marital_status) != 3 ? 'selected' : '' }}>
                            Single
                        </option>
                        <option
                                value="2" {{ old('marital_status', $customer->marital_status) == 2 ? 'selected' : '' }}>
                            Married
                        </option>
                        <option
                                value="3" {{ old('marital_status', $customer->marital_status) == 3 ? 'selected' : '' }}>
                            Others
                        </option>
                    </select>
                </div>
            </div>



            <div class="form-group row">
                <label for="type" class="col-3 col-form-label text-right">Type
                    *</label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker" name="type"
                            id="type"
                            @change="changeType($event)">
                        <option
                                value="1" {{ old('type', $customer->type) != 2 && old('type', $customer->type) != 3 ? 'selected' : '' }}>
                            Individual
                        </option>
                        <option
                                value="2" {{ old('type', $customer->type) == 2 ? 'selected' : '' }}>
                            Group
                        </option>
                        <option
                                value="3" {{ old('type', $customer->type) == 3 ? 'selected' : '' }}>
                            Agent
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="type == 2">
                <label for="group_id" class="col-3 col-form-label text-right">
                    Group *
                </label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker" data-size="7"
                            data-live-search="true"
                            name="group_id" id="group_id"
                            @change="getGrpupId($event)">
                        <option
                                value=""
                                {{ old('group_id', $customer->group_id) == null ? 'selected' : '' }}>
                            Select a Group
                        </option>
                        @foreach($groups as $group)
                            <option
                                    value="{{ $group->id }}"
                                    {{ old('group_id', $customer->group_id) == $group->id ? 'selected' : '' }}>
                                {{ $group->group_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row" v-if="type == 3">
                <label for="agent_id" class="col-3 col-form-label text-right">
                    Agent *
                </label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker" data-size="7"
                            data-live-search="true"
                            name="agent_id" id="agent_id">
                        <option
                                value=""
                                {{ old('agent_id', $customer->agent_id) == null ? 'selected' : '' }}>
                            Select a Agent
                        </option>
                        @foreach($agent as $val)
                            <option
                                    value="{{ $val->id }}"
                                    {{ old('agent_id', $customer->agent_id) == $val->id ? 'selected' : '' }}>
                                {{ $val->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label for="mobile"
                       class="col-3 col-form-label text-right">Mobile </label>
                <div class="col-9">
                    <input class="form-control" type="number" id="mobile"
                           name="mobile"
                           value="{{ old('mobile', $customer->mobile) }}"
                           placeholder="017XXXXXXXX">
                </div>
            </div>

            <div class="form-group row">
                <label for="current_district"
                       class="col-3 col-form-label text-right">
                    Current District</label>
                <div class="col-9">
                    <select class="form-control kt-selectpicker" data-size="7"
                            data-live-search="true"
                            name="current_district" id="current_district"
                            @change="getPresentPoliceStation($event)">
                        <option value="">Select Present District</option>
                        @foreach($districts as $district)
                            <option
                                    value="{{ $district->id }}"
                                    {{ old('current_district', $customer->current_district) == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-6">

            <div class="form-group row" style="display: none;">
                <label for="email"
                       class="col-4 col-form-label text-right">Email *</label>
                <div class="col-8">
                    <input class="form-control" type="email" id="email" name="email"
                           value="{{ old('email', $customer->email) }}"
                           placeholder="@">
                </div>
            </div>

            <div class="form-group row" style="display: none;">
                <label for="current_address"
                       class="col-4 col-form-label text-right">Address *</label>
                <div class="col-8">
                    <input class="form-control" type="text" id="current_address" name="current_address"
                           value="{{ old('current_address', $customer->current_address) }}"
                           placeholder="Address">
                </div>
            </div>
            <div class="form-group row" style="display: none">
                <label for="service_type_id"
                       class="col-4 col-form-label text-right">
                    Service Type *
                </label>
                <div class="col-8">
                    <select class="form-control kt-selectpicker"
                            name="service_type_id" id="service_type_id">
                        @foreach($service_types as $service_type)
                            <option
                                    value="{{ $service_type->id }}"
                                    {{ old('passport_id', $customer->service_type_id) == $service_type->id ? 'selected' : '' }}>
                                {{ $service_type->service_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(!$customer->id)

                @php
                    if ($customer->passport){
                        $passport = $customer->passport;
                    }else {
                        $passport = new \App\CustomerPassport();
                    }
                @endphp
                <div class="form-group row">
                    <label for="passport_no" class="col-4 col-form-label text-right">
                        Passport No *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text"
                               id="passport_no" name="passport_no" required
                               value="{{ old('passport_no', $passport->passport_no) }}"
                               placeholder=""
                               @keyup="validatePassport()"
                               v-model="validatePassportNoData.input">
                        <span class="form-text text-danger" v-if="validatePassportNoData.hasError">@{{ validatePassportNoData.message }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="passport_type" class="col-4 col-form-label text-right">
                        Passport Type
                    </label>
                    <div class="col-8">
                        <select class="form-control kt-selectpicker"
                                name="passport_type" id="passport_type">
                            <option
                                    value="1" {{ old('passport_type', $passport->passport_type) == 1 || old('passport_type', $passport->passport_type) == null ? 'selected' : '' }}>
                                General
                            </option>
                            <option
                                    value="2" {{ old('passport_type', $passport->passport_type) == 2 ? 'selected' : '' }}>
                                Government
                            </option>
                            <option
                                    value="3" {{ old('passport_type', $passport->passport_type) == 3 ? 'selected' : '' }}>
                                Others
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display: none">
                    <label for="issue_date"
                           class="col-4 col-form-label text-right">
                        Passport Issue Date
                    </label>
                    <div class="col-8">
                        <input class="form-control kt-datepicker" type="text"
                               id="issue_date" name="issue_date"
                               value="{{ \Carbon\Carbon::parse(old('issue_date', $passport->issue_date))->format('d-m-Y') }}"
                               placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="expiry_date"
                           class="col-4 col-form-label text-right">
                        Passport Expiry Date *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="date" name="expiry_date"
                               value="{{ $passport->expiry_date }}"
                              required>
                    </div>
                </div>
                <div class="form-group row" style="display: none">
                    <label for="issue_location"
                           class="col-4 col-form-label text-right">
                        Issue Location
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text"
                               id="issue_location" name="issue_location"
                               value="{{ old('issue_location', $passport->issue_location) }}"
                               placeholder="">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="package_name" class="col-4 col-form-label">
                        Package Name *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="package_name" name="package_name"
                               placeholder="Package Name" required >
                        {{--value="{{ old('package_name', $package->package_name) }}"--}}
                    </div>
                </div>


                <div class="form-group row">
                    <label for="total_price" class="col-4 col-form-label">Total Price *</label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="total_price" name="total_price"
                               placeholder="" required>
                        {{--value="{{ old('total_price', $package->total_price) }}"--}}
                    </div>
                </div>

            @php
                  $users= Auth::user();
            //dd($users);

            @endphp

              @if($users->user_level == 1 && $users->status == 1 || $users->user_level == 6 && $users->status == 1 )
                <div class="form-group row">
                    <label for="amount" class="col-4 col-form-label">
                        Advance Amount
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="amount"  name="amount"
                               placeholder="">
                        {{--value="{{ old('amount', $hajj_payment->amount) }}"--}}
                    </div>
                </div>

                @else

                    <div class="form-group row">
                        <label for="amount" class="col-4 col-form-label">
                            Advance Amount
                        </label>
                        <div class="col-8"  >
                            <input  class="form-control" type="text" id="amount"  name="amount"
                                   placeholder="" readonly="" >
                            {{--value="{{ old('amount', $hajj_payment->amount) }}"--}}
                        </div>
                    </div>
                @endif




                <div class="col-9 offset-3 mb-4">
                    <div class="row">
                        <label for="image"
                               class="col-3 col-form-label text-right">Image</label>
                        <div class="col-6">
                            <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                                <div class="kt-avatar__holder" id="avatar__holder"
                                     style="@if($customer->photo)background-image: url('{{ asset('uploads/customers/images').'/'. $customer->photo }}');@endif
                                             background-size: contain; width: 150px; height: 200px;"></div>
                                <label class="kt-avatar__upload" data-toggle="kt-tooltip"
                                       title=""
                                       data-original-title="Photo">
                                    <i class="fa fa-pen"></i>
                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg"
                                           id="photo" @change="loadFile($event)">
                                </label>
                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip"
                                      title=""
                                      data-original-title="Cancel avatar">
                                                                    <i class="fa fa-times"></i>
                                                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <p>
                                <b>Image Resolution: </b> 300 x 400 px <br/>
                                <b>Max Size: </b> 500KB
                            </p>
                        </div>
                    </div>
                </div>
            @else

                <div class="form-group row">
                    <label for="passport_no" class="col-4 col-form-label text-right">
                        Passport No *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text"
                               id="passport_no" name="passport_no" required
                               value="{{ old('passport_no', $passport_info->passport_no) }}"
                               placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="passport_type" class="col-4 col-form-label text-right">
                        Passport Type
                    </label>
                    <div class="col-8">
                        <select class="form-control kt-selectpicker"
                                name="passport_type" id="passport_type">
                            <option
                                    value="1" {{ old('passport_type', $passport_info->passport_type) == 1 || old('passport_type', $passport_info->passport_type) == null ? 'selected' : '' }}>
                                General
                            </option>
                            <option
                                    value="2" {{ old('passport_type', $passport_info->passport_type) == 2 ? 'selected' : '' }}>
                                Government
                            </option>
                            <option
                                    value="3" {{ old('passport_type', $passport_info->passport_type) == 3 ? 'selected' : '' }}>
                                Others
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display: none">
                    <label for="issue_date"
                           class="col-4 col-form-label text-right">
                        Passport Issue Date
                    </label>
                    <div class="col-8">
                        <input class="form-control kt-datepicker" type="text"
                               id="issue_date" name="issue_date"
                               value="{{ \Carbon\Carbon::parse(old('issue_date', $passport_info->issue_date))->format('d-m-Y') }}"
                               placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="expiry_date"
                           class="col-4 col-form-label text-right">
                        Passport Expiry Date *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="date"
                             name="expiry_date"
                               value="{{$passport_info->expiry_date }}"
                               placeholder="" required>
                    </div>
                </div>
                <div class="form-group row" style="display: none">
                    <label for="issue_location"
                           class="col-4 col-form-label text-right">
                        Issue Location
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text"
                               id="issue_location" name="issue_location"
                               value="{{ old('issue_location', $passport_info->issue_location) }}"
                               placeholder="">
                    </div>
                </div>




                <div class="form-group row">
                    <label for="package_name" class="col-4 col-form-label">
                        Package Name *
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="package_name" name="package_name"
                               @if($haji) value="{{ old('package_name', $haji->package->package_name) }}" @endif placeholder="Package Name" required>

                    </div>
                </div>

                <div>
                    <input class="form-control" type="hidden" id="package_id" name="package_id"
                           @if($haji) value="{{ old('package_name', $haji->package->id) }}" @endif required>
                </div>
                <div class="form-group row">
                    <label for="total_price" class="col-4 col-form-label">Total Price *</label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="total_price" name="total_price"
                               @if($haji) value="{{ old('total_price', $haji->package->total_price) }}" @endif  placeholder="" required>
                        {{--value="{{ old('total_price', $package->total_price) }}"--}}
                    </div>
                </div>
                @php
                    if($haji){
                        $amount = DB::table('hajj_payments')
                                 ->where('hajj_id',$haji->id)
                                 ->select('amount')
                                 ->get()->sum('amount');
                    }
                @endphp




                @php
                    $users= Auth::user();

                @endphp
                @if($users->user_level == 1 && $users->status == 1 || $users->user_level == 6 && $users->status == 1 )
                <div class="form-group row">
                    <label for="amount" class="col-4 col-form-label">
                        Paid Amount
                    </label>
                    <div class="col-8">
                        <input class="form-control" type="text" id="amount" name="amount"
                               @if($haji) value="{{ old('amount', $amount) }}" @endif placeholder="" >
                    </div>
                </div>

                @else

                    <div class="form-group row">
                        <label for="amount" class="col-4 col-form-label">
                            Paid Amount
                        </label>
                        <div class="col-8">
                            <input class="form-control" type="text" id="amount" name="amount"
                                   value="{{ old('amount', $amount) }}" placeholder="" readonly="" >
                        </div>
                    </div>
               @endif
                <div class="col-9 offset-3 mb-4">
                    <div class="row">
                        <label for="image"
                               class="col-3 col-form-label text-right">Image</label>
                        <div class="col-6">
                            <div class="kt-avatar kt-avatar--outline" id="kt_user_avatar_1">
                                <div class="kt-avatar__holder" id="avatar__holder"
                                     style="@if($customer->photo)background-image: url('{{ asset('uploads/customers/images').'/'. $customer->photo }}');@endif
                                             background-size: contain; width: 150px; height: 200px;"></div>
                                <label class="kt-avatar__upload" data-toggle="kt-tooltip"
                                       title=""
                                       data-original-title="Photo">
                                    <i class="fa fa-pen"></i>
                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg"
                                           id="photo" @change="loadFile($event)">
                                </label>
                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip"
                                      title=""
                                      data-original-title="Cancel avatar">
                                                                    <i class="fa fa-times"></i>
                                                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <p>
                                <b>Image Resolution: </b> 300 x 400 px <br/>
                                <b>Max Size: </b> 500KB
                            </p>
                        </div>
                    </div>
            </div>

                {{--<div class="form-group row">--}}
                {{--<label for="passport_id"--}}
                {{--class="col-4 col-form-label text-right">--}}
                {{--Select Passport--}}
                {{--</label>--}}
                {{--<div class="col-8">--}}
                {{--<select class="form-control kt-selectpicker" data-size="7"--}}
                {{--data-live-search="true"--}}
                {{--name="passport_id"--}}
                {{--id="passport_id">--}}
                {{--<option--}}
                {{--value=""--}}
                {{--{{ old('passport_id', $customer->passport_id) === null ? 'selected' : '' }}>--}}
                {{--Select a Passport--}}
                {{--</option>--}}
                {{--@foreach($passports as $passport)--}}
                {{--<option--}}
                {{--value="{{ $passport->id }}"--}}
                {{--{{ old('passport_id', $customer->passport_id) == $passport->id ? 'selected' : '' }}>--}}
                {{--{{ $passport->passport_no }}--}}
                {{--</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--</div>--}}
            @endif
        </div>
    </div>
</div>

<!--end: Form Wizard Step 1-->
