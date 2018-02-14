@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Expensify Import Log
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
            <h3><span>Expensify Import Log</span></h3>
        </div>
<a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>
          <div class="paginator text-right col-sm-6 col-xs-12">
                {{ $dateLog->links() }}
            </div>
        </div>
     
        <div class="table-area table-responsive col-sm-12">
            
           
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="">From date</th>
                        <th class="">To date</th>
                        <th class="">Imported at</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $dateLog->isEmpty() )
                        @foreach($dateLog as $dateLog)
                        <tr>
                            <td class="">{!! date('d, M Y', strtotime($dateLog->from_date)) !!}</td>
                            <td class="">{!! date('d, M Y', strtotime($dateLog->to_date)) !!}</td>
                            <td class="">{!! date('d, M Y', strtotime($dateLog->created_at)) !!}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr align="center">
                            <td colspan="5"><span class="text-warning"><h5>No results found.</h5></span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            

        </div>


        
</div>
  
@stop