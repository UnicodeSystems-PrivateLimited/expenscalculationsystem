@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Contact Request Details
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
            <h3><span>Contact Request Details</span></h3>
        </div>
<a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>
        <div class="contact-detailsarea col-sm-12 sendon">
      
            <div class="contact-Value col-sm-12 text-right">
                <span class="label-val text-right">
                    <i class="fa fa-calendar-o" aria-hidden="true"></i> <strong>{!! date('d, M Y',strtotime($contactDetails->created_at)) !!}</strong></span>
            </div>
         </div>

        <div class="contact-detailsarea col-sm-12">
          <div class="Detailarea detail_max-width row">
            <div class="contact_name contact-Value col-sm-6 col-xs-12">
                <div class="row">
                <span class="label"><i class="fa fa-user-o" aria-hidden="true"></i> Name </span>
                </div>
                <div class="row">
                <span class="label-val">{!! $contactDetails->name !!}</span>
                </div>
            </div>

            <div class="contact_email contact-Value col-sm-6 col-xs-12">
                <div class="row">
                    <span class="label "><i class="fa fa-envelope-o" aria-hidden="true"></i> Email </span>
                </div> 
                <div class="row">  
                    <span class="label-val">{!! $contactDetails->from_email !!}</span>
                </div>
            </div>

            <div class="contact_cmny contact-Value col-sm-6 col-xs-12">
                <div class="row">
                    <span class="label"><i class="fa fa-building-o" aria-hidden="true"></i> Company Name </span>
                </div> 
                <div class="row">  
                    <span class="label-val">{!! $contactDetails->company_name !!}</span>
                </div>
            </div>

            <div class="contact_phone contact-Value col-sm-6 col-xs-12">
               <div class="row">
                 <span class="label"> <i class="fa fa-phone" aria-hidden="true"></i> Phone number </span>
                </div> 
               <div class="row">  
                    <span class="label-val">{!! $contactDetails->phone_number !!}</span>
                </div>
            </div>

            <div class="contact_subject contact-Value col-sm-6 col-xs-12">
                <div class="row"> 
                <span class="label"><i class="fa fa-edit" aria-hidden="true"></i> Subject </span>
                </div> 
               <div class="row"> 
                <span class="label-val">{!! $contactDetails->subject !!}</span>
                </div> 
            </div>

            <div class="contact_msg contact-Value col-sm-6 col-xs-12">
               <div class="row"> 
                <span class="label"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Message </span>
                </div> 
               <div class="row"> 
                <span class="label-val msg-val">{!! $contactDetails->message !!}</span>
                </div> 
            </div>
          </div>
        </div>


        
</div>
  
@stop