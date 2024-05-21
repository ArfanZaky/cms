@extends('layouts.app')
@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            {{-- add success --}}
            <h1>Setting Form</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="section-body">

            <div id="output-status"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Setting Menu</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-pills flex-column" id="myTab" role="tablist">
                                <li class="nav-item"><a href="#site" class="nav-link active" data-toggle="tab"
                                        role="tab" aria-controls="site-tab" aria-selected="true">Site</a></li>
                                <li class="nav-item"><a href="#meta" class="nav-link" data-toggle="tab" role="tab"
                                        aria-controls="meta-tab" aria-selected="true">Meta</a></li>
                                <li class="nav-item"><a href="#market" class="nav-link" data-toggle="tab" role="tab"
                                            aria-controls="market-tab" aria-selected="true">Marketplaces</a></li>
                                <li class="nav-item"><a href="#social" class="nav-link" data-toggle="tab" role="tab"
                                        aria-controls="social-tab" aria-selected="true">Social Media</a></li>
                                <li class="nav-item"><a href="#email" class="nav-link" role="tab" data-toggle="tab"
                                        aria-controls="email-tab" aria-selected="true">Email</a></li>
                                <li class="nav-item"><a href="#shield" class="nav-link" role="tab" data-toggle="tab"
                                        aria-controls="shield-tab" aria-selected="true">Shield</a></li>
                                <li class="nav-item"><a href="#maintenance" class="nav-link" role="tab"
                                        data-toggle="tab" aria-controls="maintenance-tab"
                                        aria-selected="true">Maintenance</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card" id="settings-card">
                        <div class="card-header">
                            <h4>Setting Content</h4>
                        </div>
                        <form action="" id="form_submit" method="POST">

                            <div class="card-body tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="site" aria-labelledby="site-tab"
                                    role="tabpanel">
                                    <div class="form-group row align-items-center">
                                        <label for="name-company" class="form-control-label col-sm-3 text-md-right">Name
                                            Company</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="name_company" class="form-control" id="name-company"
                                                value="{{ $settings->where('code', 'name_company')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-company" class="form-control-label col-sm-3 text-md-right">Url
                                            Company</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_company" class="form-control" id="site-company"
                                                value="{{ $settings->where('code', 'web_company')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-contact" class="form-control-label col-sm-3 text-md-right">Url
                                            Contact</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_contact" class="form-control" id="site-contact"
                                                value="{{ $settings->where('code', 'web_contact')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-manatal" class="form-control-label col-sm-3 text-md-right">Web Url Manatal
                                            </label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_manatal" class="form-control" id="site-manatal"
                                                value="{{ $settings->where('code', 'web_manatal')->first()->value }}">
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row align-items-center">
                                        <label for="site-captcha" class="form-control-label col-sm-3 text-md-right">Web Captcha Site Key
                                            </label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_captcha_site_key" class="form-control" id="site-captcha"
                                                value="{{ $settings->where('code', 'web_captcha_site_key')->first()->value }}">
                                        </div>
                                    </div> --}}
                                    <div class="form-group row align-items-center">
                                        <label for="site-secret-captcha" class="form-control-label col-sm-3 text-md-right">Web Captcha Secret Key
                                            </label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_captcha_secret_key" class="form-control" id="site-secret-captcha"
                                                value="{{ $settings->where('code', 'web_captcha_secret_key')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-company" class="form-control-label col-sm-3 text-md-right">Image Logo</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="row">
                                                <div class="col-4 text-center" style="align-self: center;border-right: 1px solid #e4e6fc;">
                                                    <a id="lfm-logo" data-input="thumbnail-logo" data-preview="holder-logo" class="btn">
                                                        <i class="fa fa-upload"></i> Choose Image
                                                    </a>
                                                </div>
                                                <div class="col-8" >
                                                    <div class="image_list_preview position-relative" hidden>
                                                        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-logo" width="400px" height="400px" alt="image"
                                                        style="width: -webkit-fill-available;height: fit-content;" >
                                                        <button type="button" class="close-button"
                                                            onclick="DeleteImage('thumbnail-logo','holder-logo','{{asset('assets/img/default.jpg')}}' )">
                                                        </button>
                                                    </div>
                                                    <input id="thumbnail-logo" type="text" name="web_logo" class="form-control"
                                                    value="{{ $settings->where('code', 'web_logo')->first()->value }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row align-items-center">
                                        <label for="site-company" class="form-control-label col-sm-3 text-md-right">Image Favicon</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="row">
                                                <div class="col-4 text-center" style="align-self: center;border-right: 1px solid #e4e6fc;">
                                                    <a id="lfm-favicon" data-input="thumbnail-favicon" data-preview="holder-favicon" class="btn">
                                                        <i class="fa fa-upload"></i> Choose Image
                                                    </a>
                                                </div>
                                                <div class="col-8" >
                                                    <div class="image_list_preview position-relative" hidden>
                                                        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-favicon" width="400px" height="400px" alt="image"
                                                        style="width: -webkit-fill-available;height: fit-content;" >
                                                        <button type="button" class="close-button"
                                                            onclick="DeleteImage('thumbnail-favicon','holder-favicon','{{asset('assets/img/default.jpg')}}' )">
                                                        </button>
                                                    </div>
                                                    <input id="thumbnail-favicon" type="text" name="web_favicon" class="form-control"
                                                    value="{{ $settings->where('code', 'web_favicon')->first()->value }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label for="site-email" class="form-control-label col-sm-3 text-md-right">Site
                                            Email</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_email" class="form-control" id="site-email"
                                                value="{{ $settings->where('code', 'web_email')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-phone" class="form-control-label col-sm-3 text-md-right">Site
                                            Phone</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_phone" class="form-control" id="site-phone"
                                                value="{{ $settings->where('code', 'web_phone')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-fax" class="form-control-label col-sm-3 text-md-right">Site
                                            Fax</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_fax" class="form-control" id="site-fax"
                                                value="{{ $settings->where('code', 'web_fax')->first()->value }}">
                                        </div>
                                    </div>
                                       <div class="form-group row align-items-center">
                                        <label for="site-web_whatsapp_number" class="form-control-label col-sm-3 text-md-right">Site
                                            Whatsapp Number
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_whatsapp_number" class="form-control" id="site-web_whatsapp_number"
                                                value="{{ $settings->where('code', 'web_whatsapp_number')->first()->value }}">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row align-items-center">
                                        <label for="site-address" class="form-control-label col-sm-3 text-md-right">Site
                                            Address</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_address" class="form-control" id="site-address"
                                                value="{{ $settings->where('code', 'web_address')->first()->value }}">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row align-items-center">
                                        <label for="site-customer" class="form-control-label col-sm-3 text-md-right">Site
                                            Customer</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_customer" class="form-control"
                                                id="site-customer"
                                                value="{{ $settings->where('code', 'web_customer')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-marquee" class="form-control-label col-sm-3 text-md-right">Site
                                            Marquee</label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_marquee" id="site-marquee">{{ $settings->where('code', 'web_marquee')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-report"
                                            class="form-control-label col-sm-3 text-md-right">Dashboard Report
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_report" id="site-report">{{ $settings->where('code', 'web_report')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer text-md-right">
                                        <button class="btn btn-primary save-btn">Save Changes</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="meta" aria-labelledby="meta-tab" role="tabpanel">
                                    <div class="form-group row align-items-center">
                                        <label for="site-url" class="form-control-label col-sm-3 text-md-right">Site
                                            URL<b style="color: red">*</b></label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_url"
                                                value="{{ $settings->where('code', 'web_url')->first()->value }}"
                                                class="form-control" id="site-url">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-title" value="{{ config('app.name') }}"
                                            class="form-control-label col-sm-3 text-md-right">Site
                                            Title<b style="color: red">*</b></label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_title" class="form-control" id="site-title"
                                                value="{{ $settings->where('code', 'web_title')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-keyword" class="form-control-label col-sm-3 text-md-right">Site
                                            Keyword </label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="web_keyword" class="form-control"
                                                id="site-keyword"
                                                value="{{ $settings->where('code', 'web_keyword')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-description"
                                            class="form-control-label col-sm-3 text-md-right">Site
                                            Description </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_description" id="site-description">{{ $settings->where('code', 'web_description')->first()->value }}</textarea>
                                            <small>Maximum 200 character for better site description
                                            </small>
                                        </div>

                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-company" class="form-control-label col-sm-3 text-md-right">Site Image</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="row">
                                                <div class="col-4 text-center" style="align-self: center;border-right: 1px solid #e4e6fc;">
                                                    <a id="lfm-seo-image" data-input="thumbnail-seo-image" data-preview="holder-seo-image" class="btn">
                                                        <i class="fa fa-upload"></i> Choose Image
                                                    </a>
                                                </div>
                                                <div class="col-8" >
                                                    <?php 
                                                    ($settings->where('code', 'web_image')->first()->value != 'default.jpg') ? $imageSeo = env('APP_URL').$settings->where('code', 'web_image')->first()->value : $imageSeo = asset('assets/img/default.jpg');
                                                    ?>
                                                    <div class="image_list_preview position-relative" >
                                                        <img src="{{ $imageSeo }}" id="holder-seo-image" width="400px" height="400px" alt="image"
                                                        style="width: -webkit-fill-available;height: fit-content;" >
                                                        <button type="button" class="close-button"
                                                            onclick="DeleteImage('thumbnail-seo-image','holder-seo-image','{{asset('assets/img/default.jpg')}}' )">
                                                        </button>
                                                    </div>
                                                    <input id="thumbnail-seo-image" type="text" name="web_image" class="form-control"
                                                    value="{{ $settings->where('code', 'web_image')->first()->value }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-schema" class="form-control-label col-sm-3 text-md-right">Site
                                            Schema
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_schema" id="site-schema">{{ $settings->where('code', 'web_schema')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-script" class="form-control-label col-sm-3 text-md-right">Site
                                            Script
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_script" id="site-script">{{ $settings->where('code', 'web_script')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-no-script" class="form-control-label col-sm-3 text-md-right">Site
                                            No Script
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_noscript" id="site-no-script">{{ $settings->where('code', 'web_noscript')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-robot" class="form-control-label col-sm-3 text-md-right">Site
                                            Robot
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea class="form-control h-50" rows="10" name="web_robot" id="site-robot">{{ preg_replace('/<br\\s*?\/??>/i', '', $settings->where('code', 'web_robot')->first()->value) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-sitemap" class="form-control-label col-sm-3 text-md-right">Site
                                            Sitemap
                                        </label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="alert alert-danger">
                                                Please upload the 'sitemap.xml' file manually to the root folder of your website. We do not provide an upload form for security reasons. The root folder is the main directory where your website's primary files are located.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-md-right">
                                        <button class="btn btn-primary save-btn">Save Changes</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="social" aria-labelledby="social-tab" role="tabpanel">
                                    <div class="form-group row" style="justify-content: end;">
                                        {{-- button full widht --}}
                                        <div class="col-sm-12 col-md-12 mb-5">
                                            <button type="button" class="btn btn-primary btn-block"
                                                id="add-social-btn">Add Social</button>
                                        </div>
                                        <div id="data-social" class="col-sm-12 col-md-12">
                                            <?php
                                            $data = json_decode($settings->where('code', 'web_social')->first()->value);
                                            ?>
                                            @if ($data)
                                                @foreach ($data as $key => $value)
                                                    <div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-name"
                                                                class="form-control-label col-sm-3 text-md-right">Name<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_social[name][]"
                                                                    class="form-control" id="social-name"
                                                                    value="{{ $key }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-value"
                                                                class="form-control-label col-sm-3 text-md-right">Value<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_social[value][]"
                                                                    class="form-control" id="social-value"
                                                                    value="{{ $value->value }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-icon"
                                                                class="form-control-label col-sm-3 text-md-right">Link<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_social[icon][]"
                                                                    class="form-control" id="social-icon"
                                                                    value="{{ $value->image }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" style="justify-content: end;">
                                                            <div class="col-sm-8 col-md-8">
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <button class="btn btn-danger btn-block remove-social-btn"
                                                                    id="testt">Remove Social</button>

                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="card-footer text-md-right">
                                            <button class="btn btn-primary save-btn">Save Changes</button>
                                        </div>


                                    </div>
                                </div>

                                
                                <div class="tab-pane fade" id="market" aria-labelledby="market-tab" role="tabpanel">
                                    <div class="form-group row" style="justify-content: end;">
                                        {{-- button full widht --}}
                                        <div class="col-sm-12 col-md-12 mb-5">
                                            <button type="button" class="btn btn-primary btn-block"
                                                id="add-market-btn">Add Market</button>
                                        </div>
                                        <div id="data-market" class="col-sm-12 col-md-12">
                                            <?php
                                            $data = json_decode($settings->where('code', 'web_market')->first()->value);
                                            ?>
                                            @if ($data)
                                                @foreach ($data as $key => $value)
                                                    <div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-name"
                                                                class="form-control-label col-sm-3 text-md-right">Name<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_market[name][]"
                                                                    class="form-control" id="social-name"
                                                                    value="{{ $key }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-value"
                                                                class="form-control-label col-sm-3 text-md-right">Value<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_market[value][]"
                                                                    class="form-control" id="social-value"
                                                                    value="{{ $value->value }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row align-items-center">
                                                            <label for="social-icon"
                                                                class="form-control-label col-sm-3 text-md-right">Link<b
                                                                    style="color: red">*</b></label>
                                                            <div class="col-sm-6 col-md-9">
                                                                <input type="text" name="web_market[icon][]"
                                                                    class="form-control" id="social-icon"
                                                                    value="{{ $value->image }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" style="justify-content: end;">
                                                            <div class="col-sm-8 col-md-8">
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <button class="btn btn-danger btn-block remove-social-btn"
                                                                    id="testt">Remove Social</button>

                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="card-footer text-md-right">
                                            <button class="btn btn-primary save-btn">Save Changes</button>
                                        </div>


                                    </div>
                                </div>

                                <div class="tab-pane fade" id="email" aria-labelledby="email-tab" role="tabpanel">
                                    <div class="form-group row align-items-center">
                                        <label for="site-email-contact"
                                            class="form-control-label col-sm-3 text-md-right">Default Email Contact</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="email_contact" class="form-control"
                                                id="site-email-contact"
                                                value="{{ $settings->where('code', 'email_contact')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="site-email-demo"
                                            class="form-control-label col-sm-3 text-md-right">Default Email Demo</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="email_demo" class="form-control"
                                                id="site-email-demo"
                                                value="{{ $settings->where('code', 'email_demo')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center d-none">
                                        <label for="site-email-event"
                                            class="form-control-label col-sm-3 text-md-right">Default Email Event</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="email_event" class="form-control"
                                                id="site-email-event"
                                                value="{{ $settings->where('code', 'email_event')->first()->value }}">
                                        </div>
                                    </div>
                                    <div class="card-footer text-md-right">
                                        <button class="btn btn-primary save-btn">Save Changes</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="shield" aria-labelledby="shield-tab" role="tabpanel">
                                    {{-- radio button --}}
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-3 text-md-right">Front End Mode</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="shield-on" name="whitelist_frontend"
                                                    class="custom-control-input" value="1"
                                                    {{ $settings->where('code', 'whitelist_frontend')->first()->value == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="shield-on">On</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="shield-off" name="whitelist_frontend"
                                                    class="custom-control-input" value="0"
                                                    {{ $settings->where('code', 'whitelist_frontend')->first()->value == 0 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="shield-off">Off</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-3 text-md-right">Ip Address
                                            Frontend</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="ip_frontend" class="form-control"
                                                id="ip-address-frontend"
                                                value="{{ $settings->where('code', 'ip_frontend')->first()->value }}">
                                        </div>
                                    </div>
                                    {{-- radion --}}
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-3 text-md-right">Back End Mode</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="shield-on-backend" name="whitelist_backend"
                                                    class="custom-control-input" value="1"
                                                    {{ $settings->where('code', 'whitelist_backend')->first()->value == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="shield-on-backend">On</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="shield-off-backend" name="whitelist_backend"
                                                    class="custom-control-input" value="0"
                                                    {{ $settings->where('code', 'whitelist_backend')->first()->value == 0 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="shield-off-backend">Off</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-3 text-md-right">Ip Address Backend</label>
                                        <div class="col-sm-6 col-md-9">
                                            <input type="text" name="ip_backend" class="form-control"
                                                id="ip-address-backend" data-role="tagsinput"
                                                value="{{ $settings->where('code', 'ip_backend')->first()->value }}">
                                                
                                        </div>
                                    </div>
                                    <div class="card-footer text-md-right">
                                        <button class="btn btn-primary save-btn">Save Changes</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="maintenance" aria-labelledby="maintenance-tab"
                                    role="tabpanel">
                                    {{-- radion button --}}
                                    <div class="form-group row align-items-center">
                                        <label class="form-control-label col-sm-3 text-md-right">Maintenance Mode</label>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="maintenance-on" name="offline_status"
                                                    class="custom-control-input" value="1"
                                                    {{ $settings->where('code', 'offline_status')->first()->value == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="maintenance-on">On</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="maintenance-off" name="offline_status"
                                                    class="custom-control-input" value="0"
                                                    {{ $settings->where('code', 'offline_status')->first()->value == 0 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="maintenance-off">Off</label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- maintenance message  --}}
                                    <div class="form-group row align-items-center">
                                        <label for="maintenance-message"
                                            class="form-control-label col-sm-3 text-md-right">Maintenance Message</label>
                                        <div class="col-sm-6 col-md-9">
                                            <textarea name="offline_message" rows="10" id="maintenance-message" class="form-control h-50">{{ $settings->where('code', 'offline_message')->first()->value }}</textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer text-md-right">
                                        <button class="btn btn-primary save-btn">Save Changes</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('javascript')
    <script>
        $('#add-social-btn').click(function() {
            // 1 form 1 button
            var html = '<div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="social-name" class="form-control-label col-sm-3 text-md-right">Name<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_social[name][]" class="form-control" id="social-name">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="social-value" class="form-control-label col-sm-3 text-md-right">Value<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_social[value][]" class="form-control" id="social-value">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="social-icon" class="form-control-label col-sm-3 text-md-right">Link<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_social[icon][]" class="form-control" id="social-icon">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row" style="justify-content: end;">' +
                '<div class="col-sm-8 col-md-8">' +
                '</div>' +
                '<div class="col-sm-4 col-md-4">' +
                '<button class="btn btn-danger btn-block remove-social-btn" id="testt">Remove Social</button>' +
                '</div>' +
                '<hr>' +
                '</div>';
            $('#data-social').append(html);
        });

        $('#add-market-btn').click(function() {
            // 1 form 1 button
            var html = '<div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="market-name" class="form-control-label col-sm-3 text-md-right">Name<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_market[name][]" class="form-control" id="market-name">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="market-value" class="form-control-label col-sm-3 text-md-right">Value<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_market[value][]" class="form-control" id="market-value">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row align-items-center">' +
                '<label for="market-icon" class="form-control-label col-sm-3 text-md-right">Link<b style="color: red">*</b></label>' +
                '<div class="col-sm-6 col-md-9">' +
                '<input type="text" name="web_market[icon][]" class="form-control" id="market-icon">' +
                '</div>' +
                '</div>' +
                '<div class="form-group row" style="justify-content: end;">' +
                '<div class="col-sm-8 col-md-8">' +
                '</div>' +
                '<div class="col-sm-4 col-md-4">' +
                '<button class="btn btn-danger btn-block remove-market-btn" id="testt">Remove market</button>' +
                '</div>' +
                '<hr>' +
                '</div>';
            $('#data-market').append(html);
        });

        // dokumen ready
        $(document).ready(function() {

        
            // ketika button remove di klik
            $(document).on('click', '.remove-social-btn', function() {
                // hapus form
                $(this).parent().parent().parent().remove();
            });

            $(document).on('click', '.remove-market-btn', function() {
                // hapus form
                $(this).parent().parent().parent().remove();
            });

            $('.save-btn').click(function(e) {
                e.preventDefault();
                var data = $('#form_submit').serialize();
                // token
                var token = $('meta[name="csrf-token"]').attr('content');
                // ajax
                swal({
                    title: "Are you sure?",
                    text: "you will change the settings",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('settings.update') }}",
                            type: "POST",
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': token
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    swal({
                                        title: "Success",
                                        text: response.message,
                                        icon: "success",
                                        button: "Ok",
                                    });
                                } else {
                                    swal({
                                        title: "Error",
                                        text: response.message,
                                        icon: "error",
                                        button: "Ok",
                                    });
                                }
                            },
                            error: function(response) {
                                swal({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error",
                                    button: "Ok",
                                });
                            }
                        });
                    } else {
                        swal("Your settings is safe!");
                    }
                });
               
            });

        });
    </script>
@endsection
