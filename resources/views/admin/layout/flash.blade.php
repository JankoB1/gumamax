				@if(Session::has('flash_warning'))
                					<div class="alert alert-warning fade in">
                						<button class="close" data-dismiss="alert">
                							×
                						</button>
                						<i class="fa-fw fa fa-warning"></i>
                						<strong>Warning</strong> {{ Session::get('flash_warning') }}
                					</div>
                @endif
                @if(Session::has('flash_success'))
                					<div class="alert alert-success fade in">
                						<button class="close" data-dismiss="alert">
                							×
                						</button>
                						<i class="fa-fw fa fa-check"></i>
                						<strong>Success</strong> {{ Session::get('flash_success') }}
                					</div>
                @endif
                @if(Session::has('flash_info'))
                					<div class="alert alert-info fade in">
                						<button class="close" data-dismiss="alert">
                							×
                						</button>
                						<i class="fa-fw fa fa-info"></i>
                						<strong>Info!</strong> {{ Session::get('flash_info') }}
                					</div>
                @endif
                @if (Session::has('flash_danger'))
                					<div class="alert alert-danger fade in">
                						<button class="close" data-dismiss="alert">
                							×
                						</button>
                						<i class="fa-fw fa fa-times"></i>
                						<strong>Error!</strong> {{ Session::get('flash_danger') }}
                					</div>
                @endif