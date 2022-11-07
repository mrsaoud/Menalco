@section('footer')

    <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
        <p class="text-muted mb-1 mb-md-0">Copyright © 2022 <a href="http://www.menalco.com" target="_blank">Menalco</a>.</p>
        <p class="text-muted"> <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i></p>
    </footer>

        <!-- core:js -->
        <script src="{{  asset('assets/vendors/core/core.js') }}"></script>
        <script src="{{  asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/dropzone/dropzone.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
        <script src="{{  asset('assets/js/template.js') }}"></script>
        <script src="{{  asset('assets/js/blowfish.js') }}"></script>
        <script src="{{  asset('assets/js/dropify.js') }}"></script>
        <script src="{{  asset('assets/js/sweet-alert.js') }}"></script>
        <script src="{{  asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/select2/select2.min.js') }}"></script>
        <script src="{{  asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{  asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>

    @yield('scripts')

@endsection
