@section('header')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menalco - 2022</title>



        <!-- core:css -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">

              <!-- Layout styles -->
              <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.min.css') }}" id="lnk">
              <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">


        <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
        <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- end plugin css for this page -->
        <!-- inject:css -->
        <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}"> --}}
        <!-- endinject -->
        <!-- End layout styles -->
        
        <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-steps/jquery.steps.css') }}">
    </head>
@endsection
