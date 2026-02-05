@extends("layouts.dashboard")
@section("content")

<div class="row my-4">
    <form action="{{url('jobs')}}" method="post" class="card border-0 shadow p-3 pb-4 mb-4">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
            @csrf
           <h3 class="h5 mb-0">Job Details</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row">
               <div class="col-12 col-lg-6">
                  <div class="form-group mb-4"><label for="cartInputEmail1">Customer Email *</label> <input type="email" name="customer_email" class="form-control" placeholder=""  required></div>
               </div>
               <div class="col-12 col-lg-6">
                  <div class="form-group mb-4"><label for="cartInputEmail1">Postcode *</label> <input name="postcode" type="text" class="form-control" placeholder=""   required></div>
               </div>
               <div class="col-12 col-lg-6">
                  <div class="form-group mb-4"><label for="cartInputEmail1">Job Invoice No *</label> <input name="job_invoice_no" type="number" class="form-control" placeholder=""></div>
               </div>
                  <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputCompany1">Job Type *</label>
                    <select name="job_type" class="form-select" id="country" aria-label="Default select example">
                    <option value="" selected="selected">Select Job Type</option>
                    @foreach($jobtypes as $type)
                        <option value="{{$type->title}}" {{(isset($r_data['job_type']) && $r_data['job_type'] == $type->id ? 'selected' : '')}}>{{$type->title}}</option>
                    @endforeach
                 </select>
                </div>
             </div>
               <div class="col-12 col-lg-6">
                  <div class="form-group mb-4">
                     <label for="cartInputEmail1">Status *</label>
                     <select name="status" type="number" class="form-control" placeholder="Status" required>
                        <option value="Active">Active</option>
                        <option value="Completed">Completed</option>
                     </select>
                  </div>
               </div>
             
         

             <div class="col-12"><button class="btn btn-gray-800 mt-3 animate-up-2" type="submit">Add Job</button></div>
           </div>
        </div>
     </form>


@endsection
