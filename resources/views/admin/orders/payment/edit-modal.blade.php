@inject('payment_method', 'Delmax\Models\PaymentMethod')
<!-- start:modal order payment form -->
    <div class="row">
        <div class="col-md-12">
            {!! Former::vertical_open()->id('form-edit')->method($formMethod)->action($formUrl)->secure()!!}

            {!! Former::populate($model) !!}

            @if($formMethod=='PUT'){!! Former::hidden('id')!!}@endif

            {!! Former::hidden('order_id') !!}
            {!! Former::hidden('user_id') !!}

            {!! Former::select('payment_method_id')->name('payment_method_id')->id('payment_method_id')
                    ->fromQuery($payment_method::all(), 'description', 'payment_method_id')
                ->label('Način plaćanja')
            !!}

            {!! Former::text('date')->label('Datum')->id('date') !!}

            {!! Former::text('description')->label('Opis') !!}

            {!! Former::text('amount')->label('Iznos') !!}

            <div data-class="text-center">
                <button type="submit" class="btn btn-info btn-large">Sačuvaj izmene</button>
            </div>
            <div class="alert alert-danger mt10"></div>
            {!! Former::close() !!}
            
        </div>
    </div>

    <script id="modal-validation-order-payments">

        $(function(){

            $('.alert').hide(); 
            $('#date').datetimepicker({
                format: 'DD.MM.YYYY'
             }); 

            $("#form-edit").validate({
                rules: {
                    date: {
                        required: true                    
                    },
                    description: {
                        required: true,
                        maxlength: 64
                    },
                    amount: {
                        required: true,
                        number: true
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
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: $(form).attr('action'),
                        type: form.method,
                        data: $(form).serialize(),    
                        success: function(){
                            $('.modal').modal('hide');    
                            $('#orders-table').DataTable().ajax.reload(null, false);                         
                        },
                        error: function(data, status) {                                                     
                            var error = data.responseJSON.message;

                            if (error) {
                                $('.alert').html('Greška: ' + error).show();  
                            }
                        },    
                    });
                }
            });
        });
    </script>
<!-- end:modal order payment form -->