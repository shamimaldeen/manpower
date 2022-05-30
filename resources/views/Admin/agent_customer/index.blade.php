@extends('Admin.layouts.app')

@push('css')
    <!-- Datatables -->
    <link href="{{asset('vendor/dashboard/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet">
@endpush

@php
        $agent_type ='Agent Customer';
         $routeNamePrefix = 'agent-customer';

@endphp

@section('page_title', $agent_type )
@section('page_tagline', $agent_type)

@section('content')
    @include('dashboard::components.delete-modal')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon"><i class="kt-font-brand flaticon2-line-chart"></i></span>
                <h3 class="kt-portlet__head-title">
                    {{ $agent_type }} List
                </h3>
            </div>
            <div class="float-right mt-3">
                <a href="{{ route($routeNamePrefix . '.create') }}" class="btn btn-label-success btn-sm btn-upper">
                    <i class="fa fa-plus"></i> Add New {{ $agent_type }}
                </a>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table
                    class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
                <thead>
                <tr>
                    <th>Agent Name</th>
                    <th>Contact Name</th>
                    <th>Contact No</th>
                    <th> Email</th>
                    <th>Address</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($agents as $agent)
                    <tr id="tr-{{ $agent->id }}">
                        <td scope="row">{{ $agent->name }}</td>
                        <td>{{ $agent->contact_name }}</td>
                        <td>{{ $agent->contact_no }}</td>
                        <td>{{ $agent->email }}</td>
                        <td>{{ $agent->address }}</td>
                        <td class="text-center">
                            <a href="{{ route($routeNamePrefix . '.show', $agent->id) }}" class="btn btn-success btn-sm btn-icon-sm btn-circle">
                                <i class="flaticon-eye"></i>
                            </a>
                            <a href="{{ route($routeNamePrefix . '.edit', $agent->id) }}" class="btn btn-primary btn-sm btn-icon-sm btn-circle">
                                <i class="flaticon2-edit"></i>
                            </a>

                            <a href="{{ route($routeNamePrefix . '.delete', $agent->id) }}" class="btn btn-danger btn-sm btn-icon-sm btn-circle">
                                <i class="flaticon-delete"></i>
                            </a>

                            <a href="{{ route($routeNamePrefix . '.agent-history', $agent->id) }}" class="btn btn-warning btn-sm btn-icon-sm btn-circle" title="Agent-Wise-History">
                                History
                            </a>
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
    {{--<script>--}}
        {{--$(document).ready(function () {--}}
            {{--@if($group_type == 'Hajj ')--}}
            {{--$('#hajj-management-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');--}}
            {{--$('#hajj-groups-sm').addClass('kt-menu__item--active');--}}
            {{--@elseif($group_type == 'Omra Hajj ')--}}
            {{--$('#omra-hajj-management-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');--}}
            {{--$('#omra-hajj-groups-sm').addClass('kt-menu__item--active');--}}
            {{--@else--}}
            {{--$('#groups-mm').addClass('kt-menu__item--submenu kt-menu__item--open kt-menu__item--here');--}}
            {{--$('#groups-sm').addClass('kt-menu__item--active');--}}
            {{--@endif--}}
            {{--$('.table').DataTable({--}}
                {{--responsive: true--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
@endpush
