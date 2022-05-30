@extends('Admin.layouts.app')

@php
    $agent_type ='Agent Customer';
    $routeNamePrefix = 'agent-customer';

     if (isset($agent->id)){
         $route = route($routeNamePrefix . '.update', $agent->id);
     }else {
         $route = route($routeNamePrefix . '.store');
         $group = new \App\Agent();
     }

@endphp

@section('page_title', $agent_type)
@if(isset($agent->id))
    @section('page_tagline', 'Update '.$agent_type)
@else
    @section('page_tagline', 'Create '.$agent_type)
@endif

@section('content')
    @include('dashboard::msg.message')
    <!--begin::Portlet-->
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    @if(isset($agent->id)) Update @else Create @endif {{ $agent_type }}
                </h3>
            </div>
        </div>

        <!--begin::Form-->
        <form id="group-form" action="{{ $route }}" method="POST"
              class="kt-form kt-form--label-right">
            <div class="kt-portlet__body">
                @csrf
                @if(isset($agent->id)) @method('PUT') @endif
                <div class="form-group row">
                    <label for="name" class="col-2 col-form-label">
                        Agent Name *
                    </label>
                    <div class="col-10">
                        <input class="form-control" type="text" id="name"  @if(isset($agent)) value="{{ old('name', $agent->name)  }}" @endif  name="name" placeholder="Agent Name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_name" class="col-2 col-form-label">Contact Name *</label>
                    <div class="col-10">
                        <input class="form-control" type="text" id="contact_name" @if(isset($agent))  value="{{ old('contact_name', $agent->contact_name) }}" @endif  name="contact_name" placeholder="Contact Name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_no" class="col-2 col-form-label">Contact No. *</label>
                    <div class="col-10">
                        <input class="form-control" type="text" id="contact_no" @if(isset($agent))   value="{{ old('contact_no', $agent->contact_no) }}" @endif name="contact_no"
                               placeholder="017XXXXXXXX" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-2 col-form-label">Email</label>
                    <div class="col-10">
                        <input class="form-control" type="text" id="email" @if(isset($agent))  value="{{ old('email', $agent->email) }}" @endif name="email"
                               placeholder="@">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-2 col-form-label">Address</label>
                    <div class="col-10">
                        <input  class="form-control" type="text" id="address" @if(isset($agent))  value="{{ old('email', $agent->address) }}" @endif name="address"
                                rows="5" >
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-10">
                                <a href="{{ route('groups.index') }}" class="btn btn-primary">Cancel</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--end::Portlet-->
@endsection

@push('scripts')
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
            {{--$('#create-groups-sm').addClass('kt-menu__item--active');--}}
            {{--@endif--}}
        {{--});--}}
    {{--</script>--}}
@endpush
