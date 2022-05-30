@extends('Admin.layouts.app')

@push('css')
    <!-- Datatables -->
    <link href="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet">
@endpush

@section('page_title', 'Agent wise Customer List')
@section('page_tagline',' Agent wise Customer List')

@section('content')
    @include('dashboard::components.delete-modal')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon"><i class="kt-font-brand flaticon2-line-chart"></i></span>

                @if(count($hajis) > 0 )
                    <h3 class="kt-portlet__head-title">
                        <strong style="color: #3c5e79; font-size: 25px;">{{$hajis[0]->customer->agent->name}}</strong> Agent Customer List
                    </h3>
                @else
                    <h3 class="kt-portlet__head-title">
                        Agent wise Customer List
                    </h3>
                @endif
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table
                    class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
                <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Serial No.</th>
                    <th>Tracking No.</th>
                    <th>Mobile Number</th>
                    <th>Package Name</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Remaining Amount</th>
                    <th>Payment Status</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>

                @php($total = 0)
                @php($total_paid = 0)
                @php($total_due = 0)
                @foreach($hajis as $haji)
                    @php($total += $haji->package->total_price)
                    @php($total_paid += $haji->paid_amount)
                    @php($total_due += $haji->due_amount)
                    <tr id="tr-{{ $haji->id }}">
                        <td>
                            <a style="color: #ef4023;font-weight: bold;" href="{{ route('customer.show', $haji->customer->id) }}">
                                {{ $haji->customer->sur_name }}  {{ $haji->customer->given_name }}
                            </a>

                        </td>
                        <td>{{ $haji->serial_no }}</td>
                        <td>{{ $haji->customer->tracking_no }}</td>
                        <td>{{ $haji->customer->mobile }}</td>
                        <td>{{ $haji->package->package_name }}</td>
                        <td>{{ $haji->package->total_price }}</td>

                        <td>{{ $haji->paid_amount }}</td>
                        <td>{{ $haji->due_amount }}</td>
                        <td>{{ $haji->payment_status_title }}</td>
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

                <tr>
                    <td ><b>.</b></td>
                    <td><b></b></td>
                    <td ><b></b></td>
                    <td ><b></b></td>
                    <td ><b>Total:</b></td>
                    <td><b>{{ $total }}</b></td>
                    <td><b>{{ $total_paid }}</b></td>
                    <td ><b>{{ $total_due }}</b></td>
                    <td><b></b></td>
                    <td ><b></b></td>
                </tr>
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
