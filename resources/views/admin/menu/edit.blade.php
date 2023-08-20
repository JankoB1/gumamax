@extends('admin.master')

@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                {!! Former::vertical_open()->id('form-edit')->method($formMethod)->action($formUrl)->secure()!!}

                {!! Former::populate($model) !!}

                @if($formMethod=='PUT'){!! Former::hidden('id')!!}@endif

                {!! Former::text('parent_id') !!}

                {!! Former::text('order_index')->label('Redosled') !!}

                {!! Former::text('title')!!}

                {!! Former::text('route_name') !!}

                {!! Former::text('params')->help('Param1=Val1&Param2=Val2&...') !!}

                {!! Former::text('url') !!}

                {!! Former::setOption('automatic_label', false) !!}
                {!! Former::checkbox('is_active')->text('Aktivan') !!}
                {!! Former::setOption('automatic_label', true) !!}


                {!! Former::text('icon')->label('Ikonica (fontawesome fa-....)') !!}

                <div data-class="col-md-12 text-center">
                    <button type="submit" class="btn btn-info btn-large">Sačuvaj izmene</button>
                </div>
                {!! Former::close() !!}
            </div>

@endsection


@section('js')

    <script>

        $(function(){

            $("#form-edit").validate({
                rules : {
                    title : {
                        required: true,
                        maxlength : 32
                    },

                    order_index : {
                        required: true,
                        digits : true
                    }
                },

                messages : {},

                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endsection
