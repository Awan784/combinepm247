@extends("layouts.dashboard")

@if(session('gmail_wrong'))
    @section('header-scripts')
        <style type="text/css">
            .modal#gmailErrorModal .modal-dialog {
                max-width: 400px;
            }
            .modal#gmailErrorModal .modal-content {
                border-radius: 30px;
            }
            .modal#gmailErrorModal .modal-content svg {
                width: 100px;
                display: block;
                margin: 0 auto;
            }
            .modal#gmailErrorModal .modal-content .path {
                stroke-dasharray: 1000;
                stroke-dashoffset: 0;
            }
            .modal#gmailErrorModal .modal-content .path.circle {
                -webkit-animation: dash 0.9s ease-in-out;
                animation: dash 0.9s ease-in-out;
            }
            .modal#gmailErrorModal .modal-content .path.line {
                stroke-dashoffset: 1000;
                -webkit-animation: dash 0.95s 0.35s ease-in-out forwards;
                animation: dash 0.95s 0.35s ease-in-out forwards;
            }
            .modal#gmailErrorModal .modal-content .path.check {
                stroke-dashoffset: -100;
                -webkit-animation: dash-check 0.95s 0.35s ease-in-out forwards;
                animation: dash-check 0.95s 0.35s ease-in-out forwards;
            }
            @-webkit-keyframes dash {
                0% {
                    stroke-dashoffset: 1000;
                }
                100% {
                    stroke-dashoffset: 0;
                }
            }
            @keyframes dash {
                0% {
                    stroke-dashoffset: 1000;
                }
                100%{
                    stroke-dashoffset: 0;
                }
            }
            @-webkit-keyframes dash {
                0% {
                    stroke-dashoffset: 1000;
                }
                100% {
                    stroke-dashoffset: 0;
                }
            }
            @keyframes dash {
                0% {
                    stroke-dashoffset: 1000;}
                100% {
                    stroke-dashoffset: 0;
                }
            }
            @-webkit-keyframes dash-check {
                0% {
                    stroke-dashoffset: -100;
                }
                100% {
                    stroke-dashoffset: 900;
                }
            }
            @keyframes dash-check {
                0% {
                    stroke-dashoffset: -100;
                }
                100% {
                    stroke-dashoffset: 900;
                }
            }
        </style>
    @endsection
@endif
@section("content")

    <!-- Accept Modal Start  -->
        <div class="modal fade" id="modal-accept" tabindex="-1" role="dialog" aria-labelledby="modal-accept" aria-hidden="true">
            <div class="modal-dialog modal-info modal-dialog-centered" role="document">
            <div class="modal-content bg-gray-200">
                <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h2 class="h4 modal-title">Accept Job!</h2>
                </div>
                <div class="modal-body">
                    <form method="post" id="acceptForm" action="">
                        @csrf
                        <div class="form-group mb-4"><label for="cartInputEmail1">Job Invoice No *</label> <input name="job_invoice_no" type="number" class="form-control" placeholder="89701"   required></div>

                        <div class="modal-footer"><button type="submit" class="btn btn-sm btn-gray-800 m-0">Accept</button></div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    <!-- Accept Modal End  -->
    <!-- Delete Modal Start  -->
        <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
            <div class="modal-dialog modal-info modal-dialog-centered" role="document">
            <div class="modal-content bg-gradient-danger">
                <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">

                </div>
                <div class="modal-body text-white">
                    <div class="py-3 text-center">
                        <span class="modal-icon">
                        <svg class="icon icon-xl text-gray-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        </span>
                        <h2 class="h4 modal-title my-3">Important message!</h2>
                        <p>Do you know to delete this Job ?</p>
                    </div>
                </div>
                    <form method="post" id="deleteForm" action="">
                        @csrf
                        <input type="hidden" name="_method" value="delete">
                        <div class="modal-footer"><button type="submit" class="btn btn-sm btn-white">Yes</button></div>
                    </form>
            </div>
            </div>
        </div>
    <!-- Delete Modal End  -->
    <div class="pb-4">
        <div class="py-4">
            <div class="mb-0">
                <p class="mb-3 alert alert-danger d-none" id="gmail-error"><span class="h4 text-danger">Gmail Error:</span> Your Gmail session expired! click <a href="{{url('mails-check')}}" class="text-decoration-underline"><b>here</b></a> to create new.</p>
            </div>
            <div class="d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h1 class="h4">Jobs List</h1>
                    <p class="mb-0">List of jobs in our system.</p>
                </div>
                <div>
                    <a href="{{url('jobs/create')}}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center mt-3">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Job
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive py-4">
                <!-- Tab Nav -->
                <div class="nav-wrapper position-relative mb-2 px-4">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-0-tab" data-bs-toggle="tab" href="#tabs-text-0" role="tab" aria-controls="tabs-text-1" aria-selected="false">Previous Active</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-text-1-tab" data-bs-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">Current Date</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-2-tab" data-bs-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">Future Date</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-3-tab" data-bs-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">Complete Jobs</a>
                        </li>
                    </ul>
                </div>
                <!-- End of Tab Nav -->
                <!-- Tab Content -->
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="tab-content" id="tabcontent1">
                            <div class="tab-pane fade" id="tabs-text-0" role="tabpanel" aria-labelledby="tabs-text-0-tab">
                                <table class="table table-flush" id="datatable0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-bottom fw-bolder" scope="col">CUSTOMER EMAIL</th>
                                            <th class="border-bottom fw-bolder" scope="col">POSTCODE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB TYPE</th>
                                            <th class="border-bottom fw-bolder" scope="col">ADDED BY</th>
                                            <th class="border-bottom fw-bolder" scope="col">DATE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB INVOICE NUMBER</th>
                                            <th class="border-bottom fw-bolder" scope="col">ENGINEER ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">Agent ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">HANDED OVER</th>
                                            <th class="border-bottom fw-bolder" scope="col">STATUS</th>
                                            <th class="border-bottom fw-bolder" scope="col">Contract Status</th>
                                            <th class="border-bottom fw-bolder" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="job-tables-tbody">
                                        @include('includes.mainDashboard', ['jobs' => $job])
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade show active" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab">
                                <table class="table table-flush" id="datatable1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-bottom fw-bolder" scope="col">CUSTOMER EMAIL</th>
                                            <th class="border-bottom fw-bolder" scope="col">POSTCODE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB TYPE</th>
                                            <th class="border-bottom fw-bolder" scope="col">ADDED BY</th>
                                            <th class="border-bottom fw-bolder" scope="col">DATE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB INVOICE NUMBER</th>
                                            <th class="border-bottom fw-bolder" scope="col">ENGINEER ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">Agent ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">HANDED OVER</th>
                                            <th class="border-bottom fw-bolder" scope="col">STATUS</th>
                                            <th class="border-bottom fw-bolder" scope="col">Contract Status</th>
                                            <th class="border-bottom fw-bolder" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="job-tables-tbody1">
                                        @include('includes.mainDashboard', ['jobs' => $job1])
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab">
                                <table class="table table-flush" id="datatable2">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-bottom fw-bolder" scope="col">CUSTOMER EMAIL</th>
                                            <th class="border-bottom fw-bolder" scope="col">POSTCODE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB TYPE</th>
                                            <th class="border-bottom fw-bolder" scope="col">ADDED BY</th>
                                            <th class="border-bottom fw-bolder" scope="col">DATE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB INVOICE NUMBER</th>
                                            <th class="border-bottom fw-bolder" scope="col">ENGINEER ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">Agent ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">HANDED OVER</th>
                                            <th class="border-bottom fw-bolder" scope="col">STATUS</th>
                                            <th class="border-bottom fw-bolder" scope="col">Contract Status</th>
                                            <th class="border-bottom fw-bolder" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="job-tables-tbody2">
                                        @include('includes.mainDashboard', ['jobs' => $job2])
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab">
                                <table class="table table-flush" id="datatable3">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-bottom fw-bolder" scope="col">CUSTOMER EMAIL</th>
                                            <th class="border-bottom fw-bolder" scope="col">POSTCODE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB TYPE</th>
                                            <th class="border-bottom fw-bolder" scope="col">ADDED BY</th>
                                            <th class="border-bottom fw-bolder" scope="col">DATE</th>
                                            <th class="border-bottom fw-bolder" scope="col">JOB INVOICE NUMBER</th>
                                            <th class="border-bottom fw-bolder" scope="col">ENGINEER ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">Agent ASSIGNED</th>
                                            <th class="border-bottom fw-bolder" scope="col">HANDED OVER</th>
                                            <th class="border-bottom fw-bolder" scope="col">STATUS</th>
                                            <th class="border-bottom fw-bolder" scope="col">Contract Status</th>
                                            <th class="border-bottom fw-bolder" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="job-tables-tbody3">
                                        @include('includes.mainDashboard', ['jobs' => $job3])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Tab Content -->
            </div>
        </div>

        @if('gmail_wrong')
            <div class="modal fade" id="gmailErrorModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center p-lg-4">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                <circle class="path circle" fill="none" stroke="#db3646" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                                <line class="path line" fill="none" stroke="#db3646" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" />
                                <line class="path line" fill="none" stroke="#db3646" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" X2="34.4" y2="92.2" />
                            </svg>
                            <h4 class="text-danger mt-3">Gmail is wrong!</h4>
                            <p class="mt-3">{{ session('gmail_wrong') }}</p>
                            <button type="button" class="btn btn-sm mt-3 btn-danger" data-bs-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        function executeRemove(id){
            document.getElementById("deleteForm").setAttribute("action",`{{url('jobs/${id}')}}`);
        }

        function executeAccept(id){
            document.getElementById("acceptForm").setAttribute("action",`{{url('jobs/accept/${id}')}}`);
        }

    </script>

@endsection


@section('body-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        var dataTableEl0 = d.getElementById('datatable0');
        var dataTable0 = new simpleDatatables.DataTable(dataTableEl0, {
            perPage: 25,
        });
        var dataTableEl1 = d.getElementById('datatable1');
        var dataTable1 = new simpleDatatables.DataTable(dataTableEl1, {
            perPage: 25,
        });
        var dataTableEl2 = d.getElementById('datatable2');
        var dataTable2 = new simpleDatatables.DataTable(dataTableEl2, {
            perPage: 25,
        });
        var dataTableEl3 = d.getElementById('datatable3');
        var dataTable3 = new simpleDatatables.DataTable(dataTableEl3, {
            perPage: 25,
        });
    </script>
    {{-- @if (strpos(request()->path(),'dashboard/assign') !== true) --}}
    @if (strpos(request()->path(),'dashboard/assign') === 0)
        <script>
            var pageLoadTime = new Date().toISOString(); // ISO format for easy comparison

            function getLatestData() {
                $.ajax({
                    url: "{{ url('dashboard/latest_data') }}",
                    method: "GET",
                    data: {
                        loaded_time: pageLoadTime
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Update the page with the latest data
                            var data0 = response.data0;
                            var data1 = response.data1;
                            var data2 = response.data2;
                            var data3 = response.data3;
                            updatePage(data0, data1, data2, data3);
                        }
                    }
                });
            }
            var interval = setInterval(getLatestData, 5 * 1000); // 5 seconds interval
            getLatestData();
            function updatePage(data0, data1, data2, data3) {
                dataTable0.destroy();
                dataTable1.destroy();
                dataTable2.destroy();
                dataTable3.destroy();
                $('#job-tables-tbody').html(data0);
                $('#job-tables-tbody1').html(data1);
                $('#job-tables-tbody2').html(data2);
                $('#job-tables-tbody3').html(data3);
                dataTable0 = new simpleDatatables.DataTable(dataTableEl0, {
                    perPage: 25,
                });
                dataTable1 = new simpleDatatables.DataTable(dataTableEl1, {
                    perPage: 25,
                });
                dataTable2 = new simpleDatatables.DataTable(dataTableEl2, {
                    perPage: 25,
                });
                dataTable3 = new simpleDatatables.DataTable(dataTableEl3, {
                    perPage: 25,
                });
            }
        </script>

        @if (auth()->user()->anyGmailLogin() === 0 || auth()->user()->gmail_login === 1)
            <script>
                function getGmailData() {
                    $.ajax({
                        url: "{{ route('gmail.login.check') }}",
                        method: "GET",
                        success: function (response) {
                            if (response.status === 'data_updated') {
                                getLatestData();
                            }
                        }
                    });
                }
                var interval2 = setInterval(getGmailData, 5 * 1000); // 5 seconds interval
                getGmailData();
            </script>
        @endif

        <script>
            function checkGmailError() {
                $.ajax({
                    url: "{{ route('gmail.error.check') }}",
                    method: "GET",
                    success: function (response) {
                        $("#gmail-error").removeClass("d-inline-block").addClass("d-none");
                        if (response.status === 'no gmail login') {
                            $("#gmail-error").removeClass("d-none").addClass("d-inline-block");
                        }
                    }
                });
            }
            var interval2 = setInterval(checkGmailError, 5 * 1000); // 5 seconds interval
            checkGmailError();
        </script>

        @if(session('gmail_wrong'))
            <script>
                var errorModal = new bootstrap.Modal(document.getElementById('gmailErrorModal'), {});
                errorModal.show();
            </script>
        @endif
    @endif
@endsection

