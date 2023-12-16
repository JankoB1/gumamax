<!-- start:pics -->
<div id="pics-page">

@if($errors->any())
    <div class="alert alert-error">
    	<ul>
			{!! implode('', $errors->all('<li>:message</li>')) !!}
    	</ul>
    </div>
@endif

@if($partner->logo->file)
	<div class="text-center">
		<div class="pull-right">
			<a href="{!! url('partner/'.$partner->partner_id.'/delete/logo')!!}" title="Ukloni logo">
				<i class="glyphicon glyphicon-remove"></i> Ukloni
			</a>
		</div>
		<img src="{!! url($partner->logo->file->file_path.$partner->logo->file->file_name) !!}" alt="Logo" id="logo" class="logo94">
	</div>
	<br>
@endif

{!! Former::open()
	->action(url('/partner/'.$partner->partner_id.'/save/logo'))
	->id('form-edit-logo') !!}

	<legend>Logo firme</legend>
	<input type="hidden" name="partner_id" value="{!!$partner->partner_id!!}">

	<fieldset>
		<p>Poželjna veličina logoa je 94px x 94px, u suprotnom dolazi do skaliranja slike.</p>
		<div class="dropzone-link dropzone-link1">Kliknite ovde da dodate logo.</div>
		<div class="dropzone-previews dropzone-previews1"></div>
	</fieldset>

	<fieldset class="text-center">
	<input type="submit" value="Sačuvaj izmene" id="save-logo" name="save-partner-logo" class="btn btn-primary btn-large">
	</fieldset>

{!! Former::close() !!}

<br>

@if($partner->cover->file)
	<div class="text-center">
		<div class="pull-right">
			<a href="{!! url('partner/'.$partner->partner_id.'/delete/cover')!!}" title="Ukloni cover">
				<i class="glyphicon glyphicon-remove"></i> Ukloni
			</a>
		</div>
		<img src="{!! url($partner->cover->file->file_path.$partner->cover->file->file_name) !!}" alt="" id="cover">
	</div>
	<br>
@endif

{!! Former::open()
	->action(url('/partner/'.$partner->partner_id.'/save/cover'))
	->id('form-edit-cover') !!}

	<legend>Cover photo</legend>
	<input type="hidden" name="partner_id" value="{!!$partner->partner_id!!}">

	<fieldset>
		<p>Poželjna veličina slike je 1170px x 350px, u suprotnom dolazi do skaliranja slike.</p>
		<div class="dropzone-link dropzone-link3">Kliknite ovde da dodate sliku.</div>
		<div class="dropzone-previews dropzone-previews3"></div>
	</fieldset>

	<fieldset class="text-center">
	<input type="submit" value="Sačuvaj izmene" id="save-cover" name="save-partner-cover" class="btn btn-primary btn-large">
	</fieldset>

{!! Former::close() !!}

<br>

<div class="row">
@foreach($partner->attachments as $attachment)
<div class="col-md-3" style="margin-bottom:15px;">
	<div class="img-box" style="padding:10px;border:1px solid #eee;">
		<div class="text-right"><a href="{!! url('partner/'.$partner->partner_id.'/delete/picture/'.$attachment->id) !!}"><span class="glyphicon glyphicon-remove"></span> Ukloni</a></div>
		<div class="img"><img src="{!!url($attachment->file_path.$attachment->file_name)!!}" alt="" class="img-responsive"></div>
	</div>
</div>
@endforeach
</div>


{!! Former::open()
	->action(url('/partner/'.$partner->partner_id.'/save/pictures'))
	->id('form-edit-pictures')
	->files(true) !!}

	<legend>Slike</legend>
	<input type="hidden" name="partner_id" value="{!!$partner->partner_id!!}">

	<fieldset>
		<div class="dropzone-link dropzone-link2">Kliknite ovde da dodate slike.</div>
		<div class="dropzone-previews dropzone-previews2"></div>
	</fieldset>
	<fieldset class="text-center"><input type="submit" value="Sačuvaj izmene" id="save-pics" name="save-partner-pictures" class="btn btn-primary btn-large"></fieldset>

{!! Former::close() !!}

<small><em><strong>Napomena: dozvoljeno je postavljanje do 4 slike</strong></em></small>

</div>
{!! HTML::script('js/vendor/dropzone.js') !!}
<script id="partner-pics">
	Dropzone.autoDiscover = false;
	new Dropzone("#form-edit-pictures",  {
	  autoProcessQueue: false,
	  uploadMultiple: true,
	  parallelUploads: 4,
	  maxFiles: "{!! 4 - (int)(count($partner->attachments))!!}",
	  maxFilesize: 1, //1MB
	  previewsContainer: '.dropzone-previews2',
	  clickable: '.dropzone-link2',
	  addRemoveLinks: true,
	  acceptedFiles: 'image/*',
	  dictDefaultMessage: 'Kliknite ovde da dodate fajlove.',
	  dictFallbackMessage: 'Vaš pregledač ne podržava slanje fajlova metodom drag\'n\'drop.',
	  dictFallbackText: 'Koristite formular za slanje fajlova.',
	  dictInvalidFileType: 'Tip fajla nije podržan.',
	  dictFileTooBig: 'Fajl je veći od dozvoljenog.',
	  dictCancelUpload: 'Otkaži slanje.',
	  dictCancelUploadConfirmation: 'Jeste li sigurni da želite da otkažete slanje?',
	  dictRemoveFile: 'Ukloni',
	  dictMaxFilesExceeded: 'Dozvoljeno je postavljanje najviše 4 fajla.',

	  // The setting up of the dropzone
	  init: function() {
		var myDropzone2 = this;

		// First change the button to actually tell Dropzone to process the queue.
		this.element.querySelector("input#save-pics").addEventListener("click", function(e) {
		  // Make sure that the form isn't actually being sent.
		  e.preventDefault();
		  e.stopPropagation();
		  myDropzone2.processQueue();
		});

		// Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
		// of the sending event because uploadMultiple is set to true.
		this.on("sendingmultiple", function() {
		  // Gets triggered when the form is actually being sent.
		  // Hide the success button or the complete form.
		});
		this.on("successmultiple", function(files, response) {
		  // Gets triggered when the files have successfully been sent.
		  // Redirect user or notify of success.
		});
		this.on("errormultiple", function(files, response) {
		  // Gets triggered when there was an error sending the files.
		  // Maybe show form again, and notify user of error
		});
		this.on("success", function(){
			//poslati fajlovi
			window.location = '{!! url("profile?tab=pics") !!}';
		});
	  }

	});

	new Dropzone("#form-edit-logo",  {
		autoProcessQueue: false,
		uploadMultiple: false,
		parallelUploads: 1,
		maxFiles: 1,
		maxFilesize: 0.5, //0.5MB
		previewsContainer: '.dropzone-previews1',
		clickable: '.dropzone-link1',
		addRemoveLinks: true,
		acceptedFiles: 'image/*',
		dictDefaultMessage: 'Kliknite ovde da dodate fajl.',
		dictFallbackMessage: 'Vaš pregledač ne podržava slanje fajlova metodom drag\'n\'drop.',
		dictFallbackText: 'Koristite formular za slanje fajla.',
		dictInvalidFileType: 'Tip fajla nije podržan.',
		dictFileTooBig: 'Fajl je veći od dozvoljenog.',
		dictCancelUpload: 'Otkaži slanje.',
		dictCancelUploadConfirmation: 'Jeste li sigurni da želite da otkažete slanje?',
		dictRemoveFile: 'Ukloni',
		dictMaxFilesExceeded: 'Dozvoljeno je slanje jednog fajla.',

		// The setting up of the dropzone
		init: function() {
			var myDropzone1 = this;

			// First change the button to actually tell Dropzone to process the queue.
			this.element.querySelector("input#save-logo").addEventListener("click", function(e) {
				// Make sure that the form isn't actually being sent.
				e.preventDefault();
				e.stopPropagation();
				myDropzone1.processQueue();
			});

			// Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
			// of the sending event because uploadMultiple is set to true.
			this.on("sendingmultiple", function() {
				// Gets triggered when the form is actually being sent.
				// Hide the success button or the complete form.
			});
			this.on("successmultiple", function(files, response) {
				// Gets triggered when the files have successfully been sent.
				// Redirect user or notify of success.
			});
			this.on("errormultiple", function(files, response) {
				// Gets triggered when there was an error sending the files.
				// Maybe show form again, and notify user of error
			});
			this.on("success", function(){
				//poslati fajlovi
				window.location = '{!! url("profile?tab=pics") !!}';
			});
		}

	});

	new Dropzone("#form-edit-cover",  {
		autoProcessQueue: false,
		uploadMultiple: false,
		parallelUploads: 1,
		maxFiles: 1,
		maxFilesize: 1, //1MB
		previewsContainer: '.dropzone-previews3',
		clickable: '.dropzone-link3',
		addRemoveLinks: true,
		acceptedFiles: 'image/*',
		dictDefaultMessage: 'Kliknite ovde da dodate fajl.',
		dictFallbackMessage: 'Vaš pregledač ne podržava slanje fajlova metodom drag\'n\'drop.',
		dictFallbackText: 'Koristite formular za slanje fajla.',
		dictInvalidFileType: 'Tip fajla nije podržan.',
		dictFileTooBig: 'Fajl je veći od dozvoljenog.',
		dictCancelUpload: 'Otkaži slanje.',
		dictCancelUploadConfirmation: 'Jeste li sigurni da želite da otkažete slanje?',
		dictRemoveFile: 'Ukloni',
		dictMaxFilesExceeded: 'Dozvoljeno je slanje jednog fajla.',

		// The setting up of the dropzone
		init: function() {
			var myDropzone3 = this;

			// First change the button to actually tell Dropzone to process the queue.
			this.element.querySelector("input#save-cover").addEventListener("click", function(e) {
				// Make sure that the form isn't actually being sent.
				e.preventDefault();
				e.stopPropagation();
				myDropzone3.processQueue();
			});

			// Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
			// of the sending event because uploadMultiple is set to true.
			this.on("sendingmultiple", function() {
				// Gets triggered when the form is actually being sent.
				// Hide the success button or the complete form.
			});
			this.on("successmultiple", function(files, response) {
				// Gets triggered when the files have successfully been sent.
				// Redirect user or notify of success.
			});
			this.on("errormultiple", function(files, response) {
				// Gets triggered when there was an error sending the files.
				// Maybe show form again, and notify user of error
			});
			this.on("success", function(){
				//poslati fajlovi
				window.location = '{!! url("profile?tab=pics") !!}';
			});
		}

	});

</script>
<!-- end:pics -->
