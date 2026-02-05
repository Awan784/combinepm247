<!DOCTYPE html>
<html lang="en">

   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>Set Availablity</title>
      <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
      <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/favicon/apple-touch-icon.png')}}">
      <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon/favicon-32x32.png')}}">
      <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon/favicon-16x16.png')}}">
      <link rel="manifest" href="{{asset('assets/img/favicon/site.webmanifest')}}">
      <link rel="mask-icon" href="{{asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#ffffff">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="theme-color" content="#ffffff">
      <link type="text/css" href="{{asset('vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/notyf/notyf.min.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/fullcalendar/main.min.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/apexcharts/dist/apexcharts.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/choices.js/public/assets/styles/choices.min.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('vendor/leaflet/dist/leaflet.css')}}" rel="stylesheet">
      <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
      <style>
        .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
            width: 100% !important;
        }
        .dropdown-toggle {
            padding: 0rem !important;
        }
      </style>



   </head>
   <body>
    <!-- Delete Modal Start  -->
    <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
        <div class="modal-dialog modal-info modal-dialog-centered" role="document">
           <div class="modal-content bg-gradient-danger">
              <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="modal-header">

              </div>
              <div class="text-white modal-body">
                 <div class="py-3 text-center">
                    <span class="modal-icon">
                       <svg class="text-gray-200 icon icon-xl" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    </span>
                    <h2 class="my-3 h4 modal-title">Important message!</h2>
                    <p>Do you know to delete this User ?</p>
                 </div>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-sm btn-white">Yes</button></div>
           </div>
        </div>
     </div>
     <!-- Delete Modal End  -->
    @if(auth()->user()->user_type_id !== \App\Models\UserType::ENGINEER)
      @include("includes.navigation")
    @endif
      <main class="{{auth()->user()->user_type_id !== \App\Models\UserType::ENGINEER ? 'content' : 'container-fluid'}}">
        @include("includes.topbar")

        <div class="my-4 row">
            <br/>
            <x-greetings/>
            <form action="{{url('engineers/availability/' . $engineer->id)}}" method="post" class="p-3 pb-4 mb-4 border-0 shadow card">
                <input type="hidden" name="_method" value="put">
                @csrf
                <div class="p-0 py-3 mb-4 card-header mx-lg-4 py-lg-4 mb-md-0">
                   <h3 class="mb-0 h5">Job and Location Details</h3>
                </div>
                <div class="p-0 card-body p-md-4 pb-md-0">
                   <div class="row justify-content-center">

                     @if(auth()->user()->user_type_id === \App\Models\UserType::ENGINEER)
                        <div class="col-12 col-lg-2">
                           <div class="mb-4 form-group"><label for="home_postcode">Home Post Code *</label>
                              <input type="text" value="{{$engineer->home_postcode}}" name="home_postcode" placeholder="Home Post Code" class="form-control"  required>
                           </div>
                        </div>
                     @endif
                    <div class="col-12 col-lg-4">
                        <div class="mb-4 form-group"><label for="cartInputCompany1">Job Type *</label> <select class="selectpicker form-select" multiple name="jobtypes[]" aria-label="Default select example" data-live-search="true">
                            @php
                                $job_types = $engineer->jobTypes;
                                $type_ids = [];
                                foreach($job_types as $type)
                                {
                                    $type_ids[] = $type->job_type_id;
                                }

                            @endphp
                           @foreach($jobtypes as $type)

                           <option value="{{$type->id}}" {{in_array($type->id,$type_ids) ? 'selected' : ''}}>{{$type->title}}</option>

                           @endforeach
                         </select>
                        </div>
                     </div>
                     <div class="col-12 col-lg-4">
                        @php
                            $postal_codes = explode(",",$engineer->postal_codes);
                            $codes = ["AL","BA","BS","BR","CB","CM","CO","CT","CR","DA","EN","EC","E","GU","GL","HA",
                            "HP","IP","IG","KT","LU","ME","MK","N","NN","NW","OX","PE","RG","RH","RM","SE","SG","SL","SM","SN","SW","SS","TN","TW","UB","W","WC","WD"];

                        @endphp
                        <div class="mb-4 form-group"><label for="cartInputCompany1">Post Code *</label> <select class="selectpicker form-select" multiple name="postcodes[]" aria-label="Default select example" data-live-search="true">
                            @foreach($codes as $code)

                                <option value="{{$code}}" {{in_array($code,$postal_codes) ? 'selected' : ''}}>{{$code}}</option>

                            @endforeach
                         </select>
                        </div>
                     </div>
                     @if(auth()->user()->user_type_id !== \App\Models\UserType::ENGINEER)
                        <div class="col-12 col-lg-2">
                           <div class="mb-4 form-group"><label for="cartInputCompany1">Rating </label>
                              <input type="text" value="{{$engineer->rating}}" name="rating" placeholder="1" class="form-control"  required>
                           </div>
                        </div>
                     @endif
                     <div class="col-12 col-lg-2">
                        <span class="mb-5"></span>
                        <button class="mt-4 btn btn-gray-800 animate-up-2 search-btn" type="submit">Save</button>
                    </div>


                   </div>
                </div>
             </form>
             <div class="border-0 shadow card">
                <div class="p-0 py-3 mb-4 card-header mx-lg-4 py-lg-4 mb-md-0">
                    <h3 class="mb-0 h5">Availablity Details</h3>
                 </div>
                <div id="calendar" class="p-4"></div>
             </div>

        </div>
        <div class="modal fade" id="modal-new-event" tabindex="-1" role="dialog" aria-labelledby="modal-new-event" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
               <form id="addNewEventForm" method="post" action="{{url('engineers/add-availability')}}" class="modal-content">
                @csrf
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12 col-lg-6">
                           <div class="mb-4">
                            <input type="hidden" name="engineer_id" value="{{$engineer->id}}">
                              <label for="dateStart">Add Start Time</label>
                              <div class="input-group">
                                 <span class="input-group-text">
                                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                 </span>
                                 <select class="form-select" name="start_time" id="time" aria-label="Default select example">
                                    <option value="00:00">00:00</option>
                                    <option value="00:30">00:30</option>
                                    <option value="01:00">01:00</option>
                                    <option value="01:30">01:30</option>
                                    <option value="02:00">02:00</option>
                                    <option value="02:30">02:30</option>
                                    <option value="03:00">03:00</option>
                                    <option value="03:30">03:30</option>
                                    <option value="04:00">04:00</option>
                                    <option value="04:30">04:30</option>
                                    <option value="05:00">05:00</option>
                                    <option value="05:30">05:30</option>
                                    <option value="06:00">06:00</option>
                                    <option value="06:30">06:30</option>
                                    <option value="07:00">07:00</option>
                                    <option value="07:30">07:30</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                 </select>
                                 <input  class="form-control" name="date_start" id="dateStart" type="text" hidden>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-lg-6">
                           <div class="mb-2">
                              <label for="dateEnd">Add End Time</label>
                              <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                 </span>
                                 <select class="form-select" name="end_time" id="time" aria-label="Default select example">
                                    <option value="00:00">00:00</option>
                                    <option value="00:30">00:30</option>
                                    <option value="01:00">01:00</option>
                                    <option value="01:30">01:30</option>
                                    <option value="02:00">02:00</option>
                                    <option value="02:30">02:30</option>
                                    <option value="03:00">03:00</option>
                                    <option value="03:30">03:30</option>
                                    <option value="04:00">04:00</option>
                                    <option value="04:30">04:30</option>
                                    <option value="05:00">05:00</option>
                                    <option value="05:30">05:30</option>
                                    <option value="06:00">06:00</option>
                                    <option value="06:30">06:30</option>
                                    <option value="07:00">07:00</option>
                                    <option value="07:30">07:30</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                 </select>
                                 <input data-datepicker="" class="form-control" id="dateEnd" type="text" hidden>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                            {{-- <div class="mb-2">
                                <label for="dateEnd">Add Title</label>
                                <div class="input-group">
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                            </div> --}}
                        </div>
                        <div class="col-12">
                           <div class="mb-2">
                              <div class="input-group">
                                 <input type="checkbox" name="monthAvailability" id="monthAvailability">&nbsp;
                                 <label for="monthAvailability" class="mb-0"> Set this availability for next 30 days.</label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-gray-800" id="addNewEvent">Save</button>
                    <button type="button" class="btn btn-gray-300 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
               </form>
            </div>
         </div>
         <div class="modal fade" id="modal-edit-event" tabindex="-1" role="dialog" aria-labelledby="modal-edit-event" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
               <form id="editEventForm" method="post" action="{{url('engineers/add-availability')}}" class="modal-content">
                @csrf
                  <div class="modal-body">
                     <div class="mb-4"><input type="text" class="form-control" id="eventTitleEdit" hidden></div>
                     <div class="row">
                        <div class="col-12 col-lg-6">
                           <div class="">
                              <label for="dateStartEdit">Select start Time</label>

                              <div class="input-group">
                                <span class="input-group-text">
                                   <svg class="icon icon-xs"  fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                   </svg>
                                </span>
                                <select class="form-select stimeedit" name="start_time" id="time" aria-label="Default select example">
                                    <option value="00:00">00:00</option>
                                    <option value="00:30">00:30</option>
                                    <option value="01:00">01:00</option>
                                    <option value="01:30">01:30</option>
                                    <option value="02:00">02:00</option>
                                    <option value="02:30">02:30</option>
                                    <option value="03:00">03:00</option>
                                    <option value="03:30">03:30</option>
                                    <option value="04:00">04:00</option>
                                    <option value="04:30">04:30</option>
                                    <option value="05:00">05:00</option>
                                    <option value="05:30">05:30</option>
                                    <option value="06:00">06:00</option>
                                    <option value="06:30">06:30</option>
                                    <option value="07:00">07:00</option>
                                    <option value="07:30">07:30</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                 </select>
                                <input data-datepicker="" class="form-control" id="dateStartEdit" type="text" hidden>
                             </div>
                           </div>
                        </div>
                        <input type="hidden" name="engineer_id" value="{{$engineer->id}}">
                        <input type="hidden" name="avail_method" value="update">
                        <input type="hidden" name="update_id" id="update_id" value="">
                        <div class="col-12 col-lg-6">
                           <div class="mb-2">
                              <label for="dateEndEdit">Select end date</label>

                              <div class="input-group">
                                <span class="input-group-text">
                                   <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                   </svg>
                                </span>
                                <select class="form-select etimeedit" name="end_time" id="time" aria-label="Default select example">
                                    <option value="00:00">00:00</option>
                                    <option value="00:30">00:30</option>
                                    <option value="01:00">01:00</option>
                                    <option value="01:30">01:30</option>
                                    <option value="02:00">02:00</option>
                                    <option value="02:30">02:30</option>
                                    <option value="03:00">03:00</option>
                                    <option value="03:30">03:30</option>
                                    <option value="04:00">04:00</option>
                                    <option value="04:30">04:30</option>
                                    <option value="05:00">05:00</option>
                                    <option value="05:30">05:30</option>
                                    <option value="06:00">06:00</option>
                                    <option value="06:30">06:30</option>
                                    <option value="07:00">07:00</option>
                                    <option value="07:30">07:30</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:30">08:30</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:30">22:30</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:30">23:30</option>
                                 </select>
                                <input data-datepicker="" class="form-control" id="dateEndEdit" type="text" hidden>
                             </div>
                           </div>
                        </div>
                        <div class="col-12" hidden>
                            <div class="mb-2">
                                <label for="dateEndEdit">Title</label>
                                <input type="text" name="title" id="title_edit" class="form-control">
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-gray-800 me-2" id="editEvent">Update</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                    <button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button></div>
               </form>
            </div>
         </div>
         @php
            $events = [];
            $classes = ["bg-yellow","bg-red","bg-blue","bg-purple","bg-green","bg-orange"];
            //

            $availability = $engineer->availability;
            foreach($availability as $event)
            {
                $randomKey  = array_rand($classes);
                array_push($events,[
                    "id" => $event->id,
                    "title" => date("h:i A",strtotime($event->start_time)) . " - " . date("h:i A",strtotime($event->end_time)),
                    "start" => date("Y-m-d",strtotime($event->date_start)),
                    "end" => date("Y-m-d",strtotime($event->date_start)),
                    "className" => $classes[$randomKey]
                ]);
            }
         @endphp
         <p id="eventlist" style="display:none"><?= json_encode($events) ?></p>
         <p id="eventlistOriginal" style="display:none"><?= json_encode($availability) ?></p>
         <p id="basePath" style="display:none">{{url('/')}}</p>
      </main>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
      <script src="{{asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')}}"></script>
      <script src="{{asset('vendor/apexcharts/dist/apexcharts.min.js')}}"></script>
      <script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
      <script src="{{asset('vendor/simple-datatables/dist/umd/simple-datatables.js')}}"></script>
      <script src="{{asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js')}}"></script>
      <script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
      <script src="{{asset('vendor/fullcalendar/main.min.js')}}"></script>
      <script src="{{asset('assets/js/volt.js')}}"></script>

   </body>
   </html>
