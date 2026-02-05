<!-- jobs table -->
@foreach ($jobs as $key => $job)
    <tr>
        <td class=" text-gray-900">{{$job->customer_email}}</td>
        <td class=" text-gray-900">{{$job->postcode}}</td>
        <td class=" text-gray-900">{{$job->added_by_user_name()}}</td>
        <td class=" text-gray-900">{{$job->date}}</td>
        <td class=" text-gray-900">{{$job->engineer_user ? $job->engineer_user->name : ''}}</td>
        <td class=" text-gray-900">{{$job->agent_assigned ? $job->agent_assigned->name : ''}}</td>
        <td class=" text-gray-900">{{$job->handed_over ? $job->handed_over->name : ''}}</td>
        <td class=" text-gray-900"><span class="badge super-badge bg-info ms-1">{{$job->status}}</span></td>
    </tr>
@endforeach