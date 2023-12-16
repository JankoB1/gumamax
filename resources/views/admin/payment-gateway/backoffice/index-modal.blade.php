<!-- start:modal backoffice form -->
<div class="modal fade" id="backoffice-modal" data-order_id="" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="modal-title"><h4>Backoffice</h4></div>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<!-- end:modal backoffice form -->

@section('js')
    @parent
    <script>  
        $(function() {

            $("#backoffice-modal").on("shown.bs.modal", function() {

                $("form#frmBackofficeOp [type='radio']").on('change', function(){
                    if (this.value == 'full') {
                        $("#amount_partial").prop('disabled', true);
                    } else {
                        $("#amount_partial").prop('disabled', false);  
                    }
                });

                $(".nav li.disabled a").on('click', function(e) {
                    e.preventDefault();
                    return false;
                });

                $(".nav li:not('.disabled') a").on('click', function(e) {
                
                    if (!$(this).parent().hasClass('disabled')) {
                        $("input#payment_type").val($(this).data('operation'));
                        $("#submit").removeClass('disabled');
                    }
                });

                $("#submit").on('click', function(e){                    
                    if ($(this).hasClass("disabled")) {
                        e.preventDefault();
                    }
                })

                function disableActions() {
                    $("#submit").addClass('disabled');
                    $(".nav li.active").addClass('disabled');
                }

                $("#frmBackofficeOp").validate({
                    ignore: [],
                    rules: {
                        payment_type: {
                            required: true
                        },
                        amount_partial: {
                            required: true
                        }                    
                    },
                    messages: {
                        payment_type: {
                            required: 'Nije izabrana operacija!'
                        },
                        amount_partial: {
                            required: 'Nije uneta vrednost parcijalnog iznosa'
                        }   
                    },
                    errorClass: 'text-danger',
                    errorPlacement: function(error, element) {
                        error.appendTo($('#result'));
                    },
                                     
                    submitHandler: function(form) {
                        
                        showLoading();
                        $("#result").html('');
                       
                        $.ajax({
                            url: urlTo('admin/backoffice/operations'),
                            type: 'POST',
                            data: $(form).serialize()
                        }).done(function(data){
                            $('div#result').addClass('ok').html(data.description);                            
                        }).fail(function(xhr, textStatus, errorThrown){                           
                            var error = $.parseJSON(xhr.responseText).error;
                            $('div#result').addClass('error').html(error);
                        }).always(function(){
                            disableActions();
                            hideLoading();                            
                        });
                    }
                });
            });

            $("#backoffice-modal").on("hidden.bs.modal", function() {            
                $('#payment-gateway-table').DataTable().ajax.reload();
            });
        });
    </script>
@endsection