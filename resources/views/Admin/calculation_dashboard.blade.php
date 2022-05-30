@extends('Admin.layouts.app')
@section('page_title', 'Dashboard')
@section('page_tagline', 'Admin Dashboard')
@push('css')
    <style>
        .kt-widget17 .kt-widget17__stats {
            margin: 0 auto 0;
            width: 90%;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                </div>
                <div class="kt-portlet__body" style="padding-bottom: 20px; background-color: #f1f2f7;">
                    <div class="kt-widget17">
                        <h3 style="color: black ; margin-left: 80px;"> Total Summary</h3>
                        <div class="kt-widget17__stats">
                            <div class="kt-widget17__items">
                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    							Total Amount
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $totalCounts->total_amount }} BDT
    						</span>
                                </div>

                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    							Total Paid Amount
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $totalCounts->total_paid_amount }} BDT
    						</span>
                                </div>

                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    							Total Due Amount
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $totalCounts->total_due_amount }} BDT
    						</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="kt-portlet__body" style="padding-bottom: 20px; background-color: #f1f2f7; margin-top: 30px;">
                    <div class="kt-widget17">
                        <h3 style="color: black ; margin-left: 80px;"> Agent Summary</h3>
                        <form action="{{ route('agent_wise.calculation') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label style="color: black" for="agent_id" class="col-1 col-form-label text-right">
                                    Agent
                                </label>
                                <div class="col-3">
                                    <select class="form-control kt-selectpicker" data-size="7"
                                            data-live-search="true"
                                            name="agent_id" id="agent_id">
                                        @foreach($agents as $val)
                                            <option value="{{ $val->id }}"> {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>

                        @if(count($agent_calculation) > 0 )

                        @php
                         $agent_total = 0;
                         $agent_total_paid = 0;
                         $agent_total_due = 0;
                         foreach ($agent_calculation as $result){
                           $agent_total+= $result->package->total_price;
                            $agent_total_paid += $result->paid_amount;
                            $agent_total_due += $result->due_amount;

                         }

                        @endphp
                        @endif

                        @if(count($agent_calculation) > 0 )

                        <div class="kt-widget17__stats">
                            <div class="kt-widget17__items">
                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    					          Agent Total Amount
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $agent_total }} BDT
    						</span>
                                </div>

                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    							Agent Total Paid
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $agent_total_paid }} BDT
    						</span>
                                </div>

                                <div class="kt-widget17__item">
                                    <i class="fa fa-money-bill-wave" style='font-size: 40px; color: #e73930;'></i>
                                    <span class="kt-widget17__subtitle">
    							Agent Total Due
    						</span>
                                    <span class="kt-widget17__desc" style='font-size: 2rem; font-weight: 700;'>
                                   {{ $agent_total_due }} BDT
    						</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>


            </div>
            <!--end:: Widgets/Activity-->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#dashboard-mm').addClass('kt-menu__item--active');
    </script>
@endpush
