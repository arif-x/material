<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<title>RAB Material</title>
	<!-- core:css -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
	<!-- endinject -->
	<!-- plugin css for this page -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
	<!-- endinject -->
	<!-- Layout styles -->  
	<link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
	<!-- End layout styles -->
	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
	<!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
	


</head>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper">
			<!--Page Navbar Start-->
			@include('layouts.template.navbar')
			<!--Page Navbar Ends-->
			<!--Page Sidebar Start-->
			@include('layouts.template.sidebar')
			<!--Page Sidebar Ends-->
			<!--Page Content Start-->
			@yield('content')
			<!--Page Content Ends-->
			<!--Page Footer Start-->
			@include('layouts.template.footer')
			<!--Page Footer Ends-->
		</div>
	</div>

	<!-- core:js -->
	<script src="{{ URL::asset('/assets/vendors/core/core.js') }}"></script>
	<!-- endinject -->
	<!-- plugin js for this page -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
	<script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/chartjs/Chart.min.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
	<script src="{{ URL::asset('/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="{{ URL::asset('/assets/vendors/feather-icons/feather.min.js') }}"></script>
	<script src="{{ URL::asset('/assets/js/template.js') }}"></script>
	<!-- endinject -->
	<!-- custom js for this page -->
	<script src="{{ URL::asset('/assets/js/dashboard.js') }}"></script>
	<!-- end custom js for this page -->
</body>
</html>    
