@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Send Excel
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Send Files Securely</span></h3>
    </div>
     <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js'></script>
    <!--<script  src="{{ URL::asset('resources/assets/js/dropzone.js') }}"></script>-->
       

    <div class="from-lgn formgroup text-center no-padding col-sm-12">
        <div class="concur-login form_wrapper width-800">
            <div class="msgalert-section row">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                 @endif
                 @if (isset($message))
                    <p id="message">{{ $message }}</p>
                @endif
            </div>    
         <div class="row row-wrapper ">
            <form method="POST" enctype="multipart/form-data" class="attachment-input-form">
                
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="input-group row input-attachment-group">
                    <div class="file-import-section col-sm-12 ">
                        <div class="input-value col-sm-12 no-padding">
                            <fieldset>


								<div class="attachment-dropdown" style="background: rgba(239, 247, 252, 0.65); ">
                                                                    <input type="file" id="fileselect" name="excel_file[]" multiple="multiple"  style="width:100%; max-width:100%; "/>
									
									<div id="filedrag">
										<div class="dropdown-img">
											<img src="{{ URL::asset('resources/assets/images/drop-download.png') }}" />
										</div>
										<label>Drop files to upload</label>
										<div class="click-here-btn">
											<span>or click here</span>
										</div>
									</div>
								</div>

								<div id="submitbutton">
									<button type="submit">Upload Files</button>
								</div>
					</fieldset>
                                    
     				</div>
                        <div id="messages"></div>
                    </div>
                </div>

                <div class="input-group col-sm-12 submit">
                    <div class="input-value value-submit">
                        
                        <!-- <input type="submit" name="submit" value="Submit"/> -->
                        <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        
                    </div>
                </div>

            </form>
          </div>
        </div><!--form_wrapper-->
    </div><!--formgroup-->
</div><!--content_section-->
<script>
    (function() {

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		m.innerHTML = msg ;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
			var data = []
		for (var i = 0, file; file = files[i]; i++) {
                        data.push([
                            "<p>File Name: <strong>" + file.name +
			"</strong><br/>type: <strong>" + file.name.split('.').pop() +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		
                        ]) ;
		}
                Output(data);
	}



	// initialize
	function Init() {

		var fileselect = $id("fileselect"),
			filedrag = $id("filedrag"),
			submitbutton = $id("submitbutton");

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";

			// remove submit button
			submitbutton.style.display = "none";
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


})();
</script>

@stop