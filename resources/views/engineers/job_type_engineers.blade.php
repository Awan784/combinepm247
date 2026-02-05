@extends("layouts.dashboard")
@section("content")

<div class="pb-4">
    <div class="py-4">

        <div class="d-flex justify-content-between w-100 flex-wrap">
           <div class="mb-3 mb-lg-0">
              <h1 class="h4">Job Type Engineers</h1>
              <p class="mb-0">List of Engineers which are available today by job type.</p>
           </div>
        </div>
     </div>

     <div class="card py">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable">
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



@endsection
