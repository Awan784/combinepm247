@extends("layouts.dashboard")
@section("content")

<div class="pb-4">
    <div class="py-4">

        <div class="d-flex justify-content-between w-100 flex-wrap">
           <div class="mb-3 mb-lg-0">
              <h1 class="h4">Engineers Available Today</h1>
              <p class="mb-0">List of Engineers which are available today.</p>
           </div>
        </div>
     </div>

     <div class="card py">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable">
              <thead class="thead-light">
                <tr>
                    <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                    <th class="border-bottom fw-bolder" scope="col">Postcode Cover</th>
                    <th class="border-bottom fw-bolder" scope="col">Job Type</th>
                    <th class="border-bottom fw-bolder" scope="col">Available</th>
                    <th class="border-bottom fw-bolder" scope="col">Rating</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($availabilities as $available)
                <tr>
                    <td class=" text-gray-900">{{$available->engineer->name}}</td>
                    <td class=" text-gray-900">
                        @php
                        $codes = explode(",",$available->engineer->postal_codes);
                    @endphp
                    @foreach ($codes as $code)
                    <span class="badge super-badge bg-success ms-1">{{$code}}</span>
                    @endforeach
                    </td>
                    <td class=" text-gray-900">
                        @php
                        $bgClasses = ["bg-success","bg-danger","bg-secondary","bg-tertiary","bg-warning"];

                   @endphp
                   @foreach($available->engineer->jobTypes as $type)
                   @php
                         $randomKey  = array_rand($bgClasses);
                   @endphp
                   <span class="badge super-badge {{$type->jobtype->bgcolor}} ms-1">{{$type->jobtype->title}}</span>

                   @endforeach
                    </td>
                    <td class=" text-gray-900">
                        @php
                            echo date("h:i A",strtotime($available->start_time)) . " - " . date("h:i A",strtotime($available->end_time));
                        @endphp
                    </td>
                    <td class=" text-gray-900">{{$available->engineer->rating}}</td>

                </tr>
                @endforeach
            </tbody>
           </table>
        </div>
     </div>
</div>



@endsection
