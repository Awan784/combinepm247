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
              <h1 class="h4">Job Lists</h1>
              <p class="mb-0">List of Job in our system.</p>
           </div>
       

        </div>
     </div>

     <div class="card">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable">
              <thead class="thead-light">
                <tr>
                    <th class="border-bottom fw-bolder" scope="col">CUSTOMER EMAIL</th>
                    <th class="border-bottom fw-bolder" scope="col">POSTCODE</th>
                    <th class="border-bottom fw-bolder" scope="col">JOB TYPE</th>
                    <th class="border-bottom fw-bolder" scope="col">ADDED BY</th>
                    <th class="border-bottom fw-bolder" scope="col">DATE</th>
                    <th class="border-bottom fw-bolder" scope="col">JOB INVOICE NUMBER</th>
                    <th class="border-bottom fw-bolder" scope="col">ENGINEER ASSIGNED</th>
                    <th class="border-bottom fw-bolder" scope="col">Agent ASSIGNED</th>
                    <th class="border-bottom fw-bolder" scope="col">HANDED OVER</th>

                  
                    <th class="border-bottom fw-bolder" scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jobs as $job)
                    <tr>
                <td class=" text-gray-900">{{$job->customer_email}}</td>
                <td class=" text-gray-900">{{$job->postcode}}</td>
                <td class=" text-gray-900">{{$job->job_type}}</td>
                <td class=" text-gray-900">{{$job->added_by_user_name()}}</td>
                <td class=" text-gray-900">{{$job->date}}</td>
                <td class=" text-gray-900">{{$job->job_invoice_no}}</td>
                <td class=" text-gray-900">{{$job->engineer_user ? $job->engineer_user->name : ''}}</td>
                <td class=" text-gray-900">{{$job->agent_assigned ? $job->agent_assigned->name : ''}}</td>
                <td class=" text-gray-900">{{$job->handed_over ? $job->handed_over->name : ''}}</td>


                     
                      
                        <td>
                             <a href="{{route('investigation.job.preview.pdf' , $job->id)}}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> 
                Investigation PDF
            </a>
                          
                                  <a href="{{route('completion.job.preview.pdf' , $job->id)}}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> 
                Completion Form
            </a>
                          
                <a href="{{route('job.completion_investigation.pdf' , $job->id)}}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> 
                Job Sheet
            </a>
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
