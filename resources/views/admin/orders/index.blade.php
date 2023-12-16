@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="widget-title">Porudžbenice</h5>
            </div>

            <div class="widget-body no-padding">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="orders-table">
                    <thead>
                    <tr>
                        <th>ID-s</th>
                        <th>Kupac</th>
                        <th>Datum</th>
                        <th>Vrednost</th>
                        <th>Plaćanje</th>
                        <th>Adresa za isporuku</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.orders.payment.index-modal')
@endsection

@section('js')
    @parent
    <script>      
        var createPayment = null;

        $(function() {
            var addButton = $('.addButton');
            var editButton = $('.editButton');
            var deleteButton = $('.deleteButton');
            
            var table = $('#orders-table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": urlTo('admin/api/dt/orders/{!! $status !!}'),
                "order": [[ 0, 'desc' ]],
                "columns": [
                    {data: "id", name: "order.id", searchable: true, responsivePriority: 1},
                    {data: "user_id", name: "order.user_id", searchable: true},
                    {data: "date", name: "order.date", searchable: true},
                    {data: "total_amount_with_tax", name: "order.total_amount_with_tax", searchable: true},
                    {data: "payment_status", name: "payment_status.description", searchable: true},
                    {data: "shipping_recipient", name: "order.shipping_recipient", searchable: true},
                    {data: "actions", name: "actions", searchable: false, responsivePriority: 0},
                    {data: "shipping_city", name: "order.shipping_city", searchable: true, visible: false},
                    {data: "shipping_phone", name: "order.shipping_phone", searchable: true, visible: false},
                    {
                        data: "shipping_postal_code",
                        name: "order.shipping_postal_code",
                        searchable: true,
                        visible: false
                    },
                    {data: "user_first_name", name: "order.user_first_name", searchable: true, visible: false},
                    {data: "user_last_name", name: "order.user_last_name", searchable: true, visible: false},
                    {data: "user_company_name", name: "order.user_company_name", searchable: true, visible: false},
                    {data: "user_phone_number", name: "order.user_phone_number", searchable: true, visible: false},
                    {data: "user_email", name: "order.user_email", searchable: true, visible: false},
                    {
                        data: "user_customer_type_id",
                        name: "order.user_customer_type_id",
                        searchable: false,
                        visible: false
                    },
                    {data: "erp_reference_id", name: "order.erp_reference_id", searchable: false, visible: false},
                    {data: "cart_id", name: "order.cart_id", searchable: false, visible: false},
                    {data: "number", name: "order.number", searchable: true, visible: false},
                    {
                        data: "total_amount_without_tax",
                        name: "order.total_amount_without_tax",
                        searchable: false,
                        visible: false
                    },
                    {data: "total_tax_amount", name: "order.total_tax_amount", searchable: false, visible: false},
                    {data: "discount_amount", name: "order.discount_amount", searchable: false, visible: false},
                    {data: "payment_method", name: "payment_method.description", searchable: false, visible: false},
                    {data: "payment_status_id", name: "order.payment_status_id", searchable: false, visible: false},
                ],

                "rowCallback": function (nRow, aData, iDisplayIndex) {

                    $('td:eq(0)', nRow).html(
                        '<strong>Broj:<span class="pull-right">' + aData.number + '</strong></span><br>' +
                        'CartId:<span class="pull-right">' + aData.cart_id + '</span><br>' +
                        'OrderId:<span class="pull-right">' + aData.id + '</span><br>' +
                        'ErpRefId:<span class="pull-right">' + aData.erp_reference_id + '</span><br>'
                    );
                    $('td:eq(0)', nRow).data('order_id', aData.id);

                    $('td:eq(1)', nRow).html(
                        aData.user_id + '<br>' + aData.user_first_name + ' ' + aData.user_last_name + 
                        ((aData.user_customer_type_id == 2) ? '<br>' + aData.user_company_name : '<br>') +
                        aData.user_email + '<br>' +
                        aData.user_phone_number
                    );

                    $('td:eq(3)', nRow).html(
                        'Popust:<div class="pull-right">' + number_format(aData.discount_amount, 2, '.', ',', true) + '</div><br>' +
                        'Iznos bez PDV:<div class="pull-right">' + number_format(aData.total_amount_without_tax, 2, '.', ',', true) + '</div><br>' +
                        'PDV:<div class="pull-right">' + number_format(aData.total_tax_amount, 2, '.', ',', true) + '</div><br>' +
                        '<strong>Iznos sa PDV:<div class="pull-right"><strong>' + number_format(aData.total_amount_with_tax, 2, '.', ',', true) + '</div></strong><br>'
                    );

                    $('td:eq(4)', nRow).html(
                        function () {
                            var label = '';
                            switch (aData.payment_status_id) {
                                case 1:
                                    label = '<span class="label label-warning arrowed-in">' + aData.payment_status + '</span>';
                                    break;
                                case 2:
                                    label = '<span class="label label-success arrowed-in arrowed-in-right">' + aData.payment_status + '</span>';
                                    break;
                                case 3:
                                case 4:
                                case 5:
                                    label = '<span class="label label-danger arrowed arrowed-right">' + aData.payment_status + '</span>';
                                    break;
                                default :
                                    label = '<span class="label arrowed">' + aData.payment_status + '</span>';
                                    break;
                            }

                            return '<div class="pull-right">' + aData.payment_method + '</div><br>' +
                                    '<div class="pull-right">' + label + '</div><br>'
                        }
                    );

                    $('td:eq(5)', nRow).html(
                        aData.shipping_recipient + '<br>' +
                        aData.shipping_postal_code + ' ' + aData.shipping_city + '<br>' +
                        aData.shipping_phone
                    );

                    return nRow;
                }

            });

            table.on('click', 'tbody tr', function () {
                if ($(this).hasClass('row_selected')) {
                    $(this).removeClass('row_selected');
                }
                else {
                    table.$('tr.row_selected').removeClass('row_selected');
                    $(this).addClass('row_selected');
                }
            });

            $('table#orders-table tbody').on('click', 'a#btnShowPayment', function(){
                var orderId = $(this).data('order-id');

                if (orderId !== undefined) {
                    var modalPayment = $('#order-payment-modal-index');
                    modalPayment.data('order_id', orderId);
                    modalPayment.modal('show');
                    return false;               
                }
            }); 

            function jqCreatePayment(orderId, paymentMethodId, amount, userId) {
                
                showLoading();

                $.ajax({            
                    url: urlTo('admin/orders-payment/create'),
                    contentType: "application/json; charset=utf-8",
                    data: {
                        order_id: orderId,
                        payment_method_id: paymentMethodId,
                        amount: amount,
                        user_id: userId
                    }                   
                }).done(function(data) {
                    new BootstrapDialog.show({
                        title: 'Uplata za porudžbenicu',
                        cssClass:'fixed-body',
                        draggable: true                     
                    }).getModalBody().html(data);
                }).fail(function(xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }).always(function(){
                    hideLoading();
                });
            }

            createPayment = jqCreatePayment;
        });   
        

        function dmxModalDialog($title, $loadingMessage, $url, $dataTable, $lookUpControl, DmxObjId){
            return new BootstrapDialog({
                data : { 'senderId' : DmxObjId },
                title: $title,
                message: $('<div>'+$loadingMessage+'</div>').load($url),
                cssClass :'fixed-body',
                draggable: true,
                buttons : [
                    {
                        label: 'Close',
                        icon: 'fa fa-times',
                        action: function(dialogRef){
                            dialogRef.close();
                        }
                    },{
                        label:'Save',
                        icon: 'fa fa-save',
                        cssClass:'btn-primary',
                        //autospin : true,
                        action : function (dialogRef) {
                            var senderId = dialogRef.getData('senderId');
                            var form = dialogRef.$modalBody.find('#modal_'+senderId);
                            var btn = dialogRef.$modalFooter.find('.btn.btn-primary');
                            if (form.valid()){
                                dialogRef.enableButtons(false);
                                dialogRef.setClosable(false);
                                submitModalLaravelForm(form, dialogRef, btn, $dataTable, $lookUpControl);
                            }

                        }
                    }
                ],
                onhidden: function(dialogRef){
                    clearError(dialogRef.$modalBody.find('form'));
                }
            });
        }
    </script>
@endsection
