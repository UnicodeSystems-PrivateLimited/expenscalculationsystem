@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Excel Import
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
    <a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>    
    <div class="header-title no-padding col-sm-12 text-center">
    <h3><span>Excel Import</span></h3>
    </div>

    <div class="from-lgn excel-import formgroup text-center no-padding col-sm-12">
        <div class="form_wrapper login">
            <div class="row row-wrapper error-row">
                @foreach($incorrectData as $data)
                <p>Incorrect date format in row no. {!! $data !!}</p>
                @endforeach
            </div>
        </div><!--form_wrapper-->
    </div> <!--formgroup--> 
</div>
    
@stop