@extends('admin.master')

@section('content')
@if($errors->any())
    <div class="alert alert-error">
    	<ul>
			{!! implode('', $errors->all('<li>:message</li>')) !!}
    	</ul>
    </div>
@endif

	<div class="col-md-12">
		{!! Former::open()->method('POST')->action(url('admin/pictures/carousel'))->addClass('dropzone')->id('form-edit-pictures')->secure() !!}
			<button type="submit" id="save-carousel-pictures" class="btn btn-info btn-large">Sačuvaj izmene</button>
		{!! Former::close() !!}
	</div>

<div class="col-md-12">
@foreach($pictures as $pic)
	<div class="admin-carousel-img-list">
		<div class="text-right"><a href="{!! url('admin/pictures/carousel/delete/'.$pic) !!}"><span class="glyphicon glyphicon-remove"></span> Ukloni</a></div>
		<div class=""><img src="{!!url('carousel/'.$pic)!!}" alt="" style="max-width:235px;max-height:150px;"></div>
		<div>{!!$pic!!}</div>
	</div>
@endforeach
</div>
@stop

@section('js')

	<script type="text/javascript">

		new Dropzone("#form-edit-pictures",  {
			autoProcessQueue: false,
			uploadMultiple: true,
			parallelUploads: 4,
			maxFiles: 1,
			maxFilesize: 0.25, //100kB
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
			dictMaxFilesExceeded: 'Dozvoljeno je postavljanje najviše 1 fajla.',

			// The setting up of the dropzone
			init: function() {
				var myDropzone = this;

				// First change the button to actually tell Dropzone to process the queue.
				this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
					// Make sure that the form isn't actually being sent.
					e.preventDefault();
					e.stopPropagation();
					myDropzone.processQueue();
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
					// uspesno poslati fajlovi
					// window.history.back(-2);
					// TODO: ako se uradi ovako back, nece biti osvezene i vidljive novododate slike
					// znaci da mora da se uradi redirect sa osvezavanjem stranice
					// window.location.href = ...
				});
			}

		};
	</script>
@endsection
