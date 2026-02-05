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
                <p>Do you know to delete this Job ?</p>
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
               <h1 class="h4">Send Contracts List</h1>
               <p class="mb-0">List of contracts in our system.</p>
           </div>
        </div>
     </div>

     <div class="card">
        <div class="table-responsive py-4">
           <table class="table table-flush" id="datatable0">
              <thead class="thead-light">
                  <tr>
                     <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                     <th class="border-bottom fw-bolder" scope="col">Customer Email</th>
                     <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                     <th class="border-bottom fw-bolder" scope="col">Job Invoice Number</th>
                     <th class="border-bottom fw-bolder" scope="col">Sent By</th>
                     <th class="border-bottom fw-bolder" scope="col">Status</th>
                     <th class="border-bottom fw-bolder" scope="col">Contract Sent</th>
                     <th class="border-bottom fw-bolder" scope="col">Contract Received</th>
                     <th class="border-bottom fw-bolder" scope="col">Engineer Informed</th>
                     <th class="border-bottom fw-bolder" scope="col">Job Status</th>
                     <th class="border-bottom fw-bolder" scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($jobs as $job)
                  <tr>
                     <td class=" text-gray-900">{{$job->engineer_user->name ?? "Not Assign Yet"}}</td>
                     <td class=" text-gray-900">{{$job->customer_email}}</td>
                     <td class=" text-gray-900">{{$job->postcode}}</td>
                     <td class=" text-gray-900">{{$job->job_invoice_no}}</td>
                     @if ($job->contract !== null)
                        <td class=" text-gray-900">{{$job->contract->sent_by_user->name ?? ""}}</td>
                        <td class=" text-gray-900">
                           <span class="badge super-badge bg-{{$job->contract->status == "sent" ? 'success' : 'info'}} ms-1">{{$job->contract->status}}</span>
                        </td>
                        <td class=" text-gray-900">{{$job->contract->sent_time}}</td>
                        <td class=" text-gray-900">{{$job->contract->received_time}}</td>
                        <td class=" text-gray-900">{{$job->contract->inform_time}}</td>
                        <td class=" text-gray-900">
                            <span class="badge super-badge bg-{{$job->contract_status === '1' ? 'success' : 'danger'}} ms-1">{{$job->contract_status === '1' ? 'Accepted' : 'Rejected'}}</span>
                            <a href="{{ route('job.reject', $job->id)}}" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" >
                                Reject
                            </a>
                        </td>
                        <td>
                           @if ($job->contract->status == "sent")
                              <a href="{{url('contracts/received').'/'.$job->contract->id}}" class="btn btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                                 <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                 </svg> Received Contract
                              </a>
                           @endif
                        </td>
                     @else
                        <td colspan="5"></td>
                        <td class=" text-gray-900">
                            <span class="badge super-badge bg-{{$job->contract_status === '1' ? 'success' : 'danger'}} ms-1">{{$job->contract_status === '1' ? 'Accepted' : 'Rejected'}}</span>
                            <a href="{{ route('job.reject', $job->id)}}" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" >
                                Reject
                            </a>
                        </td>
                        <td>
                           <a href="{{url('contracts/sent').'/'.$job->id}}" class="btn btn-outline-success action-btn d-inline-flex align-items-center">
                              <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path><path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path>
                              </svg> Send Contract
                           </a>
                        </td>
                     @endif
                  </tr>
                @endforeach






              </tbody>
           </table>
        </div>
     </div>
</div>
<script>
    function executeRemove(id){
       document.getElementById("deleteForm").setAttribute("action",`{{url('jobs/${id}')}}`);
    }
</script>

@endsection

@section('body-scripts')

<script>
    var dataTableEl0 = d.getElementById('datatable0');
    var dataTable0 = new simpleDatatables.DataTable(dataTableEl0, {
        perPage: 25,
    });
</script>

@endsection
