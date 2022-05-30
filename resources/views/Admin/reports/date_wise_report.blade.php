@extends('Admin.layouts.app')

@push('css')
    <!-- Datatables -->
    <link href="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet">
@endpush

@section('page_title', 'Date Wise Report')
@section('page_tagline', 'Date Wise Report')

@section('content')
    @include('dashboard::components.delete-modal')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet kt-portlet--collapsed" data-ktportlet="true" id="customer_report_filter">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon"><i class="kt-font-brand flaticon2-line-chart"></i></span>
                <h3 class="kt-portlet__head-title">
                    Date Wise Report
                </h3>
            </div>


            <div class="kt-portlet__head kt-portlet__head--lg">
                <div  style="margin-left: 730px !important;" class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon" id="printreport" onclick="printData()"><i class="flaticon2-printer"></i></span>
                </div>
                <hr>
            </div>

            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-group">
                    <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-default btn-icon-md"><i class="la la-angle-down"></i></a>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <form id="haji-report-form" action="{{ route('date-wise-report.show') }}" method="POST" class="kt-form kt-form--label-right">
                @csrf
                <div class="row">

                    <div class="col-4">
                        <div class="form-group row">
                            <label for="start_date" class="col-4 col-form-label">
                              Start Date
                            </label>
                            <div class="col-8">
                                <input class="form-control kt-datepicker" type="text" id="start_date" name="start_date" value="{{ old('start_date',$start) }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group row">
                            <label for="end_date" class="col-4 col-form-label">
                           End Date
                            </label>
                            <div class="col-8">
                                <input class="form-control kt-datepicker" type="text" id="end_date" name="end_date" value="{{ old('end_date',$end) }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-1"></div>
                    <div class="col-11">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="kt-portlet__body" id="datewisereport">

        <div id="heading" class="row">
            <table style="font-size:14px;">
                <tbody style="text-align: left;">
                <tr>
                    <td style="border-left:none; text-align:left;width: 59%;">
                        <img alt="Brand" src="{{brandLogo()}}">
                    </td>
                </tr>
                </tbody>
            </table>
            <table>
                <thead>
                <tr>
                    <td style="width: 75%;">
                        <h2>{{$details[0]->company_name}}</h2>
                        <b>Address:</b>
                        {{$details[0]->company_address}}<br>
                        <b>Phone Number:</b>{{$details[0]->company_contact}}
                        <br><b>Email:</b>{{$details[0]->company_email}}
                    </td>
                </tr>
                </thead>
                <tbody style="text-align: left;">
                </tbody>
            </table>
            <br>
        </div>




       @if($start != '')
            <div class="kt-portlet__head" id="title">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" style="margin-left: 200px;">
                        Date Wise Report ({{$start}} TO {{ $end }})
                    </h3>
                </div>
            </div>
        @else
            <div class="kt-portlet__head" id="title">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" style="text-align: center;">
                        Date Wise Report
                    </h3>
                </div>
            </div>
         @endif


        <br>

        <!--begin: Datatable -->
        <table
                class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Package Name</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Remaining Amount</th>
                <th>Payment Status</th>

            </tr>
            </thead>
            <tbody>

            @php($total = 0)
            @php($total_paid = 0)
            @php($total_due = 0)
            @foreach($hajjs as $haji)
                @php($total += $haji->package->total_price)
                @php($total_paid += $haji->paid_amount)
                @php($total_due += $haji->due_amount)
                <tr id="tr-{{ $haji->id }}">
                    <td>
                        <a style="color: #ef4023;font-weight: bold;" href="{{ route('customer.show', $haji->customer->id) }}">
                            {{ $haji->customer->sur_name }}  {{ $haji->customer->given_name }}
                        </a>

                    </td>
                    <td>{{ $haji->package->package_name }}</td>
                    <td>{{ $haji->package->total_price }}</td>

                    <td>{{ $haji->paid_amount }}</td>
                    <td>{{ $haji->due_amount }}</td>
                     @if($haji->due_amount == 0 )
                        <td>Paid</td>
                     @else
                        <td>Partially Paid</td>
                    @endif
                </tr>
            @endforeach

            <tr>
                <td ><b>.</b></td>
                <td ><b>Total:</b></td>
                <td><b>{{ $total }} BDT</b></td>
                <td><b>{{ $total_paid }} BDT</b></td>
                <td ><b>{{ $total_due }} BDT</b></td>
                <td><b></b></td>
                <td ><b></b></td>
            </tr>
            </tbody>
        </table>
        <!--end: Datatable -->
    </div>
    <!--end::Portlet-->
@endsection

@push('scripts')
    @include('dashboard::scripts.delete')
    <!-- Datatables -->
    <script src="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/report-bootstrap-pickers.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#reports-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');
            $('#haji-report-sm').addClass('kt-menu__item--active');
            setTimeout(function () {
                $('.kt-selectpicker').selectpicker('refresh');
            }, 400);
            $('#heading').hide();
        });

        function printData()
        {
            var divToPrint=document.getElementById('datewisereport');
            var divTo=document.getElementById('heading');

            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print()">'+divTo.innerHTML+'</body></html>');
            newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();

            setTimeout(function(){newWin.close();},10);
        }
    </script>
@endpush
