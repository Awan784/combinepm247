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
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            function getAvailableEngineers(){
                $.ajax({
                    url:"{{url('api/available_engineer_after_interval')}}",
                    success:function(data){
                        deployFetchedData(data);
                    }
                })
            }

            function deployFetchedData(engineers){
                let half =  Math.ceil(engineers.length / 2);
                let firstHalfEngineers = engineers.splice(0,half);
                let secondHaldEngineers = [...engineers];

                let firstHalfRows = firstHalfEngineers.map(engineer => {
                    let jobtypespills = engineer.engineer.job_types.map(jobtype => {
                        return `<span class="badge super-badge ${jobtype.jobtype.bgcolor} ms-1">${jobtype.jobtype.title}</span>`;
                    });
                    return `<tr>
                                <td class="text-gray-900 ">${engineer.engineer.name}</td>

                                    <td class="text-gray-900 ">
                                        ${jobtypespills.join(" ")}
                                    </td>

                            </tr>`;
                })
                $("#half1").html(firstHalfRows);

                let secondHalfRows = secondHaldEngineers.map(engineer => {
                    let jobtypespills = engineer.engineer.job_types.map(jobtype => {
                        return `<span class="badge super-badge ${jobtype.jobtype.bgcolor} ms-1">${jobtype.jobtype.title}</span>`;
                    });
                    return `<tr>
                                <td class="text-gray-900 ">${engineer.engineer.name}</td>

                                    <td class="text-gray-900 ">
                                        ${jobtypespills.join(" ")}
                                    </td>

                            </tr>`;
                })
                $("#half2").html(secondHalfRows);
            }

            var interval = setInterval(getAvailableEngineers,30*1000);
            getAvailableEngineers();
        </script>
    </head>
    <body>

        <main >
            <nav style="display:none" class="pb-0 navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2">
                <div class="px-0 container-fluid">
                    <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
                        <div class="d-flex align-items-center">

                        </div>
                        <ul class="navbar-nav align-items-center">

                            <li class="nav-item ms-lg-3">

                                <a href="login.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
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


            <div class="row">
                <div class="mb-4 col-12">
                    <div class="row">
                        <div class="mb-4 col-12">
                            <div class="border-0 shadow card">
                                <div class="card-header available-engineer-card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h2 class="mb-0 fs-5-custom fw-bold">Engineers Available Today</h2></div>
                                        {{-- <div class="col text-end"><a href="today-engineers.html" class="btn btn-sm btn-outline-gray-600">See all</a></div> --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxl-6">
                                        <div class="table-responsive">
                                            <table class="table available-engineer-table align-items-center table-flush">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                        <th class="border-bottom fw-bolder" scope="col">Job Type</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="half1"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div class="table-responsive">
                                            <table class="table available-engineer-table align-items-center table-flush">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                        <th class="border-bottom fw-bolder" scope="col">Job Type</th>

                                                    </tr>
                                                </thead>
                                                <tbody id="half2"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>





                            </div>
                        </div>
                        <div class="mb-4 col-12 col-xxl-6">
                            <div class="border-0 shadow card">
                                <div class="card-header available-engineer-card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h2 class="mb-0 fs-5-custom fw-bold">Job Type Engineers</h2></div>
                                        <div class="col text-end"><a href="{{url('job-type-engineers')}}" class="btn btn-sm btn-outline-gray-600">See all</a></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table available-engineer-table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class=" border-bottom fw-bolder" scope="col">Job Type</th>
                                                <th class=" border-bottom fw-bolder" scope="col">No of Engineers</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobTypes as $type)
                                                <tr>
                                                    <td class="text-gray-900">{{$type->title}}</td>
                                                    <td class="text-gray-900">{{$type->engineersAvailableToday()}}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-xxl-6">
                            <div class="border-0 shadow card">
                                <div class="card-header available-engineer-card-header">
                                    <div class="row align-items-center">
                                        <div class="col"><h2 class="mb-0 fs-5-custom fw-bold">Weekly Available Engineers </h2></div>
                                        <div class="col text-end"><a href="{{url('weekly-available-engineers')}}" class="btn btn-sm btn-outline-gray-600">See all</a></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table available-engineer-table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class=" border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                <th class=" border-bottom fw-bolder" scope="col">No of Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $engineers = [];
                                            foreach ($weeklyAvailable as $available)
                                            {
                                                $isFounded = -1;
                                                foreach($engineers as $key => $engineer)
                                                {
                                                    if($available->engineer){
                                                        if($engineer["id"] == $available->engineer->id){
                                                            $isFounded = $key;
                                                        }
                                                    }
                                                }
                                                if($isFounded >= 0)
                                                {
                                                    //engineer data founded
                                                    $totalHours = $engineers[$isFounded]["totalHours"];
                                                    $startTime = \Carbon\Carbon::parse($available->start_time);
                                                    $endTime = \Carbon\Carbon::parse($available->end_time);
                                                    $tHours = $endTime->diffInHours($startTime);
                                                    $totalHours += $tHours;
                                                    $engineers[$isFounded]["totalHours"] = $totalHours;
                                                }else{
                                                    $startTime = \Carbon\Carbon::parse($available->start_time);
                                                    $endTime = \Carbon\Carbon::parse($available->end_time);
                                                    $tHours = $endTime->diffInHours($startTime);
                                                    $engineers[] = [
                                                        "id" => $available->engineer ? $available->engineer->id : '',
                                                        "name" => $available->engineer ? $available->engineer->name : '',
                                                        "totalHours" => $tHours
                                                    ];
                                                }
                                            }
                                            usort($engineers, function($a, $b) {
                                    return $b['totalHours'] <=> $a['totalHours'];
                                });
                                $engineers = array_splice($engineers,0,10);
                                            @endphp
                                            @foreach($engineers as $engineer)
                                            <tr>
                                                <td class="text-gray-900">{{$engineer["name"]}}</td>
                                                <td class="text-gray-900">

                                                    {{$engineer["totalHours"]}}
                                                </td>
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


    </body>
</html>
