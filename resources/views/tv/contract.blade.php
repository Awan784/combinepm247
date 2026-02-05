<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
        <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/favicon/apple-touch-icon.png')}}" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon/favicon-32x32.png')}}" />
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon/favicon-16x16.png')}}" />
        <link rel="manifest" href="{{asset('assets/img/favicon/site.webmanifest')}}" />
        <link rel="mask-icon" href="{{asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#ffffff" />
        <meta name="msapplication-TileColor" content="#ffffff" />
        <meta name="theme-color" content="#ffffff" />
        <link type="text/css" href="{{asset('vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/notyf/notyf.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/fullcalendar/main.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/apexcharts/dist/apexcharts.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/choices.js/public/assets/styles/choices.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/leaflet/dist/leaflet.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet" />
    </head>
    <body>

        <main>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow">
                                <div class="table-responsive">
                                    <div id="job-tables-container">
                                        @include('tv.data.contract', ['jobs' => $jobs])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
        </main>
        

        <script src="{{asset('vendor/%40popperjs/core/dist/umd/popper.min.js')}}"></script>
        <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('vendor/onscreen/dist/on-screen.umd.min.js')}}"></script>
        <script src="{{asset('vendor/nouislider/distribute/nouislider.min.js')}}"></script>
        <script src="{{asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')}}"></script>
        <script src="{{asset('vendor/countup.js/dist/countUp.umd.js')}}"></script>
        <script src="{{ asset('vendor/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
        <script src="{{ asset('vendor/simple-datatables/dist/umd/simple-datatables.js') }}"></script>
        <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js') }}"></script>
        <script src="{{ asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
        <script src="{{ asset('vendor/fullcalendar/main.min.js') }}"></script>
        <script src="{{ asset('vendor/dropzone/dist/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('vendor/choices.js/public/assets/scripts/choices.min.js') }}"></script>
        <script src="{{ asset('vendor/notyf/notyf.min.js') }}"></script>
        <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
        <script src="{{ asset('vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js') }}"></script>
        <script src="{{ asset('vendor/svgmap/dist/svgMap.min.js') }}"></script>
        <script src="{{ asset('vendor/simplebar/dist/simplebar.min.js') }}"></script>
        <script src="{{ asset('vendor/sortablejs/Sortable.min.js') }}"></script>
        <script async defer="defer" src="{{ asset('buttons.github.io/buttons.js') }}"></script>
        <script src="{{ asset('assets/js/volt.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            var pageLoadTime = new Date().toISOString(); // ISO format for easy comparison
        
            function getLatestData() {
                $.ajax({
                    url: "{{ url('tv_dashboard/contract_latest_data') }}",
                    method: "GET",
                    data: {
                        loaded_time: pageLoadTime
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Update the page with the latest data
                            updatePage(response.data);
                        }
                    }
                });
            }
            var interval = setInterval(getLatestData, 30 * 1000); // 30 seconds interval
            getLatestData();
            function updatePage(data) {
                $('#job-tables-container').html(data);
            }
        </script>

    </body>
</html>
