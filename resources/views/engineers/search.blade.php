@extends("layouts.dashboard")
@section("content")

<div class="row my-4">
    <form action="" method="get" class="card border-0 shadow p-3 pb-4 mb-4">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
           <h3 class="h5 mb-0">Search Engineer</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="form-group mb-4"><label for="cartInputCompany1">Job Type *</label>
                    <select name="job_type_id" class="form-select" id="country" aria-label="Default select example">
                    <option value="" selected="selected">Select Job Type</option>
                    @foreach($jobtypes as $type)
                        <option value="{{$type->id}}" {{(isset($r_data['job_type_id']) && $r_data['job_type_id'] == $type->id ? 'selected' : '')}}>{{$type->title}}</option>
                    @endforeach
                 </select>
                </div>
             </div>
             <div class="col-12 col-lg-5">
                <div class="form-group mb-4"><label for="cartInputCompany1">Post Code *</label>
                    <input type="text" name="post_codes" class="form-control" value="{{(isset($r_data['post_codes']) ? $r_data['post_codes'] : '')}}">
                    {{-- <select name="post_codes" class="form-select" id="country" aria-label="Default select example">
                    <option selected="selected">Select Post Code</option>
                    @php

                    $codes = ["AL","BA","BS","BR","CB","CM","CO","CT","CR","DA","EN","EC","E","GU","GL","HA",
                    "HP","IP","IG","KT","LU","ME","MK","N","NN","NW","OX","PE","RG","RH","RM","SE","SG","SL","SM","SN","SW","SS","TN","TW","UB","W","WC","WD"];

                    @endphp
                    @foreach($codes as $code)
                    <option value="{{$code}}" {{(isset($r_data['post_codes']) && $r_data['post_codes'] == $code ? 'selected' : '')}}>{{$code}}</option>
                    @endforeach

                 </select> --}}
                </div>
             </div>
             {{-- <div class="col-12 col-lg-2">
                <div class="form-group mb-4"><label for="cartInputCompany1">Rating </label>
                    <input name="rating" value="{{isset($r_data['rating']) ? $r_data['rating'] : ''}}" type="text" placeholder="1" class="form-control">
                </div>
             </div> --}}
             <div class="col-12 col-lg-2">
                <span class="mb-5"></span>
                <button class="btn btn-gray-800 mt-4 animate-up-2 search-btn" type="submit">Search</button>
            </div>


           </div>
        </div>
     </form>
     @if(request()->has("job_type_id"))
     <div class="card">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable">
              <thead class="thead-light">
                <tr>
                    <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                    <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                    <th class="border-bottom fw-bolder" scope="col">Job Type</th>
                    <th class="border-bottom fw-bolder" scope="col">Distance</th>
                    <th class="border-bottom fw-bolder" scope="col">From</th>
                    <th class="border-bottom fw-bolder" scope="col">Live</th>
                    <th class="border-bottom fw-bolder" scope="col">Available</th>
                    <th class="border-bottom fw-bolder" scope="col">Rating</th>
                </tr>
              </thead>
              <tbody>
                @foreach($engineers as $engineer)
                <tr>
                    <td class=" text-gray-900">{{$engineer->name}}</td>
                    @php
                                $codes = explode(",",$engineer->postal_codes);
                                $bgClasses = ["bg-success","bg-danger","bg-secondary","bg-tertiary","bg-warning"];
                    @endphp
                    <td class=" text-gray-900">


                            <span class="badge super-badge bg-success ms-1">{{$postcode}}</span>

                    </td>
                    <td class=" text-gray-900">

                        <span class="badge super-badge {{$jobtype->bgcolor}} ms-1">{{$jobtype->title}}</span>

                    </td>
                    <td class=" text-gray-900">{{$engineer->distance == "error" ? "" : ($engineer->distance . " miles")}}</td>
                      <td class=" text-gray-900">{{$engineer->distance_type}}</td> 
                    <td class=" text-gray-900">{{$engineer->updated_at}}</td>  
                  <td class=" text-gray-900">
                        @php
                            $available = $engineer->todayAvailablity();
                        @endphp
                        @if($available)
                        @php
                            echo date("h:i A",strtotime($available->start_time)) . " - " . date("h:i A",strtotime($available->end_time));
                        @endphp

                        @endif
                    </td>
                    <td class=" text-gray-900">{{$engineer->rating}}</td>

                </tr>
                @endforeach
                {{-- <tr>
                    <td class=" text-gray-900">Faizan Saeed</td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-success ms-1">AL</span></td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-info ms-1">Plumbing</span></td>
                    <td class=" text-gray-900">9am-5pm</td>
                    <td class=" text-gray-900">1</td>

                </tr>
                <tr>
                    <td class=" text-gray-900">Umair Khalid</td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-success ms-1">AL</span></td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-info ms-1">Plumbing</span></td>
                    <td class=" text-gray-900">9am-5pm</td>
                    <td class=" text-gray-900">2</td>

                </tr>
                <tr>
                    <td class=" text-gray-900">AB Cheema</td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-success ms-1">AL</span></td>
                    <td class=" text-gray-900"><span class="badge super-badge bg-info ms-1">Plumbing</span></td>
                    <td class=" text-gray-900">9am-5pm</td>
                    <td class=" text-gray-900">2</td>

                </tr> --}}


              </tbody>
           </table>
        </div>
     </div>
     @endif
</div>


@endsection
