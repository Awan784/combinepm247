@extends("layouts.dashboard")
@section("content")

<div class="row my-4">
    <form action="{{url('jobs')}}/{{$job->id}}" method="post" class="card border-0 shadow p-3 pb-4 mb-4">
        <input type="hidden" name="_method" value="put">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
            @csrf
           <h3 class="h5 mb-0">Job Details</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row">
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4"><label for="cartInputEmail1">Customer Email *</label> <input type="email" name="customer_email" class="form-control" placeholder="example@company.com" value="{{$job->customer_email}}" required></div>
            </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4"><label for="cartInputEmail1">Postcode *</label> <input name="postcode" type="text" class="form-control" placeholder="NB ORE"  value="{{$job->postcode}}" required></div>
            </div>
                     <div class="col-12 col-lg-6">
          <div class="form-group mb-4">
              <label for="cartInputCompany1">Job Type *</label>
              <select name="job_type" class="form-select" id="country" aria-label="Default select example">
                  <option value="" selected="selected">Select Job Type</option>
                  @foreach($jobtypes as $type)
                      <option value="{{ $type->title }}" 
                          {{ (isset($job) && $job->job_type == $type->title) ? 'selected' : '' }}>
                          {{ $type->title }}
                      </option>
                  @endforeach
              </select>
          </div>
      </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4"><label for="cartInputEmail1">Job Invoice No</label> <input name="job_invoice_no" type="number" class="form-control" placeholder="89701" value="{{$job->job_invoice_no}}"></div>
            </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4">
                  <label for="cartInputEmail1">Status *</label>
                  <select name="status" type="number" class="form-control" placeholder="Status" required>
                     <option value="Active" {{$job->status == "Active" ? 'selected' : ''}}>Active</option>
                     <option value="Completed" {{$job->status == "Completed" ? 'selected' : ''}}>Completed</option>
                  </select>
               </div>
            </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4">
                   <label for="cartInputCompany1">Engineer Name</label>
                   <select name="engineer_id" id="engineer_id" class="selectpicker form-select" data-live-search="true">
                        <option selected disabled> Select Engineer</option>
                        @foreach($engineers as $engineer)
                           <option value="{{ $engineer->id }}" {{$job->engineer_id == $engineer->id ? 'selected' : ''}}>{{ $engineer->name }}</option>
                        @endforeach
                   </select>
               </div>
            </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4">
                   <label for="cartInputCompany1">Assign Agent</label>
                   <select name="agent_id" id="agent_id" class="selectpicker form-select" data-live-search="true">
                       <option selected disabled> Select Agent</option>
                       @foreach($agents as $agent)
                           <option value="{{ $agent->id }}" {{$job->agent_id == $agent->id ? 'selected' : ''}}>{{ $agent->name }}</option>
                       @endforeach
                   </select>
               </div>
            </div>
            <div class="col-12 col-lg-6">
               <div class="form-group mb-4">
                  <label for="cartInputDate">Date *</label>
                  <input type="date" name="date" class="form-control" placeholder="" value="{{$job->date}}" required>
               </div>
            </div>
            <div class="col-12"><button class="btn btn-gray-800 mt-3 animate-up-2" type="submit">Edit Job</button></div>
           </div>
        </div>
     </form>


    <style>
        .content {
            overflow: visible !important;
        }
        .bootstrap-select .dropdown-menu {
            inset: inherit !important;
            transform: none !important;
            left: 0px !important;
        }
    </style>
@endsection
