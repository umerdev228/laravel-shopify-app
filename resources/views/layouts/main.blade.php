<!DOCTYPE html>
<html lang="en">

@include('partials.header-assets')

<body>

@include('partials.topbar')
@include('partials.sidebar')

<main id="main" class="main">
    @include('partials.bread_crumbs')

    @yield('content')
</main><!-- End #main -->

@include('partials.footer')
@include('partials.footer-assets')


</body>

</html>
