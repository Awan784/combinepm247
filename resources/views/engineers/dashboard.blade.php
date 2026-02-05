@extends("layouts.dashboard")
@section("content")

<div class="py-4 row">
    <div class="mb-4 col-12">
        <div class="row">
            <div class="mb-4 col-12">
                <div class="border-0 shadow card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col"><h2 class="mb-0 fs-5 fw-bold">Engineers Available Today</h2></div>
                            <div class="col text-end"><a href="{{url('available-today')}}" class="btn btn-sm btn-outline-gray-600">See all</a></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
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
                                    {{-- @if($available->engineer) --}}
                                        <tr>
                                            <td class="text-gray-900 ">{{$available->engineer->name}}</td>
                                            <td class="text-gray-900 ">
                                                @php
                                                    $codes = explode(",",$available->engineer->postal_codes);
                                                @endphp
                                                @foreach ($codes as $code)
                                                <span class="badge super-badge bg-success ms-1">{{$code}}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-gray-900 ">
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
                                            <td class="text-gray-900 ">
                                                @php
                                                    echo date("h:i A",strtotime($available->start_time)) . " - " . date("h:i A",strtotime($available->end_time));
                                                @endphp
                                            </td>
                                            <td class="text-gray-900 ">{{$available->engineer->rating}}</td>
        
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach


                                {{-- <tr>
                                    <td class="text-gray-900 ">Neal Martin</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">John Doe</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Waseem Dildar</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Umair Khalid</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Andy McCormick</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Neal Martin</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">John Doe</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Waseem Dildar</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Umair Khalid</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>

                                </tr>
                                <tr>
                                    <td class="text-gray-900 ">Andy McCormick</td>
                                    <td class="text-gray-900 "><span class="badge super-badge bg-success ms-1">AL</span><span class="badge super-badge bg-success ms-1">CB</span><span class="badge super-badge bg-success ms-1">CM</span></td>
                                    <td class="text-gray-900 ">
                                        <span class="badge super-badge bg-info ms-1">Plumbing</span><span class="badge super-badge bg-success ms-1">Drainage</span><span class="badge super-badge bg-danger ms-1">Heating</span><span class="badge super-badge bg-secondary ms-1">Gas</span>
                                        <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                        <span class="badge super-badge bg-warning ms-1">Electricity</span>
                                    </td>
                                    <td class="text-gray-900 ">9am-5pm</td>
                                    <td class="text-gray-900 ">1</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-12 col-xxl-6">
                <div class="border-0 shadow card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col"><h2 class="mb-0 fs-5 fw-bold">Job Type Engineers</h2></div>
                            <div class="col text-end"><a href="{{url('job-type-engineers')}}" class="btn btn-sm btn-outline-gray-600">See all</a></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
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
                                {{-- <tr>
                                    <td class="text-gray-900">Drainage Basic</td>
                                    <td class="text-gray-900">20</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Heating</td>
                                    <td class="text-gray-900">34</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Gas</td>
                                    <td class="text-gray-900">9</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Plumbing</td>
                                    <td class="text-gray-900">55</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Drainage Jetting</td>
                                    <td class="text-gray-900">12</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Unvented</td>
                                    <td class="text-gray-900">60</td>
                                </tr> --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mb-4 col-12 col-xxl-6">
                <div class="border-0 shadow card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col"><h2 class="mb-0 fs-5 fw-bold">Weekly Available Engineers </h2></div>
                            <div class="col text-end"><a href="{{url('weekly-available-engineers')}}" class="btn btn-sm btn-outline-gray-600">See all</a></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
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
                                        if($available->engineer){
                                            if($engineer["id"] == $available->engineer->id){
                                                $isFounded = $key;
                                            }
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
                                            "id" => $available->engineer ? $available->engineer->id : '',
                                            "name" => $available->engineer ? $available->engineer->name : '',
                                            "totalHours" => $tHours
                                        ];
                                    }
                                }

                                usort($engineers, function($a, $b) {
                                    return $b['totalHours'] <=> $a['totalHours'];
                                });
                                $engineers = array_splice($engineers,0,10);
                                @endphp
                                @foreach($engineers as $engineer)
                                <tr>
                                    <td class="text-gray-900">{{$engineer["name"]}}</td>
                                    <td class="text-gray-900">

                                        {{$engineer["totalHours"]}}
                                    </td>
                                </tr>
                                @endforeach
                                {{-- <tr>
                                    <td class="text-gray-900">Faizan Saeed</td>
                                    <td class="text-gray-900">40</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Neal Martin</td>
                                    <td class="text-gray-900">48</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Umair Khalid</td>
                                    <td class="text-gray-900">32</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">AB Cheema</td>
                                    <td class="text-gray-900">60</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Rizwan Razi</td>
                                    <td class="text-gray-900">20</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-900">Billa Badshah</td>
                                    <td class="text-gray-900">60</td>
                                </tr> --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
