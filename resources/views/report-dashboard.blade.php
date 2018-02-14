@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Reports
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
<div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Reports Dashboard</span></h3>
        </div>
        <a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>
        
    <div class="from-lgn formgroup text-center no-padding col-sm-12">
        <div class="xml-report_section form_wrapper width-800 row">
                <div class="user_full-section ">
                    <div class="email-associate row">
                    <div class="person_info">
                        <p><span><i class="fa fa-envelope-o" aria-hidden="true"></i></span><strong>{{ $email}}</strong></p>  
                        <p><span><i class="fa fa-user-o" aria-hidden="true"></i></span><strong>{{ $first_name}} {{ $last_name}}</strong></p>
                        <p><span><i class="fa fa-building-o" aria-hidden="true"></i></span><strong>{{ $company_name}}</strong></p>
                        </div>

                    </div>
                 <div class="row submisssion-and-vat">   
                    <div class="col-sm-12">
                            @if($id == null)
                            <div class="col-sm-6">
                                <a href="{{route('submission-analysis')}}" class=" text-center">
                                    <span class="icon_custom"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                                    <span>Submission Analysis Report</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{route('vat-potiential')}}" class="text-center">
                                    <span class="icon_custom col-sm-12"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                                    <span class="col-sm-12">Vat Potential Analysis</span>
                                </a>
                            </div>
                            @else

                            <div class="col-sm-6">
                                <a href="{{route('submission-analysis', ['id' => $id])}}" class="text-center">
                                    <span class="icon_custom col-sm-12"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                                    <span class="col-sm-12">Submission Analysis Report</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{route('vat-potiential', ['id' => $id])}}" class="text-center">
                                    <span class="icon_custom col-sm-12 "><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                                    <span class="col-sm-12">Vat Potential Analysis</span>
                                </a>
                            </div>
                            @endif
                    </div>
                  </div>
                </div>
        </div>
    </div>
</div>

@stop