<!-- start:modal contact reply form -->
    <div class="row">
        <div class="col-md-12">
            {!! Former::vertical_open()
            ->action($formUrl)
            ->id('contact-reply-form-message')
            ->method($formMethod)
            ->secure() !!}

            {!! Former::populate($model) !!}

            @if($formMethod=='PUT'){!! Former::hidden('id')!!}@endif


            <div class="modal-body">
                {!! Former::text('name')->label('Ime')->readonly() !!}
                {!! Former::email('email')->label('Email')->readonly() !!}
                {!! Former::textarea('message')->label('Poruka')->rows(3)->readonly() !!}
                {!! Former::textarea('answer')->label('Odgovor')->rows(3)->autofocus() !!}

            </div>
            {!! Former::close() !!}
        </div>
    </div>
<!-- end:modal contact reply form -->