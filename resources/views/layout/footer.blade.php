@section('footer')

    <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
        <p class="text-muted mb-1 mb-md-0">Copyright © 2022 <a href="http://www.menalco.com" target="_blank">Menalco</a>.</p>
        <p class="text-muted"> <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i></p>
    </footer>

        <!-- core:js -->
        <script src="{{  asset('assets/vendors/core/core.js') }}"></script>
        <!-- endinject -->
        {{-- <script src="{{  asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script> --}}
        <script src="{{  asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
        {{-- <script src="{{  asset('assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script> --}}
        {{-- <script src="{{  asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script> --}}
        <script src="{{  asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/select2/select2.min.js') }}"></script>
        {{-- <script src="{{  asset('assets/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script> --}}
        {{-- <script src="{{  asset('assets/vendors/jquery-tags-input/jquery.tagsinput.min.js') }}"></script> --}}
        {{-- <script src="{{  asset('assets/vendors/dropzone/dropzone.min.js') }}"></script>--}}
        <script src="{{  asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/dropzone/dropzone.min.js') }}"></script>
        {{-- <script src="{{  asset('assets/vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script> --}}
        <script src="{{  asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        {{-- <script src="{{  asset('assets/vendors/moment/moment.min.js') }}"></script> --}}
        {{-- <script src="{{  asset('assets/vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script> --}}
        <script src="{{  asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
        <!-- inject:js -->
        <script src="{{  asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
        <script src="{{  asset('assets/js/template.js') }}"></script>
        <!-- endinject -->
    <!-- custom js for this page -->

    {{-- <script src="{{  asset('assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{  asset('assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script> --}}

    <script src="{{  asset('assets/js/dashboard.js') }}"></script>
    <script src="{{  asset('assets/js/datepicker.js') }}"></script>
	{{-- <script src="{{  asset('assets/js/bootstrap-maxlength.js') }}"></script> --}}
	<script src="{{  asset('assets/js/inputmask.js') }}"></script>
	<script src="{{  asset('assets/js/select2.js') }}"></script>
	{{-- <script src="{{  asset('assets/js/typeahead.js') }}"></script>
	<script src="{{  asset('assets/js/tags-input.js') }}"></script>
	<script src="{{  asset('assets/js/dropzone.js') }}"></script>
	<script src="{{  asset('assets/js/dropify.js') }}"></script> --}}
	{{-- <script src="{{  asset('assets/js/bootstrap-colorpicker.js') }}"></script> --}}
    <script src="{{  asset('assets/js/dropzone.js') }}"></script>
	<script src="{{  asset('assets/js/dropify.js') }}"></script>

	<script src="{{  asset('assets/js/datepicker.js') }}"></script>
	{{-- <script src="{{  asset('assets/js/timepicker.js') }}"></script> --}}
    <script src="{{  asset('assets/js/sweet-alert.js') }}"></script>
        <!-- end custom js for this page -->
        <script src="{{  asset('assets/js/chartjs.js') }}"></script>
        <script src="{{  asset('assets/js/wizard.js') }}"></script>
        <script src="{{  asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{  asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>

    @yield('scripts')

@endsection
