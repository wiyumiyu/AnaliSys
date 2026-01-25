<!DOCTYPE html>
<html lang="en">

    <meta charset="utf-8" />
    <title>@yield('title', ' | FabKin Admin & Dashboards Template')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />

    {{-- JS FABKIN --}}
    <!-- layout setup -->
    <script type="module" src="/js/layout-setup.js"></script>


    @yield('css')
    @include('partials.head-css')

    <body>

        @include('partials.topbar')
        @include('partials.sidebarx')


        <main class="app-wrapper">
            <div class="container-fluid">

                @include('partials.page-title')

                @yield('content')
                @include('partials.switcher')
                @include('partials.scroll-to-top')
                @include('partials.footer')



                @yield('js')





                </body>

                </html>
