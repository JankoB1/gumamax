@inject('menu', 'Gumamax\Layout\DmxMenuBuilderAce3')
@inject('companies', 'Crm\Models\Company')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Gumamax Admin</title>
		<meta name="csrf-token" content="{!! csrf_token() !!}">
		<meta name="description" content="Administracija za Gumamax.com" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

		<!-- ace styles -->
        <link rel="stylesheet" href="{{ mix('css/admin.css') }}" id="main-ace-style">

		<!-- page specific plugin styles -->
		@yield('custom-css')
		<!-- ace settings handler -->
        <script src="{{ asset('assets/admin/ace/js/ace-extra.min.js') }}"></script>


        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
        <!--[if lte IE 8]>
        <script src="{{ asset('assets/admin/ace/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('assets/admin/ace/js/respond.min.js') }}"></script>
        <![endif]-->
	</head>

	<body class="no-skin">
	<div id="loading-screen" style="display: none;"></div>

		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

            <div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>
				<!-- /section:basics/sidebar.mobile.toggle -->
                <div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<img src="{!! asset('assets/admin/ace/img/gmx_logo_xsmall.png') !!}" alt="Logo">
							Gumamax Admin
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
                </div>
                <div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="dark-opaque">
							@include('admin.layout.select-company')
						</li>
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<i class="ace-icon fa fa-user"></i>
								<span class="user-info">
									<small>Dobrodošli, Onaj</small>
								</span>

                                <i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                                <li>
                                    <a href="{!! url(route('show-homepage')) !!}">
                                        <i class="ace-icon fa fa-home"></i>
                                        Go to home
                                    </a>
                                </li>
								<li>
									<a href="{{URL::to('profile')}}">
										<i class="ace-icon fa fa-user"></i>
                                        Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="{{URL::to('logout')}}">
										<i class="ace-icon fa fa-power-off"></i>
                                        Odjavi se
                                    </a>
								</li>
							</ul>
						</li>
					</ul><!-- /.ace-nav -->
				</div><!-- /.container-fluid -->
			</div><!-- /.navbar-inner -->
		</div>

		<div class="main-container" id="main-container">
            <script type="text/javascript">
                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
            </script>

			<div class="sidebar responsive" id="sidebar">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>
				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->
				<!-- nav-list -->
					{!! $menu->build(20000000) !!}
				<!-- /.nav-list -->
				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<div class="main-content">
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs', 'fixed')}catch(e){}
					</script>
					{!! $menu->getBreadcrumbs() !!}
				</div>
                <div class="page-content">
					<div class="page-header">
						@yield('page-header')
					</div>
                    <div class="row">
						<div class="col-xs-12">
							@include('admin.layout.flash-js')
							@include('admin.layout.errors')
								<!-- PAGE CONTENT BEGINS -->
							@yield('content')
						</div>
                            <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.row-fluid -->
                </div><!-- /.page-content -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
			<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
		</a>

    <script src="{{ asset('assets/admin/js/ace-admin.js') }}"></script>
	<script type="text/javascript">try{ace.settings.navbar_fixed(true,true)}catch(e){}</script>
	<script type="text/javascript">try{ace.settings.sidebar_fixed(true,true)}catch(e){}</script>
	<script type="text/javascript">try{ace.settings.breadcrumbs_fixed(true,true)}catch(e){}</script>

{{--
    {!! HTML::script('/assets/admin/js/lib/jquery/jquery.cookie-1.4.1.min.js') !!}
	{!! HTML::script('/assets/admin/js/lib/jquery/jquery.rateit.min.js') !!}
	{!! HTML::script('/assets/admin/js/admin-edit-pricelist.js') !!}
		--}}

@yield('page-plugin-js')

	<script>
		var dtLanguage =  {
			"emptyTable":     "No data available in table",
			"info":           "_START_ - _END_ / _TOTAL_",
			"infoEmpty":      " 0 - 0 / 0",
			"infoFiltered":   "(_MAX_)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     "_MENU_",
			"loadingRecords": "Loading...",
			"processing":     "Processing...",
			"search":         "",
			"zeroRecords":    "No matching records found",
			"paginate": {
				"first":      "First",
				"last":       "Last",
				"next":       "Next",
				"previous":   "Previous"
			},
			"aria": {
				"sortAscending":  ": activate to sort column ascending",
				"sortDescending": ": activate to sort column descending"
			}
		};
		$.extend(true, $.fn.DataTable.defaults, {
						info: true,
						serverSide: true,
						responsive: true,
						processing: true,
						/* stateSave: true,*/
						language: dtLanguage,
						order: []
					}
			);

		function updateQueryString(key, value, url) {
			if (!url) url = window.location.href;
			var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
					hash;

			if (re.test(url)) {
				if (typeof value !== 'undefined' && value !== null)
					return url.replace(re, '$1' + key + "=" + value + '$2$3');
				else {
					hash = url.split('#');
					url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
					if (typeof hash[1] !== 'undefined' && hash[1] !== null)
						url += '#' + hash[1];
					return url;
				}
			}
			else {
				if (typeof value !== 'undefined' && value !== null) {
					var separator = url.indexOf('?') !== -1 ? '&' : '?';
					hash = url.split('#');
					url = hash[0] + separator + key + '=' + value;
					if (typeof hash[1] !== 'undefined' && hash[1] !== null)
						url += '#' + hash[1];
					return url;
				}
				else
					return url;
			}
		}
	</script>
@yield('js')

@stack('scripts')

</body>
</html>
