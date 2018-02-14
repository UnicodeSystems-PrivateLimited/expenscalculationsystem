@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Vat Potiential Report
@stop
@section('content')

    <div class="main-content index-page iframe_mainsection">
        <div class="mainarea_section col-sm-12">
            <a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left"
                                                                               aria-hidden="true"></i></a>
            @if ($ownerType == 1 && $type ==2)
                <iframe frameborder=0 width="100%" height="800"
                        src="https://reports.zoho.com/ZDBDataSheetView.cc?OBJID=1465504000000171034&STANDALONE=true&privatelink=dc46d9004b5783d600c25c8b6014dd64&WIDTH=800&HEIGHT=600&INTERVAL=-1&REMTOOLBAR=true&INCLUDETITLE=true&INCLUDEDESC=true&ZOHO_CRITERIA=%22Expenses%22.%22Company+Name%22%3D'<?php echo $company?>'"></iframe>
            @elseif($type == 2)
                <iframe frameborder=0 width="100%" height="800"
                        src="https://reports.zoho.com/ZDBDataSheetView.cc?OBJID=1465504000000171034&STANDALONE=true&privatelink=dc46d9004b5783d600c25c8b6014dd64&WIDTH=800&HEIGHT=600&INTERVAL=-1&REMTOOLBAR=true&INCLUDETITLE=true&INCLUDEDESC=true&ZOHO_CRITERIA=%22Expenses%22.%22Entity%22%3D'<?php echo $entity?>'"></iframe>
            @elseif($ownerType == 1 && $type ==1)
                <iframe frameborder=0 width="100%" height="800"
                        src="https://reports.zoho.com/ZDBDataSheetView.cc?OBJID=1465504000000124241&STANDALONE=true&privatelink=5784c0e2a7c67c0e3a75db44ad689fe2&WIDTH=800&HEIGHT=600&INTERVAL=-1&REMTOOLBAR=true&INCLUDETITLE=true&INCLUDEDESC=true&ZOHO_CRITERIA=%22Expenses%22.%22Company+Name%22%3D'<?php echo $company?>'"></iframe>
            @else
                <iframe frameborder=0 width="100%" height="800"
                        src="https://reports.zoho.com/ZDBDataSheetView.cc?OBJID=1465504000000124241&STANDALONE=true&privatelink=5784c0e2a7c67c0e3a75db44ad689fe2&WIDTH=800&HEIGHT=600&INTERVAL=-1&REMTOOLBAR=true&INCLUDETITLE=true&INCLUDEDESC=true&ZOHO_CRITERIA=%22Expenses%22.%22Entity%22%3D'<?php echo $entity?>'"></iframe>
            @endif
        </div>


    </div>

@stop