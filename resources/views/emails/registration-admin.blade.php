<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {!! HTML::style('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css') !!}
    {!! HTML::style('packages/jacopo/laravel-authentication-acl/css/mail-base.css') !!}
</head>
<body>
<div style="max-width:600px;font-family:arial;margin: 20px auto;border:1px solid #ededed;font-size:14px;">
                            <div style="background: #eff6fc;border-radius: 3px 3px 0 0;border-bottom: 1px solid #d1e5f7;">
                                <span style="color: #fff;font-size: 36px;padding: 12px 0px 16px;display: inline-block;text-align: center;width: 100%;">
                                    <span style="vertical-align: middle;"><img src="https://vataxcloud.com/resources/assets/images/vataxcloud-logo.png" style="max-width:176px;"></span>
                                </span>
                            </div>
                            <div style="display: block;padding: 30px 20px;text-align: left;font-size: 16px;color: #545454;background: #ffffff;/* border: 1px solid rgb(221,221,221); */">
                            <h2 style="font-size: 22px;font-weight: 400;color: rgba(0,0,0,.9);width:100%;text-align:center;margin: 3px 0px 30px;"><span style="/* text-decoration:underline; */border-bottom: 2px solid rgba(86, 86, 86, 0.18);padding: 0px 1px;">Welcome to {!! Config::get('acl_base.app_name') !!}</span></h2>
                            <p style="font-size: 16px;line-height: 23px; ">
                                
                                User registered on system, Please verify and activate account.
                        
                        </p>
                        <br> 
                        <strong style=" font-weight: 600;
                        ">Please find account details below:</strong><div style="
                        max-width: 500px;
                        margin: 20px auto;
                        ">
                        <table style="
                        width: 100%;
                        ">
                        <tbody style=" width: 100%;">
                        <tr>
                        <td style=" width: 50%;font-size: 16px; line-height:20px; color: #666; border: 1px solid #888; padding: 10px;">First Name</td> 
                        <td style=" width: 50%;font-size: 17px; line-height:20px; color: #444; border: 1px solid #888; padding: 10px;">{{$first_name}}</td>
                        </tr>
                        <tr>
                        <td style=" width: 50%;font-size: 16px; line-height:20px; color: #666; border: 1px solid #888; padding: 10px;">Last Name</td> 
                        <td style=" width: 50%;font-size: 17px; line-height:20px; color: #444; border: 1px solid #888; padding: 10px;">{{$last_name}}</td>
                        </tr>
                        <tr>
                        <td style=" width: 50%;font-size: 16px; line-height:20px; color: #666; border: 1px solid #888; padding: 10px;">Email </td> 
                        <td style=" width: 50%;font-size: 17px; line-height:20px; color: #444; border: 1px solid #888; padding: 10px;">{{$email}}</td>
                        </tr>
                        <tr>
                        <td style=" width: 50%;font-size: 16px; line-height:20px; color: #666; border: 1px solid #888; padding: 10px;">Company </td> 
                        <td style=" width: 50%;font-size: 17px; line-height:20px; color: #444; border: 1px solid #888; padding: 10px;">{{$company_name}}</td>
                        </tr>
                        <tr>
                        <td style=" width: 50%;font-size: 16px; line-height:20px; color: #666; border: 1px solid #888; padding: 10px;">Phone </td> 
                        <td style=" width: 50%;font-size: 17px; line-height:20px; color: #444; border: 1px solid #888; padding: 10px;">{{$phone}}</td>
                        </tr>
                        </tbody></table></div>
                            
                        <div style="background: #f3f3f3;color: #606060;display: block;font-size: 12px;line-height: 18px;padding: 16px 8px 8px;text-align: center;border-top: 1px solid #e8e8e8;background: #EAEAEA;">
                                
                        <p style="color: #03a9f4; font-weight: 500; font-size: 14px;line-height: 20px;color: #666; width:100%;">Copyright © 2017 Vatax Cloud</p>
                                        
                        </div></div></div>



</body>
</html>