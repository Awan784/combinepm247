
@extends("layouts.dashboard")
@section("content")

<div class="row my-4">
    <form action="" method="get" class="card border-0 shadow p-3 pb-4 mb-4">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
           <h3 class="h5 mb-0">Search Job Engineer</h3>
        </div>
      
     </form>

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
                 
                    <td class=" text-gray-900">


                            
                        <span class="badge super-badge bg-success ms-1">{{ substr($postcode, 0, 2) }}</span>


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
    
</div>


@endsection
