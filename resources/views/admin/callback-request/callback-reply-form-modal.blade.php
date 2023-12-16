<!-- start:modal contact reply form -->
            {!! Former::vertical_open()
            ->action($formUrl)
            ->id('callback-request-reply-form-message')
            ->method($formMethod)
            ->secure() !!}

            {!! Former::populate($model) !!}

            @if($formMethod=='PUT'){!! Former::hidden('id')!!}@endif

            <div class="modal-body">
                {!! Former::text('agent_id')->readonly() !!}
                {!! Former::text('created_at')->label('Kreirano')->readonly() !!}
                {!! Former::text('name')->label('Kupac')->readonly() !!}
                {!! Former::text('phone')->label('Telefon')->readonly()!!}

                <div class="form-group">
                    <label for="called_at">Pozvano u</label>
                    <div class='input-group date' id='called_at_picker' data-linked_input="called_at">
                        <input type='text' class="form-control" value="{!! date('d.m.Y H:i', strtotime($model->called_at)) !!}" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    </div>
                </div>
                <input class="" id="called_at" name="called_at" type="hidden" value="{!! date('Y-m-d H:i:s', strtotime($model->called_at)) !!}">

                {!! Former::textarea('answer')->label('Naš odgovor')->autofocus() !!}

                {!! Former::setOption('automatic_label', false) !!}
                {!! Former::checkbox('closed')->text('Završeno') !!}
                {!! Former::setOption('automatic_label', true) !!}

            </div>
            {!! Former::close() !!}

    <script id="modal-validation-adminfrmCallback">

        $(function() {

            $("#callback-request-reply-form-message").validate({
                rules: {
                    answer: {
                        required: true,
                        maxlength: 512
                    },

                    agent_id: {
                        required: true
                    }
                },

                messages: {},

                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            function setDate(sender, e) {
                var dt = e.date.format("YYYY-MM-DD HH:mm:ss");
                var lin = '#' + sender.data('linked_input');
                $(lin).val(dt);
            }


            $('#datetime_start_picker').datetimepicker({format: 'DD.MM.YYYY HH:mm'});

            $('#called_at_picker').datetimepicker({
                useCurrent: false, //Important! See issue #1075
                format: 'DD.MM.YYYY HH:mm'
            });

            $("#called_at_picker").on("dp.change", function (e) {
                $('#called_at_picker').data("DateTimePicker").minDate(e.date);
                setDate($(this), e);
            });
        });
    </script>
<!-- end:modal contact reply form -->