@extends('admin.master')
@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                {!! Former::vertical_open()->id('contact-form-show') !!}

                {!! Former::populate($model) !!}

                {!! Former::text('name')->label('Ime')->readonly() !!}

                {!! Former::text('email')->label('Email')->readonly() !!}

                {!! Former::text('from_ip')->label('IP')->readonly() !!}

                {!! Former::textarea('message')->label('Poruka')->readonly() !!}

                {!! Former::textarea('answer')->label('Odgovor')->readonly() !!}

                {!! Former::text('answered_at')->label('Odgovoreno')->readonly() !!}

                {!! Former::close() !!}

            </div>
        </div>
    </div>

@endsection