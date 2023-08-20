@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="widget-title">Payment gateway log</h5>
            </div>

            <div class="widget-body no-padding">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="payment-gateway-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>ID-s</th>
                        <th>Kupac</th>
                        <th>Datum</th>
                        <th>Vrednost</th>
                        <th>Plaćanje</th>
                        <th>Transakcija</th>                                            
                        <th>Akcije</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.payment-gateway.backoffice.index-modal')

@endsection

@section('js')
    @parent
    <script>  
        $(function() {

            var table = $('#payment-gateway-table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": urlTo('admin/api/dt/payment-gateway'),               
                "columns": [   
                    {data: null, className: 'details-control', orderable: false, defaultContent: ''},                 
                    {data: "id", name: "id", searchable: true, responsivePriority: 1},
                    {data: "user_id", name: "user_id", searchable: true},
                    {data: "date", name: "date", searchable: true, "orderData": [19]},
                    {data: "total_amount_with_tax", name: "total_amount_with_tax", searchable: true},
                    {data: "payment_status", name: "payment_status", searchable: true},
                    {data: "payment_method", name: "payment_method", searchable: true},
                    {data: "actions", name: "actions", searchable: false, responsivePriority: 0},
                    {data: "user_first_name", name: "user_first_name", searchable: true, visible: false},
                    {data: "user_last_name", name: "user_last_name", searchable: true, visible: false},
                    {data: "user_company_name", name: "user_company_name", searchable: true, visible: false},
                    {data: "user_phone_number", name: "user_phone_number", searchable: true, visible: false},
                    {data: "user_email", name: "user_email", searchable: true, visible: false},                   
                    {data: "erp_reference_id", name: "erp_reference_id", searchable: false, visible: false},
                    {data: "cart_id", name: "cart_id", searchable: false, visible: false},
                    {data: "number", name: "number", searchable: true, visible: false},
                    {data: "total_amount_without_tax", name: "total_amount_without_tax", searchable: false,  visible: false},
                    {data: "total_tax_amount", name: "total_tax_amount", searchable: false, visible: false},
                    {data: "discount_amount", name: "discount_amount", searchable: false, visible: false},
                    {data: "created_at", name: "created_at", searchable: false, visible: false}                   
                ],
                "order": [[3, 'desc']], 
                select: {
                    style: 'os',
                    selector: 'td:not(:first-child)'
                },
                "rowCallback": function (nRow, aData, iDisplayIndex) {

                    $('td:eq(1)', nRow).html(
                        '<strong>Broj:<span class="pull-right">' + aData.number + '</strong></span><br>' +
                        'CartId:<span class="pull-right">' + aData.cart_id + '</span><br>' +
                        'OrderId:<span class="pull-right">' + aData.id + '</span><br>' +
                        'ErpRefId:<span class="pull-right">' + aData.erp_reference_id + '</span><br>'
                    );
                    $('td:eq(1)', nRow).data('order_id', aData.id);

                    $('td:eq(2)', nRow).html(
                        aData.user_id + '<br>' + aData.user_first_name + ' ' + aData.user_last_name + 
                        ((aData.user_customer_type_id == 2) ? '<br>' + aData.user_company_name : '<br>') +
                        aData.user_email + '<br>' +
                        aData.user_phone_number
                    );

                    $('td:eq(4)', nRow).html(
                        'Popust:<div class="pull-right">' + number_format(aData.discount_amount, 2, '.', ',', true) + '</div><br>' +
                        'Iznos bez PDV:<div class="pull-right">' + number_format(aData.total_amount_without_tax, 2, '.', ',', true) + '</div><br>' +
                        'PDV:<div class="pull-right">' + number_format(aData.total_tax_amount, 2, '.', ',', true) + '</div><br>' +
                        '<strong>Iznos sa PDV:<div class="pull-right"><strong>' + number_format(aData.total_amount_with_tax, 2, '.', ',', true) + '</div></strong><br>'
                    );

                    $('td:eq(5)', nRow).html(
                        function () {
                            var label = '';

                            switch (aData.payment_status_id) {
                                case 1:
                                    label = '<span class="label label-warning arrowed-in">' + aData.payment_status + '</span>';
                                    break;
                                case 2:
                                    label = '<span class="label label-success arrowed-in arrowed-in-right">' + aData.payment_status + '</span>';
                                    break;
                                case 1100:
                                    label = '<span class="label label-danger arrowed-in">' + aData.payment_status + '</span>';
                                    break;
                                case 4:
                                case 5:
                                    label = '<span class="label label-danger arrowed arrowed-right">' + aData.payment_status + '</span>';
                                    break;
                                default :
                                    label = '<span class="label arrowed">' + aData.payment_status + '</span>';
                                    break;
                            }

                            return '<div class="pull-right">' + aData.payment_method + '</div><br>' +
                                    '<div class="pull-right">' + label + '</div><br>';                          
                        }
                    );    

                    $('td:eq(6)', nRow).html(
                        function() {
                            var content = '',
                                badgeColor = 'info';

                            if (aData.payment_type != null) {

                                switch (aData.payment_type) {
                                    case 'PA':
                                        badgeColor = 'info';
                                        break;
                                    case 'CP':
                                        badgeColor = 'success';
                                        break;
                                    case 'RF': 
                                        badgeColor = 'danger'
                                        break;
                                    case 'RV':
                                        badgeColor = 'pink';
                                        break;
                                }
                                content = '<span class="badge badge-' + badgeColor +'">' + aData.payment_type + '</span>';
                            }

                            if (aData.descriptor != null) {
                                content = content + '<br>' + '<div class="dt-txt-block">' + aData.descriptor + '</div>';
                            }
                            return content;
                        }
                    );                

                    return nRow;
                }
            });

            function createChildTable(rowData) { 

                var table = $('<table class="dt-child-table">'); 

                    showLoading();                    
 
                    $.ajax({
                        url: urlTo('admin/api/dt/payment-gateway/details/' + rowData.id),                        
                        dataType: 'json'
                    }).done(function (data) {
                            var tblHtml = '';

                            $.each(data.data, function(i, val){
                                var body = htmlDecode(val.body);

                                tblHtml = tblHtml + `
                                    <tr>
                                        <td>Order id: ${val.order_id}<br>
                                        Code: ${val.code}<br>
                                        Description: ${val.description}<br>
                                        Created at: ${val.created_at}</td>
                                        <td>${body}</td>
                                    </tr>`;   
                            })  
                            table.html(tblHtml);                                                       
                    }).always(function() {
                        hideLoading();
                    });
 
                    return table;
            }

            $('#payment-gateway-table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
            
                if (row.child.isShown()) {                   
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(createChildTable(row.data()), 'dt-child-row').show();                                
                    tr.addClass('shown');
                }
            });

            $('table#payment-gateway-table tbody').on('click', '#btnBackofficeShow', function(){
                var orderId = $(this).data('order-id');

                if (orderId !== undefined) {
                    var modal = $('#backoffice-modal');
                    modal.data('order_id', orderId);

                    showLoading();
                    $.get(urlTo('admin/backoffice/' + orderId), function(data){
                        modal.find('.modal-body').html(data);
                        modal.modal('show');
                        
                        hideLoading();
                    });                   
                    
                    return false;               
                }
            });

        });
    </script>
@endsection