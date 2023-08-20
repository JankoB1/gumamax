Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

var resourceUrl = urlTo('address');

var vm = new Vue({
	el: '#address_table',
	data: {
		address_list: []
	},

	mounted: function () {
		this.fetchAddresses();
	},

	methods: {
		fetchAddresses: function () {
			var self = this;
			this.$http.get(apiUserAddressesUrl).then((response) => {
				self.address_list = response.body;
			}, (response)=>{
				//error handling
			});
		},

		addItem: function(){
			createAddress();
		},

		editItem : function(address, e){
			editAddress(address.id);
		},

		deleteItem : function(address, e){
			var self = this;
			swal({
					title: "Brisanje adrese",
					text: "Da li ste sigurni?",
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
						self.$http.delete(resourceUrl+'/'+address.id,  function(data, status, request){
							this.fetchAddresses();
						}).error(function(data, status, request){

						});
					}
				});
		}
	}

});

var loadingMessage = 'Učitavanje u toku...';

function createAddress() {

	var createAddressDialog = new BootstrapDialog({
		title : "Kreiranje adrese",
		message: $('<div>'+loadingMessage+'</div>').load(createAddressDialogUrl),
		onhidden: function(dialogRef){
			vm.fetchAddresses();
		}
	});

	createAddressDialog.open();
}


function editAddress(id){
	var editUrl = resourceUrl+'/'+id+'/edit';
	var editColumnDtDialog = new BootstrapDialog({
		title: "Edit",
		data: {"remoteUrl": ""},
		message: $('<div>'+loadingMessage+'</div>')
			.load(editUrl),
		onhidden:  function(dialogRef){
				vm.fetchAddresses();
		}
	});
	editColumnDtDialog.open();
}

