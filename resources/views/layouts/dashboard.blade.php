{{-- @php
    
    dd(auth()->user()->hasReadWritePermission(1));

    
@endphp --}}
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
        @include("includes.headerscripts")
        @yield("header-scripts")
    </head>
    <body>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THQTXJ7" height="0" width="0" style="display: none; visibility: hidden;"></iframe></noscript>


            @include("includes.navigation")

        <main class="content">
            @include("includes.topbar")
            <x-greetings/>

            @yield("content")


        </main>
      @include("includes.footerscripts")
      @yield("body-scripts")
    </body>
</html>
