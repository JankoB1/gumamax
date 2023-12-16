$("#addVehicle").addVehicle({
	vehicleUrl: urlTo('/vehicle/ajax'),
	vehicleList: $("#vehicle-list"),
	hiddenType: $("#type_id"),
	showNameField: false,
	showEngineCode: false,
	showVIN: false,
	sendButton: true,
    formGroupClass : 'add-vehicle-group',
	year:{"required":""},
	mfa_id:{"required":""},
	mod_id:{"required":""},
	typ_id:{"required":""},
	userId: $('#user_id').val(),
    close: function() {
        setTimeout(function(){
            window.location.href = urlTo("/profile?tab=vehicle"); 
        }, 1000);
        
    },
	htmlType: function(el, id, uvid, name){
		if(id=='' || uvid==''){
			return false;
		}
	    var html = '' +
	        '<tr class="vehicle">'+
	        	'<td>'+name+'</td>'+
	            '<td class="edit">'+
	                '<a class="action-edit" href="#" title="Izmeni" data-action="Izmeni"><i class="glyphicon glyphicon-pencil"></i></a>'+
	                '<a class="action-delete" href="#" title="Ukloni" data-action="Ukloni" data-id="'+uvid+'"><i class="glyphicon glyphicon-trash"></i></a>'+
	            '</td>' +
	     '</tr>';
	    $(html).appendTo(el).fadeIn("slow");
	}
});

var manufacturersByYear = $('#manufacturersByYear'),
	vehiclesByYear = $('#vehiclesByYear'),
	vehiclesType = $('#vehiclesType'),
    vehicleName = $('#vehicleName'),
    year = $('#year');

manufacturersByYear.on("change",function(){
	$('#manufacturersByYearLabel').val($(this).find('option:selected').text());
});

vehiclesByYear.on("change",function(){
	$('#vehiclesByYearLabel').val($(this).find('option:selected').text());
});

vehiclesType.on("change",function(){
	$('#vehiclesTypeLabel').val($(this).find('option:selected').text());
});


$(".container").on("click",".vehicle .action-delete",function(e){
    e.preventDefault();

    var uvid = $(this).data('id');

    swal({
        title: "Brisanje vozila",
        text: "Sigurno želite brisanje vozila?",
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
            $.ajax({
                type: "GET",
                url: urlTo("/profile/vehicle/"+uvid+"/delete")
            }).done(function(){
                window.location.href = urlTo("/profile?tab=vehicle");
            }).fail(function(){
                console.log('failed');
            });
        }
    });   
});

$('.form-vehicle-submit').on('click',function(e){
    e.preventDefault();
    var form = $(this).closest('form'),
        id = form.find('#user_vehicle_id').val(),
        data = {
            "vehicle_vin": form.find('#vehicle_vin').val(),
            "vehicle_engine": form.find('#vehicle_engine').val(),
            "_token": form.find("input[name='_token']").val()
        };
    console.log(data);
    $.ajax({
        type: "POST",
        url: urlTo("profile/vehicle/"+id+"/update"),
        data: data
    }).done(function(response){
        $('.form-edit[data-id='+id+']').hide();
        $('a.action-edit[data-id='+id+']').data('action','Izmeni').attr('title', "Izmeni").html('<i class="glyphicon glyphicon-pencil"></i>');
    });
});

$(".edit").on("click","a.action-edit i",function(e){
    e.preventDefault();
    var a = $(this).parent();

    if(a.data('action')=='Izmeni'){

        $('.form-edit[data-id='+a.data('id')+']').show();
        a.html('<i class="glyphicon glyphicon-remove"></i>');
        a.data('action','Zatvori');
        a.attr('title', "Zatvori");

    }else if(a.data('action')=='Zatvori'){

        $('.form-edit[data-id='+a.data('id')+']').hide();
        a.html('<i class="glyphicon glyphicon-pencil"></i>');
        a.data('action','Izmeni');
        a.attr('title', "Izmeni");
    }
});


