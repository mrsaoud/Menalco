<!DOCTYPE html>
<html lang="fr">

<style>

</style>


    @include('layout.header')
    @yield('header')


<body class="">

		<div class="page-wrapper">

            @yield('main')

			<!-- partial:partials/_footer.html -->
			@include('layout.footer')
    		@yield('footer')
			<!-- partial -->

		</div>
	



</body>
</html>
