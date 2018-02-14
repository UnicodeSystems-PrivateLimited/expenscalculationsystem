@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Excel Import
@stop
@section('content')

<div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Excel Import</span></h3>
        </div>

    <div class="from-lgn excel-import formgroup text-center no-padding col-sm-12">
        <div class="concur-login form_wrapper">
            <div class="msgalert-section row">      
                 @if (session('error'))
                 <div class="alert alert-danger"> <p id="error">{{ session('error') }}</p></div>
                    @endif
                    @if (session('message'))
                    <div class="alert alert-success"><p id="message">{{ session('message') }}</p></div>
                    @endif

                 </div>
            <div class="row row-wrapper">
                <form action="{{route('excel-import-action')}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="input-group row">

                    <div class="csv col-sm-12">
                        <label class="col-sm-12 text-center">Select Excel file to import :</label> 
                        <div class="input-value col-sm-12 text-center">
                            <input type="file"  name="file" accept=".csv, .xls, .xlsx">               
                        </div>
                    </div> 

                </div>  

                    <div class="input-group submit  col-sm-12">
                        <div class="input-value value-submit text-center">
                            <!-- <input type="submit"  name="submit" value="Import"> 
                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> -->
                            <button type="submit" name="submit">Import <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>           
                        </div> 
                    </div> 

                </form>
          
            <div class="text-center col-sm-12 sampledata_download">
            <a href="{{ asset('resources/assets/sampleExcel/sample_file.xlsx') }}" class="d-flex flex-row justify-content-center align-items-center">
           <i class="fa fa-download" aria-hidden="true"></i> Download Sample Excel file </a>
           </div>
            </div><!--row-wrapper-->
       </div><!--form_wrapper-->
    </div><!--formgroup-->
</div>
 
@stop