
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
        <td class=" text-gray-900"><span class="badge super-badge bg-info ms-1">{{$job->status}}</span></td>
        <td class=" text-gray-900">
            @if ($job->contract_status != '0')
                <span class="badge super-badge bg-{{$job->contract_status === '1' ? 'success' : 'danger'}} ms-1">{{$job->contract_status === '1' ? 'Accepted' : 'Rejected'}}</span>
                @if($job->contract_status === '1')
                    <a href="{{ route('job.reject', $job->id)}}" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" >
                        Reject
                    </a>
                @else
                    <button onclick="executeAccept({{$job->id}}, {{$job->job_invoice_no}})" href="button" class="btn btn btn-outline-success action-btn d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-accept">Accept</button>
                @endif
            @else
                <button onclick="executeAccept({{$job->id}}, {{$job->job_invoice_no}})" href="button" class="btn btn btn-outline-success action-btn d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-accept">Accept</button>
                <a href="{{ route('job.reject', $job->id)}}" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" >
                    Reject
                </a>
            @endif
        </td>
        <td>
            @if ($job->engineer_id == null)
                <a href="{{route('job.assign_engineer', $job->id)}}" class="btn btn btn-outline-tertiary action-btn d-inline-flex align-items-center" >
                    <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                    </svg> Assign Engineer
                </a>
            @endif
            @if ($job->agent_id == null)
                <a href="{{route('job.assign_agent', $job->id)}}" class="btn btn btn-outline-tertiary action-btn d-inline-flex align-items-center" >
                    <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                    </svg> Assign Agent
                </a>
            @endif
          
                   <a href="{{ route('job.assign_agent_search', $job->id) }}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
              <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
              </svg> 
              Search
          </a>
          
            <a href="{{route('job.investigation' , $job->id)}}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> 
                Investigation Form
            </a>
          
                      <a href="{{route('job.completion' , $job->id)}}" class="btn btn-outline-tertiary action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> 
                Completion Form
            </a>
          
            <a href="{{route('job.assign_hand_over', $job->id)}}" class="btn btn btn-outline-tertiary action-btn d-inline-flex align-items-center" >
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg> Hand over
            </a>
            <a href="{{url('jobs/' . $job->id . '/edit')}}" class="btn btn-outline-success action-btn d-inline-flex align-items-center">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg> Edit
            </a>
            <button onclick="executeRemove({{$job->id}})" href="button" class="btn btn btn-outline-danger action-btn d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-notification">
                <svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg> Delete
            </button>
        </td>
    </tr>
@endforeach
