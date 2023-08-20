<!-- start:modal order payment form -->
<div class="modal fade" id="order-payment-modal-index" data-order_id="" tabindex="-1" role="dialog" aria-labelledby="order-payment-modal-index" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="modal-title"><h4>Uplate za odabrani dokument</h4></div>
            </div>
            <div class="modal-body">

                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="order-payment-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Način plaćanja</th>                       
                        <th>Datum uplate</th>
                        <th>Opis knjiženja</th>
                        <th>Iznos</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end:modal order payment form -->

@section('js')
    @parent
<script>
    $(function() {

        $('#order-payment-modal-index').on('shown.bs.modal', function() {
            var addButton = $('.addButton');
            var editButton = $('.editButton');
            var deleteButton = $('.deleteButton');
            var showPaymentButton = $('.showPaymentButton');
            var orderId = $("#order-payment-modal-index").data('order_id');
            var modal = $(this);
            
            modal.find('.modal-title > h4').text('Uplate za odabrani dokument: '+orderId);
            
            var orderPaymentTable = $('#order-payment-table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                destroy: true,
                dom:'tr',
                "ajax": urlTo('admin/api/dt/orders-payment/'+orderId),
                "columns": [
                    {data: "id", name: "id", searchable: true},
                    {data: "payment_method", name: "payment_method", searchable: true},
                    {data: "date", name: "date", searchable: true},
                    {data: "description", name: "description", searchable: true},
                    {data: "amount", name: "amount", searchable: true}
                ]
            });

            orderPaymentTable.on('click', 'tbody tr', function () {
                if ( $(this).hasClass('row_selected') ) {
                    $(this).removeClass('row_selected');
                }
                else {
                    orderPaymentTable.$('tr.row_selected').removeClass('row_selected');
                    $(this).addClass('row_selected');
                }
            } );

            addButton.on('click', function(){
                window.location = "{!!route('admin.menu.create')!!}";
            });

            editButton.on('click', function(){
                var id =  $(orderPaymentTable.row('.row_selected').node()).children().first().text();
                window.location = urlTo('admin/menu/'+id+'/edit');
            });

            deleteButton.on('click', function(e){
                var id =  $(orderPaymentTable.row('.row_selected').node()).children().first().text();
                //swalAlert
                //brisanje
            });

            showPaymentButton.on('click', function(){
                $('#order-payment-modal-index').modal('show');
            });
        });
    });

    $('#order-payment-modal-index').on('hidden.bs.modal', function() {

        if ( $.fn.dataTable.isDataTable( '#order-payment-table' ) ) {
            var mytable = $('#order-payment-table').DataTable();
            mytable.destroy();
            $('#order-payment-table').find('tbody').empty();
        }
    });


</script>
@endsection