@extends("layouts.dashboard")
@section("content")


 <!-- Delete Modal Start  -->
 <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-info modal-dialog-centered" role="document">
       <div class="modal-content bg-gradient-danger">
          <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-header">

          </div>
          <div class="modal-body text-white">
             <div class="py-3 text-center">
                <span class="modal-icon">
                   <svg class="icon icon-xl text-gray-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                </span>
                <h2 class="h4 modal-title my-3">Important message!</h2>
                <p>Do you know to delete this Engineer ?</p>
             </div>
          </div>
            <form method="post" id="deleteForm" action="">
                @csrf
                <input type="hidden" name="_method" value="delete">
                <div class="modal-footer"><button type="submit" class="btn btn-sm btn-white">Yes</button></div>
            </form>
       </div>
    </div>
 </div>
 <!-- Delete Modal End  -->
 <div class="pb-4">
    <div class="py-4">

        <div class="d-flex justify-content-between w-100 flex-wrap">
           <div class="mb-3 mb-lg-0">
              <h1 class="h4">Engineers List</h1>
              <p class="mb-0">List of Engineers in our system.</p>
           </div>
           <div>
            <a href="{{url('engineers/create')}}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center mt-3">
               <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
               </svg>
               Add New Engineer
            </a>
         </div>

        </div>
     </div>

     <div class="card">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable">
              <thead class="thead-light">
                <tr>
                    <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                    <th class="border-bottom fw-bolder" scope="col">Email</th>
                    <th class="border-bottom fw-bolder" scope="col">MOBILE NO</th>
                    <th class="border-bottom fw-bolder" scope="col">Last Update</th>
                    <th class="border-bottom fw-bolder" scope="col">Postcode Cover</th>
                    <th class="border-bottom fw-bolder" scope="col">Job Type</th>
                    <th class="border-bottom fw-bolder" scope="col">Available</th>
                    <th class="border-bottom fw-bolder" scope="col">Rating</th>
                    <th class="border-bottom fw-bolder" scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($engineers as $engineer)
                    <tr>
                        <td class=" text-gray-900">{{$engineer->name}}</td>
                        <td class=" text-gray-900">{{$engineer->user->email ?? ""}}</td>
                        <td class=" text-gray-900">{{$engineer->user->phone ?? ""}}</td>
                        <td class=" text-gray-900">{{$engineer->updated_at ?? ""}}</td>
                        <td class=" text-gray-900">
                            @php
                                $codes = explode(",",$engineer->postal_codes);
                                $bgClasses = ["bg-success","bg-danger","bg-secondary","bg-tertiary","bg-warning"];
                            @endphp
                            @foreach($codes as $code)

                            <span class="badge super-badge bg-success ms-1">{{$code}}</span>
                            @endforeach
                            {{-- <span class="badge super-badge bg-success ms-1">AL</span>
                            <span class="badge super-badge bg-success ms-1">CB</span>
                            <span class="badge super-badge bg-success ms-1">CM</span> --}}
                        </td>
                        <td class=" text-gray-900">
                            @foreach($engineer->jobTypes as $type)
                            @php
                                  $randomKey  = array_rand($bgClasses);
                            @endphp
                            <span class="badge super-badge {{$type->jobtype->bgcolor}} ms-1">{{$type->jobtype->title}}</span>

                            @endforeach
                                                {{-- <span class="badge super-badge bg-info ms-1">Plumbing</span>
                                                <span class="badge super-badge bg-success ms-1">Drainage</span>
                                                <span class="badge super-badge bg-danger ms-1">Heating</span>
                                                <span class="badge super-badge bg-secondary ms-1">Gas</span>
                                                <span class="badge super-badge bg-tertiary ms-1">Unvented</span>
                                                <span class="badge super-badge bg-warning ms-1">Electricity</span> --}}
                        </td>
                        <td class=" text-gray-900">
                           @php
                                $todayAvailable = $engineer->todayAvailablity();
                                if($todayAvailable != null){

                                }
                           @endphp
                           @if($todayAvailable != null)
                                {{date("h:i A",strtotime($todayAvailable->start_time))}}
                                -
                                {{date("h:i A",strtotime($todayAvailable->end_time))}}
                           @endif
                        </td>
                        <td class=" text-gray-900">
                            {{$engineer->rating}}
                        </td>
                        <td>
                            <a href="{{url('engineers/availability/' . $engineer->id)}}" class="btn btn-outline-primary action-btn d-inline-flex align-items-center">
                                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg> Set Availablity
                            </a>
                            <a href="{{url('engineers/' . $engineer->id . '/edit')}}" class="btn btn-outline-success action-btn d-inline-flex align-items-center">
                                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg> Edit
                            </a>
                            <button onclick="executeRemove({{$engineer->id}})" href="button" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-notification">
                                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach






              </tbody>
           </table>
        </div>
     </div>
</div>
<script>
    function executeRemove(id){
       document.getElementById("deleteForm").setAttribute("action",`{{url('engineers/${id}')}}`);
    }


</script>

@endsection
