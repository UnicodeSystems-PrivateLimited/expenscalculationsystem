<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {!! HTML::style('packages/jacopo/laravel-authentication-acl/css/mail-base.css') !!}
    {!! HTML::style('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css') !!}
</head>
<body>

<div style="max-width:600px;font-family:arial;margin: 20px auto;border:1px solid #ededed;font-size:14px;">
                        <div style="background: #eff6fc;border-radius: 3px 3px 0 0;border-bottom: 1px solid #d1e5f7;">
                            <span style="color: #fff;font-size: 36px;padding: 12px 0px 16px;display: inline-block;text-align: center;width: 100%;">
                                <span style="vertical-align: middle;"><img src="https://vataxcloud.com/resources/assets/images/vataxcloud-logo.png" style="max-width:176px;"></span>
                            </span>
                        </div>

    <div style="display: block;padding: 30px 20px;text-align: left;font-size: 16px;color: #2e5075;background: #ffffff;">
                        <h2 style="font-size: 22px;font-weight: 400;color: rgba(0,0,0,.9);width:100%;text-align:center;margin: 3px 0px 10px;">
                        <span style="border-bottom: 2px solid rgba(86, 86, 86, 0.18);padding: 0px 1px;">Welcome to {!! Config::get('acl_base.app_name')!!}</span></h2>

                            <h3 style="font-size: 18px;font-weight: 400;color: rgb(27,36,50);margin: 28px 0px 10px;">Dear: {{$first_name}},</h3>
    <p style="color: rgb(103,109,118);font-size: 16px;margin: 10px 0px 20px 10px;line-height: 24px;color: #717171;"> 
    Your email <strong>{{$email}}</strong> has been registered succesfully. After getting approval email you can login to the website using the  <a href="{!! URL::to('/') !!}">Following link</a>.</p>                 
      </div>
           
      <div style="background: #f3f3f3;color: #606060;display: block;font-size: 12px;line-height: 18px;padding: 16px 8px 8px;text-align: center;border-top: 1px solid #e8e8e8;background: #EAEAEA;">      
                    <p style="color: #03a9f4; font-weight: 500; font-size: 14px;line-height: 20px;color: #666; width:100%;">Copyright © 2017 Vatax Cloud</p>            
                        </div>

</div>
</body>
</html>