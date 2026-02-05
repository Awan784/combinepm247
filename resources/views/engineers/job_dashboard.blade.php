<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png" />
        <link rel="manifest" href="assets/img/favicon/site.webmanifest" />
        <link rel="mask-icon" href="assets/img/favicon/safari-pinned-tab.svg" color="#ffffff" />
        <meta name="msapplication-TileColor" content="#ffffff" />
        <meta name="theme-color" content="#ffffff" />
        <link type="text/css" href="{{asset('vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/notyf/notyf.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/fullcalendar/main.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/apexcharts/dist/apexcharts.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/choices.js')}}/public/assets/styles/choices.min.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('vendor/leaflet/dist/leaflet.css')}}" rel="stylesheet" />
        <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet" />
    </head>
    <body>
        <main class="container-fluid">
            <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
                <div class="container-fluid px-0">
                    <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
                        <div class="d-flex align-items-center">
                            
                        </div>
                        <ul class="navbar-nav align-items-center">
                            
                            <li class="nav-item  ms-lg-3">
                                
                                <a href="{{url('logout')}}" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                                    <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sign Out
                                </a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="row py-4">
                <div class="col-12 mb-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h2 class="fs-5 fw-bold mb-0">Contracts</h2></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table    table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                                                <th class="border-bottom fw-bolder" scope="col">Job Invoice Number</th>
                                                <th class="border-bottom fw-bolder" scope="col">Sent By</th>
                                                <th class="border-bottom fw-bolder" scope="col">Status</th>
                                                <th class="border-bottom fw-bolder" scope="col">Contract Sent</th>
                                                <th class="border-bottom fw-bolder" scope="col">Contract Received</th>
                                                <th class="border-bottom fw-bolder" scope="col">Engineer Informed</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach ($contracts as $contract)
                                                <tr>
                                                    <td class=" text-gray-900">{{$contract->job->engineer_user->name ?? "Not Assign Yet"}}</td>
                                                    <td class=" text-gray-900">{{$contract->job->postcode}}</td>
                                                    <td class=" text-gray-900">{{$contract->job->job_invoice_no}}</td>
                                                    <td class=" text-gray-900">{{$contract->sent_by_user->name ?? ""}}</td>
                                                    <td class=" text-gray-900">
                                                        <span class="badge super-badge bg-{{$contract->status == "sent" ? 'success' : 'info'}} ms-1">
                                                            {{$contract->status}}
                                                        </span>
                                                    </td>
                                                    <td class=" text-gray-900">{{$contract->sent_time}}</td>
                                                    <td class=" text-gray-900">{{$contract->received_time}}</td>
                                                    <td class=" text-gray-900">{{$contract->inform_time}}</td>
                                                </tr>
                                            @endforeach
                                          </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h2 class="fs-5 fw-bold mb-0">Payments</h2></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                                                <th class="border-bottom fw-bolder" scope="col">Job Invoice Number</th>
                                                <th class="border-bottom fw-bolder" scope="col">Sent By</th>
                                                <th class="border-bottom fw-bolder" scope="col">Status</th>
                                                <th class="border-bottom fw-bolder" scope="col">Payment Sent</th>
                                                <th class="border-bottom fw-bolder" scope="col">Payment Received</th>
                                                <th class="border-bottom fw-bolder" scope="col">Engineer Informed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $payment)
                                                <tr>
                                                    <td class=" text-gray-900">{{$payment->job->engineer_user->name ?? "Not Assign Yet"}}</td>
                                                    <td class=" text-gray-900">{{$payment->job->postcode}}</td>
                                                    <td class=" text-gray-900">{{$payment->job->job_invoice_no}}</td>
                                                    <td class=" text-gray-900">{{$payment->sent_by_user->name ?? ""}}</td>
                                                    <td class=" text-gray-900">
                                                        <span class="badge super-badge bg-{{$payment->status == "sent" ? 'success' : 'info'}} ms-1">
                                                            {{$payment->status}}
                                                        </span>
                                                    </td>
                                                    <td class=" text-gray-900">{{$payment->sent_time}}</td>
                                                    <td class=" text-gray-900">{{$payment->received_time}}</td>
                                                    <td class=" text-gray-900">{{$payment->inform_time}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        <script src="{{asset('vendor/countup.js')}}/dist/countUp.umd.js')}}"></script>
        <script src="{{asset('vendor/apexcharts/dist/apexcharts.min.js')}}"></script>
        <script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
        <script src="{{asset('vendor/simple-datatables/dist/umd/simple-datatables.js')}}"></script>
        <script src="{{asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
        <script src="{{asset('cdnjs.cloudflare.com/ajax/libs/moment.js')}}/2.27.0/moment.min.js')}}"></script>
        <script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
        <script src="{{asset('vendor/fullcalendar/main.min.js')}}"></script>
        <script src="{{asset('vendor/dropzone/dist/min/dropzone.min.js')}}"></script>
        <script src="{{asset('vendor/choices.js')}}/public/assets/scripts/choices.min.js')}}"></script>
        <script src="{{asset('vendor/notyf/notyf.min.js')}}"></script>
        <script src="{{asset('vendor/leaflet/dist/leaflet.js')}}"></script>
        <script src="{{asset('vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js')}}"></script>
        <script src="{{asset('vendor/svgmap/dist/svgMap.min.js')}}"></script>
        <script src="{{asset('vendor/simplebar/dist/simplebar.min.js')}}"></script>
        <script src="{{asset('vendor/sortablejs/Sortable.min.js')}}"></script>
        <script async defer="defer" src="{{asset('buttons.github.io/buttons.js')}}"></script>
        <script src="{{asset('assets/js/volt.js')}}"></script>
 
    </body>
</html>
