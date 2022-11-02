<!DOCTYPE html>
<html lang="fr">

<style>

</style>


    @include('layout.header')
    @yield('header')


<body class="">
    <div id="loader"></div>
    <div class="main-wrapper">

		<!-- partial:partials/_sidebar.html -->
		@include('layout.sidebar')
		@yield('sidebar')
		<!-- partial -->

		<div class="page-wrapper">

			<!-- partial:partials/_navbar.html -->
			@include('layout.menu')
			@yield('menu')

			<!-- partial -->


            @yield('main')

			<!-- partial:partials/_footer.html -->
			@include('layout.footer')
    		@yield('footer')
			<!-- partial -->

		</div>
	</div>



</body>
</html>
