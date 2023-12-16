String.prototype.smartTruncate =
    function( n, useWordBoundary ){
        var isTooLong = this.length > n,
            s_ = isTooLong ? this.substr(0,n-1) : this;
        s_ = (useWordBoundary && isTooLong) ? s_.substr(0,s_.lastIndexOf(' ')) : s_;
        return  isTooLong ? s_ + '&hellip;' : s_;
    };
/**
 * -----------------------------------------------------------
 * Data table filter
 * @param i
 * @param $table
 * @param $form
 */
function filterColumn(i, $table, $form) {
    var filterInput = $('input.column_filter[data-column="' + i + '"]', $form);
    var val = filterInput.val();
    var colIsNumeric = filterInput.data('numeric_search');

    if (typeof colIsNumeric !== typeof undefined && !colIsNumeric) {
        $table.DataTable().columns(i).search(val, true, false).draw();
    }
    else {
        $table.DataTable().columns(i).search(val, false, true).draw();
    }
}
/**
 *
 */
$('body').on('input', 'input.column_filter', function () {

    var $t = $(this);
    var form = $t.closest('form');
    var tableId = $(form).data('table');

    if (tableId){
        if (typeof search_timeout !== 'undefined') {
            clearTimeout(search_timeout);
        }

        search_timeout = setTimeout(function () {
            search_timeout = undefined;
            filterColumn($t.attr('data-column'), $('#'+tableId), $(form));
        }, 350);
    }
});


/**
 *
 */

$('body').on('click', '.clear_filter', function (e) {
    e.preventDefault();
    var $t = $(this);
    $('.column_filter', $('#'+$t.data('form'))).val('');
    $('#'+$t.data('table')).DataTable()
        .search('')
        .columns().search('')
        .draw();
});

$('body').on('preInit.dt', function (e, settings) {
    showLoading();
});

$('body').on( 'processing.dt', function ( e, settings, processing ) {
    if (processing){showLoading()}else{hideLoading()}
});


$('body').on( 'stateLoadParams.dt', function (e, settings, data) {
    $.each(data.columns, function(i, item){
        if (item.search.search!==''){
            $('input.column_filter[data-column="'+i+'"]').val(item.search.search);
        }
    })
});


$('body').on('click', 'tbody tr', function () {
    if ( !$(this).hasClass('row_selected') ) {
        $('tr.row_selected', $($(this).closest('table'))).removeClass('row_selected');
        $(this).addClass('row_selected');
    }
});

/**
 * -----------------------------------------------------------
 */

$('.wdg-project-menu>li>a').on('click', function(){

    var projectId = $(this).closest('.wdg-project-menu').data('project_id');

    if ($(this).hasClass('view_project_members')){
        window.location = urlTo("crm/project/members/"+projectId);
    }
});

$('.widget-box').on({
    'show.ace.widget': function (e) {
        //e.preventDefault();
        if ($(this).attr('id') == '#wdg-user-address') {
            google.maps.event.trigger(map, 'resize');
            //mapInitializeWithGeocoding();
        }
    },

    'admin.user.updated': function (e, data) {
        if ($(this).attr('id') == '#wdg-user-account') {
            $(this).find('.widget-header>h5').text(data.first_name + ' ' + data.last_name);
            $(this).widget_box('hide');
        }

    },

    'admin.customer.updated': function (e, data) {
        if ($(this).attr('id') == '#wdg-user-customer-type') {
            $(this).find('.widget-header>h5').text(data.customer_type.description);
            $(this).widget_box('hide');
        }
    },

    'admin.address.updated': function (e, data) {
        if ($(this).attr('id') == '#wdg-user-address') {
            $(this).find('.widget-header>h5').html((data.city_name + ', ' + data.address).smartTruncate(45,true));
            $(this).widget_box('hide');
        }
    }
});


$('body').on('click', 'tbody tr td.table-actions-column div a', function(){
    var table, id, url;

    if ($(this).hasClass('crm_edit_partner')) {

        id = $(this).data('id');
        url = urlTo('crm/partners/'+id+'/edit');

        table = $('#'+$(this).data('table'));
        if (typeof table!='undefined'){
            table=table.DataTable();
        }
        var $title = 'Partner';
        dmxModalDialog($title, 'Loading', url, 'form-partner-basic', '', table).open();
        return false;
    }

    if ($(this).hasClass('crm_edit_project_membership')) {

        id = $(this).data('id');
        url = urlTo('crm/member/'+id+'/edit');

        window.location = url;
        return false;
    }

    if ($(this).hasClass('crm_edit_project_members_information')) {
        id = $(this).data('id');
        url = urlTo('crm/member-information/'+id+'/edit');
        BootstrapDialog.show({
            size:BootstrapDialog.SIZE_WIDE,
            message: $('<div></div>').load(url)
        });
        return false;
    }

    if ($(this).hasClass('crm_delete_project_membership')) {
        id = $(this).data('id');
        deleteUrl = urlTo('crm/member/'+id);

        swal({
            title: "Brisanje partnera?",
            text: '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Da",
            cancelButtonText: "Ne",
            closeOnConfirm: true,
            closeOnCancel: true
        },

        function (isConfirm) {
            if (isConfirm) {                
                $.ajax({method:'delete', url: deleteUrl})
                    .done(function(){
                        $('#members-table').DataTable().ajax.reload();
                        swal('Partner obrisan!', '', 'info');
                    })
                    .fail(function(xhr, textStatus, errorThrown) {
                        var error = xhr.responseJSON.message;
                     
                        if (error) {
                            swal('Greška', error, 'error');
                        }
                    }
                );     
            }
        });               
    }

});

function dmxLoadContent($targetDiv, fromUrl, alwaysHide, callback){
    if ($targetDiv.is(':empty')){
        showLoading();
        $.ajax({method:'get', type:'html', url:fromUrl})
            .done(function(result){
                $targetDiv.html(result);
                if( typeof callback == "function" ){
                    callback();
                }
            })
            .fail(function() {
                hideLoading();
            }

            )
            .always(function(){
                if (alwaysHide||false)
                    hideLoading();
            }
        );
    }
}

$('.partner_change_data_request').on('click', function(){
    swal('TODO', 'Zahtev za promenu podataka', 'info');
});

function previewImage(fileInput, previewId, uploadButton) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var imageType = /image.*/;
        if (!file.type.match(imageType)) {
            continue;
        }
        var img=document.getElementById(previewId);
        img.file = file;
        var reader = new FileReader();
        reader.onload = (function(aImg) {
            return function(e) {
                aImg.src = e.target.result;
            };
        })(img);
        reader.readAsDataURL(file);
        if(uploadButton){
            var btn = document.getElementById(uploadButton);
            btn.style.display='inline-block';
        }
    }
}