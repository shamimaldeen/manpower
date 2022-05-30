@extends('Admin.layouts.app')

@push('css')
    <!-- Datatables -->
    <link href="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet">
@endpush

@section('page_title', $hajj_type)
@section('page_tagline', $hajj_type . ' List')

@section('content')
    @include('dashboard::components.delete-modal')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon"><i class="kt-font-brand flaticon2-line-chart"></i></span>
                    <h3 class="kt-portlet__head-title">
                        {{ $hajj_type }} List
                    </h3>
            </div>

            <div class="mt-3" style="margin-left: 380px;margin-top: 30px;">
                <a href="{{ route('customer.create') }}" class="btn btn-label-success btn-sm btn-upper">
                    <i class="fa fa-plus"></i> Add New Customer
                </a>
            </div>
            {{--<div class="float-right mt-3">--}}
                {{--<a href="{{ $hajj_type == 'Customer Service' ? route('package-service.create') : route('omra-haji.create') }}" class="btn btn-label-success btn-sm btn-upper">--}}
                    {{--<i class="fa fa-plus"></i> Add New {{ $hajj_type }}--}}
                {{--</a>--}}
            {{--</div>--}}
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table
                class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
                <thead>
                <tr>
                    <th>Customer Name</th>
                    {{--<th>Serial No.</th>--}}
                    <th>Type</th>
                    <th>Agent/Group Name</th>
                    <th>Passport No</th>
                    <th>Mobile Number</th>
                     <th>Package Name</th>
                     <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Remaining Amount</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($hajis as $haji)
                    @php

                   $passport_info =  DB::table('customer_passports')->where('id',$haji->customer->passport_id)->first();
                   // dd($passport_info);

                    @endphp

                    <tr id="tr-{{ $haji->id }}">
                        <td>
                        <a style="color: #ef4023;font-weight: bold;" href="{{ route('customer.show', $haji->customer->id) }}">
                            {{ $haji->customer->sur_name }}  {{ $haji->customer->given_name }}
                        </a>

                        </td>
                        {{--<td>{{ $haji->serial_no }}</td>--}}
                         @if($haji->customer->type == 1)
                        <td> Individual</td>
                        @elseif($haji->customer->type == 2)
                            <td> Group</td>
                        @else
                            <td> Agent</td>
                        @endif


                        @php
                        if($haji->customer->type == 3){
                            $agent_id = $haji->customer->agent_id;
                              $agent= DB::table('agents')->where('id',$agent_id)->first();

                        }
                        if($haji->customer->type == 2){
                            $group_id = $haji->customer->group_id;
                              $group= DB::table('groups')->where('id',$group_id)->first();

                        }
                        @endphp

                        @if($haji->customer->type == 3)
                            <td>{{ $agent->name }}</td>
                        @elseif($haji->customer->type == 2)
                            <td>{{ $group->group_name }}</td>
                        @else
                            <td> N/A</td>
                        @endif



                        <td>{{ $passport_info->passport_no }}</td>
                        <td>{{ $haji->customer->mobile }}</td>
                        <td>{{ $haji->package->package_name }}</td>
                        <td>{{ $haji->package->total_price }}</td>
                        <td>{{ $haji->paid_amount }}</td>
                        <td>{{ $haji->due_amount }}</td>

                        <td class="text-center">
                            <a href="{{ route('deposit-details.view', $haji->id) }}" class="btn btn-warning btn-sm btn-icon-sm btn-circle"
                               data-skin="brand" data-offset="60px 0px" data-toggle="kt-tooltip" data-placement="top" title="View Payments">
                                <i class="fas fa-money-bill"></i>
                            </a>
                            <a href="{{ $hajj_type == 'Customer Service' ? route('package-service.edit', $haji->id) :  route('omra-haji.edit', $haji->id)  }}" class="btn btn-primary btn-sm btn-icon-sm btn-circle">
                                <i class="flaticon2-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-icon-sm btn-circle delete-button" data-toggle="modal" data-target="#delete-modal" data-id="{{ $haji->id }}">
                                <i class="flaticon-delete"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
    <!--end::Portlet-->
@endsection

@push('scripts')
    @include('dashboard::scripts.delete')
    <!-- Datatables -->
    <script src="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            @if($hajj_type == 'Customer Service')
            $('#hajj-management-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');
            $('#all-haji-information-sm').addClass('kt-menu__item--active');
            @else
            $('#omra-hajj-management-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');
            $('#all-omra-haji-information-sm').addClass('kt-menu__item--active');
            @endif
            $('.table').DataTable({
                responsive: true
            });
        });
    </script>
@endpush
