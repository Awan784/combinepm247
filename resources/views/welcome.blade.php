<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png" />
        <link rel="manifest" href="assets/img/favicon/site.webmanifest" />
        <link rel="mask-icon" href="assets/img/favicon/safari-pinned-tab.svg" color="#ffffff" />
        <meta name="msapplication-TileColor" content="#ffffff" />
        <meta name="theme-color" content="#ffffff" />
        <link type="text/css" href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" />
        <link type="text/css" href="vendor/notyf/notyf.min.css" rel="stylesheet" />
        <link type="text/css" href="vendor/fullcalendar/main.min.css" rel="stylesheet" />
        <link type="text/css" href="vendor/apexcharts/dist/apexcharts.css" rel="stylesheet" />
        <link type="text/css" href="vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
        <link type="text/css" href="vendor/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
        <link type="text/css" href="vendor/leaflet/dist/leaflet.css" rel="stylesheet" />
        <link type="text/css" href="css/volt.css" rel="stylesheet" />

    </head>
    <body>

        <main>
            <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
                <div class="container">

                    <div class="row justify-content-center form-bg-image" data-background-lg="assets/img/illustrations/signin.svg">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                                <div class="text-center text-md-center mb-4 mt-md-0"><h1 class="mb-0 h3">Sign in to our platform</h1></div>
                                <form action="{{url('')}}/" method="post" class="mt-4">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label for="email">Your Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                            </span>
                                            <input type="email" name="email" class="form-control" placeholder="example@company.com" id="email" autofocus required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group mb-4">
                                            <label for="password">Your Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                                <input type="password" name="password" placeholder="Password" class="form-control" id="password" required />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-top mb-4">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" value="" id="remember" /> <label class="form-check-label mb-0" for="remember">Remember me</label></div>
                                        </div>
                                    </div>
                                    <div class="d-grid"><button type="submit" class="btn btn-gray-800">Sign in</button></div>
                                    @if(Session::has("error"))
                                    <div class="d-grid">
                                        <div class="alert alert-danger mt-2">{{session('error')}}</div>
                                    </div>
                                    @endif
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <script src="vendor/%40popperjs/core/dist/umd/popper.min.js"></script>
        <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendor/onscreen/dist/on-screen.umd.min.js"></script>
        <script src="vendor/nouislider/distribute/nouislider.min.js"></script>
        <script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
        <script src="vendor/countup.js/dist/countUp.umd.js"></script>
        <script src="vendor/apexcharts/dist/apexcharts.min.js"></script>
        <script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
        <script src="vendor/simple-datatables/dist/umd/simple-datatables.js"></script>
        <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
        <script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
        <script src="vendor/fullcalendar/main.min.js"></script>
        <script src="vendor/dropzone/dist/min/dropzone.min.js"></script>
        <script src="vendor/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="vendor/notyf/notyf.min.js"></script>
        <script src="vendor/leaflet/dist/leaflet.js"></script>
        <script src="vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script>
        <script src="vendor/svgmap/dist/svgMap.min.js"></script>
        <script src="vendor/simplebar/dist/simplebar.min.js"></script>
        <script src="vendor/sortablejs/Sortable.min.js"></script>
        <script async defer="defer" src="buttons.github.io/buttons.js"></script>
        <script src="assets/js/volt.js"></script>

    </body>
</html>
