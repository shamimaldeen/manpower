@extends('Admin.layouts.app')
@push('css')
    @include('Admin.customer.customer-pdf-css')
@endpush

@section('page_title', 'Customer')
@section('page_tagline', $customer->full_name)

@section('content')
    @include('dashboard::components.delete-modal')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon" onclick="printData()"><i class="flaticon2-printer"></i></span>
                <h3 class="kt-portlet__head-title" style="text-align: center">
                    View Customer
                </h3>

            </div>
            <hr>
        </div>





        <div class="kt-portlet__body" id="customer_info">


                <div class="row">
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
                </div>
            <div class="panel panel-default no-break">
                <div class="panel-heading">
                    <h3 class="panel-title">General Information</h3>
                </div>
                <div class="panel-body">
                    <table style="width:100%">
                        <tr>
                            <td width="50%">
                                <table class="pdf-table">
                                    <tr>
                                        <td colspan="2">
                                            @if($customer->photo)
                                                <img src="{{ str_replace(' ', '%20', asset('/') . 'uploads/customers/images/'. $customer->photo) }}"
                                                     style="width: 150px; height: 180px;">
                                                @else
                                                <img src="{{ str_replace(' ', '%20', asset('/') . 'uploads/customers/no_image/no_image.png') }}"
                                                     style="width: 150px; height: 180px;">
                                               @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Full Name <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Date Of Birth <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->format('d F, Y') : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Gender <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->gender == 1 ? 'Male' : 'Female' }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <table class="pdf-table">
                                    <tr>
                                        <td class="info-key">Type <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->type == 1 ? 'Individual' : 'Group' }}</td>
                                    </tr>
                                    @if($customer->type == 2)
                                        <tr>
                                            <td class="info-key">Group <span class="text-right">:</span></td>
                                            <td class="info-value">{{ $customer->group ? $customer->group->group_name : '' }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="info-key">Passport ID <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->passport ? $customer->passport->passport_no : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Mobile <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Marital Status <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->marital_status == 1 ? 'Single' : ($customer->marital_status ? 'Married' : 'Others') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel panel-default no-break">
                <div class="panel-heading">
                    <h3 class="panel-title">Medical Infomation</h3>
                </div>
                <div class="panel-body">
                    <table style="width:100%">
                        <tr>
                            <td width="50%">
                                <table class="pdf-table">
                                    <tr>
                                        <td class="info-key">Medical Date <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->m_date ? \Carbon\Carbon::parse($customer->m_date)->format('d F, Y') : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Medical Expiry Date <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->m_expiry_date ? \Carbon\Carbon::parse($customer->m_expiry_date)->format('d F, Y') : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Medical Institute<span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->m_ins_name }}</td>
                                    </tr>
                                    @if($customer->m_date == null)
                                    <tr>
                                        <td class="info-key"> Medical Status <span class="text-right">:</span></td>
                                        <td class="info-value"></td>
                                    </tr>
                                    @else
                                        <tr>
                                            <td class="info-key"> Medical Status <span class="text-right">:</span></td>
                                            <td class="info-value">{{ $customer->m_status == 1 ? 'Fit' : 'Unfit' }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </td>
                            <td width="50%">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="panel panel-default no-break">
                <div class="panel-heading">
                    <h3 class="panel-title">Flight Information</h3>
                </div>
                <div class="panel-body">
                    <table style="width:100%">
                        <tr>
                            <td width="50%">
                                <table class="pdf-table">
                                    <tr>
                                        <td class="info-key">PNR <span class="text-right">:</span></td>
                                        <td class="info-value"> {{ $customer->pnr }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key">Airlines <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->airlines }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-key"> Flight Date <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->flight_date ? \Carbon\Carbon::parse($customer->flight_date)->format('d-m-Y') : '' }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="panel panel-default no-break">
                <div class="panel-heading">
                    <h3 class="panel-title">Stamping Information</h3>
                </div>
                <div class="panel-body">
                    <table style="width:100%">
                        <tr>
                            <td width="50%">
                                <table class="pdf-table">
                                    <tr>
                                        <td class="info-key"> Stamping  Date <span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->stamping_date ? \Carbon\Carbon::parse($customer->stamping_date)->format('d-m-Y') : '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="info-key"> Visa-Expire Date<span class="text-right">:</span></td>
                                        <td class="info-value">{{ $customer->expire_date ? \Carbon\Carbon::parse($customer->expire_date)->format('d-m-Y') : '' }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Portlet-->
        @endsection

        @push('scripts')
            <script>
                function printData()
                {
                    var divToPrint=document.getElementById('customer_info');

                    var newWin=window.open('','Print-Window');

                    newWin.document.open();

                    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

                    newWin.document.close();

                    setTimeout(function(){newWin.close();},10);
                }
                $(document).ready(function () {
                    $('#Customer-management-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');
                    $('#all-customer-list-sm').addClass('kt-menu__item--active');

                    $('.kt-portlet__head').on('click', '.delete-button', function () {
                        $('#modal-delete-button').unbind().click(function () {
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                url: "{{ url()->current() }}",
                                type: 'DELETE',
                                dataType: 'json',
                                data: {},
                                success: function (response) {
                                    if (response.success) {
                                        toastr.success(response.message);
                                        setTimeout(function(){
                                            window.location.href = "{{ route('customer.index') }}";
                                        }, 5000);
                                    } else {
                                        toastr.error("Whoops! Something Went Wrong!")
                                    }
                                }
                            }).done(function () {

                            });
                        });
                    });

                    $(document).on('submit', '#update-note-form', function (e) {
                        e.preventDefault();
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            url: $(this).attr('action'),
                            type: 'POST',
                            dataType: 'json',
                            data: $(this).serialize(),
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#update-note-modal').modal('hide');
                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000);
                                } else {
                                    toastr.error("Whoops! Something Went Wrong!")
                                }
                            }
                        }).done(function () {

                        });
                    });
                });
            </script>
    @endpush
