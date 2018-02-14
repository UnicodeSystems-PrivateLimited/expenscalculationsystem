
@extends('layouts.base')
@section('title')
Report Login
@stop
@section('content')

<div class="container">
    <div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 d-flex justify-content-center">
            <h3><span>Report Login</span></h3>
        </div>

        <div class="from-lgn form_wrapper text-center no-padding col-sm-12">
            <form action="{{route('report-login-action')}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


            <div class="input-labelvalue username flex-row d-flex align-items-center">

                <label class="col-4 d-flex justify-content-end">Email :</label>
                <div class="input-value col-8 d-flex justify-content-start no-padding">
                    <input id="email" required type="text" name="email"
                        value="{{ old('email') }}">
                </div>

            </div>

            <div class="input-labelvalue pass flex-row d-flex align-items-center ">

                <label class="col-4 d-flex justify-content-end">Password :</label>
                <div class="input-value col-8 d-flex justify-content-start no-padding">
                    <input id="password" required type="password" name="password"
                        value="{{ old('password') }}">
                </div>
            </div>

            <div class="input-labelvalue submit d-flex justify-content-center col-sm-12">

                <div class="input-value value-submit">
                    <!-- <input type="submit" name="submit" value=" Submit "/> -->
                    <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>

                </div>
            </div>

            </form>
        </div>

@stop

                              

     