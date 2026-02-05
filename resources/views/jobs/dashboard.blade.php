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

<div class="row py-4">
    <div class="col-12 mb-4">
        <div class="pb-4">
            <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <p class="mb-0 alert alert-danger d-none" id="gmail-error"><span class="h4 text-danger">Gmail Error:</span> Your Gmail session expired! click <a href="{{url('mails-check')}}" class="text-decoration-underline"><b>here</b></a> to create new.</p>
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
        <div id="job-tables-container">
            @include('includes.contractMainDashboard', ['contracts' => $contracts , 'payments' => $payments])
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

@endsection

@section('body-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        var pageLoadTime = new Date().toISOString(); // ISO format for easy comparison

        function getLatestData() {
            $.ajax({
                url: "{{ url('dashboard/contract_latest_data') }}",
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
        var interval = setInterval(getLatestData, 5 * 1000); // 5 seconds interval
        getLatestData();
        function updatePage(data) {
            $('#job-tables-container').html(data);
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
@endsection
