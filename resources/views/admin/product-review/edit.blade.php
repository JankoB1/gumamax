@extends('admin.master')

@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                {!! Former::vertical_open()->id('form-edit')->method($formMethod)->action($formUrl)->secure()!!}

                {!! Former::populate($model) !!}

                @if($formMethod=='PUT'){!! Former::hidden('id')!!}@endif

                <div class="row-fluid">
                    <div class="col-sm-6 padding-5">
                        {!! Former::text('nickname')->label('Nadimak')->readonly() !!}
                    </div>
                    <div class="col-sm-6 padding-5">
                        {!! Former::text('email')->label('Email')->readonly() !!}
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="col-sm-12 padding-5">
                        {!! Former::text('review_title')->label('Naslov')->readonly() !!}
                        {!! Former::textarea('review_product')->label('Mišljenje o proizvodu')->readonly() !!}
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="col-sm-4">
                        {!! Former::text('dry_traction')->label('Prijanjanje na suvom')->readonly() !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Former::text('wet_traction')->label('Prijanjanje na mokrom')->readonly() !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Former::text('steering_feel')->label('Upravljivost')->readonly() !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Former::text('quietness')->label('Bučnost')->readonly() !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Former::text('purchase_again')->label('Kupio/la bih ponovo')->readonly() !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Former::text('overall_rating')->label('Ukupna ocena')->readonly() !!}
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="col-sm-12 padding-5">
                        {!! Former::text('site_rating')->label('Ocena sajta')->readonly() !!}
                        {!! Former::textarea('site_review')->label('Mišljenje o sajtu')->readonly() !!}
                    </div>
                </div>


                <div class="row-fluid">
                    <div class="col-sm-6 padding-5">
                        <div class="form-group">
                            <label for="approved_at">Ocena prihvaćena</label>
                            <div class='input-group date' id='approved_at_picker' data-linked_input="approved_at">
                                <input type='text' class="form-control" value="{!! (empty($model->approved_at) ? null : date('d.m.Y H:i', strtotime($model->approved_at))) !!}" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <input class="" id="approved_at" name="approved_at" type="hidden" value="{!! (empty($model->approved_at) ? '' : date('d.m.Y H:i', strtotime($model->approved_at))) !!}">
                    </div>
                    <div class="col-sm-6 padding-5">
                        <div class="form-group">
                            <label for="rejected_at">Ocena odbijena</label>
                            <div class='input-group date' id='rejected_at_picker' data-linked_input="rejected_at">
                                <input type='text' class="form-control" value="{!! (empty($model->rejected_at) ? null : date('d.m.Y H:i', strtotime($model->rejected_at))) !!}" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <input class="" id="rejected_at" name="rejected_at" type="hidden" value="{!! (empty($model->rejected_at) ? '' : date('d.m.Y H:i', strtotime($model->rejected_at))) !!}">
                    </div>
                </div>

                <div data-class="col-md-12 text-center">
                    <button type="submit" class="btn btn-info btn-large">Sačuvaj izmene</button>
                </div>
                {!! Former::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>

        $(function() {

            $.validator.addMethod("checkDates", function() {
                var approved_at = !!$('#approved_at').val(),
                        rejected_at = !!$('#rejected_at').val();

                return approved_at !== rejected_at
            },function() {
                return ($('#approved_at').val() && $('#rejected_at').val()) ? 'Nije moguće uneti obe vrednosti' : 'Unesite jednu od vrednosti'
            });

            $("#form-edit").validate({
                rules: {
                    approved_at: {
                        checkDates: true
                    },
                    rejected_at: {
                        checkDates: true
                    }
                },

                messages: {},

                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                ignore: [],
                groups: {
                    dates: "approved_at rejected_at"
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.attr("id") == "approved_at" || element.attr("id") == "rejected_at" ) {
                        error.insertAfter("#rejected_at");
                    } else
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });


            function setDate(sender, e) {
                if (e.date !== false) {
                    var dt = e.date.format("YYYY-MM-DD HH:mm:ss");
                } else {
                    dt = null;
                }

                var lin = '#' + sender.data('linked_input');
                $(lin).val(dt);
            }


            $('#approved_at_picker, #rejected_at_picker').datetimepicker({
                useCurrent: false, //Important! See issue #1075
                format: 'DD.MM.YYYY HH:mm',
                showClear: true
            });

            $('#approved_at_picker, #rejected_at_picker').on("dp.change", function (e) {
                $(this).data("DateTimePicker").minDate(e.date);
                setDate($(this), e);
            });
        });

    </script>

@endsection
