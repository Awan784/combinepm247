@extends("layouts.dashboard")
@section("content")

<div class="pb-4">
    <div class="py-4">

        <div class="flex-wrap d-flex justify-content-between w-100">
           <div class="mb-3 mb-lg-0">
              <h1 class="h4">Job Type Engineers</h1>
              <p class="mb-0">List of Engineers which are available today by job type.</p>
           </div>
        </div>
     </div>

     <div class="card py">
        <div class="py-4 table-responsive">
           <table class="table table-flush" id="datatable">
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
                                        if($engineer["id"] == $available->engineer->id){
                                            $isFounded = $key;
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
                                        $tMinutes = $endTime->diffInMinutes($startTime);
                                        $tHours = $tMinutes / 60;
                                        $engineers[] = [
                                            "id" => $available->engineer->id,
                                            "name" => $available->engineer->name,
                                            "totalHours" => $tHours
                                        ];
                                    }
                                }
                                usort($engineers, function($a, $b) {
                                    return $b['totalHours'] <=> $a['totalHours'];
                                });
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


@endsection
