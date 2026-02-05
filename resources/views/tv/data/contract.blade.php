
                                    <!-- jobs table -->
                                    @foreach ($jobs as $key => $job)
                                        <table class="table contract-table align-items-center table-flush table-custom">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="border-bottom fw-bolder" scope="col">Job Created By</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Job Creation</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Job Invoice Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-gray-900">{{$job->created_by_user->name ?? ''}}</td>
                                                    <td class="text-gray-900">{{$job->created_at}}</td>
                                                    <td class="text-gray-900">{{$job->engineer_user->name ?? ''}}</td>
                                                    <td class="text-gray-900">{{$job->postcode}}</td>
                                                    <td class="text-gray-900">{{$job->job_invoice_no}}</td>
                                                </tr>
                                            </tbody>
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="border-bottom fw-bolder" scope="col">Contract Sent By</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Contract Status</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Contract Sent</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Contract Received</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Contract Engineer Informed</th>
                                                    <th class="border-bottom fw-bolder" scope="col"></th>
                                                </tr>
                                            </thead>
                                            @if($job->contract != null && $job->contract->sent_by_user !== null && $job->contract->status !== null )
                                                <tbody>
                                                    @php
                                                        $timeDifference = 0;
                                                        if ($job->contract->received_time == null) {
                                                            $sentTime = \Carbon\Carbon::parse($job->contract->sent_time);
                                                            $nowTime= \Carbon\Carbon::now();
                                                            $timeDifference = $sentTime->diffInMinutes($nowTime);
                                                        }
                                                    @endphp
                                                    <tr class="{{$timeDifference >= 10 ? 'bg-danger' : ''}}" id="contract{{$job->contract->id}}">

                                                        <td class="text-gray-900">{{$job->contract->sent_by_user->name ?? ''}}</td>
                                                        <td class="text-gray-900"><span class="badge super-badge bg-{{$job->contract->status == "sent" ? 'success' : 'info'}} ms-1">{{$job->contract->status}}</span></td>
                                                        <td class="text-gray-900">{{$job->contract->sent_time}}</td>
                                                        <td class="text-gray-900">{{$job->contract->received_time}}</td>
                                                        <td class="text-gray-900">{{$job->contract->inform_time}}</td>
                                                        <td class="text-gray-900"></td>
                                                    </tr>
                                                </tbody>
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="border-bottom fw-bolder" scope="col">Payment Sent By</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Payment Status</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Payment Sent</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Payment Received</th>
                                                    <th class="border-bottom fw-bolder" scope="col">Payment Engineer Informed</th>
                                                    <th class="border-bottom fw-bolder" scope="col"></th>
                                                </tr>
                                            </thead>
                                            @if($job->payment != null && $job->payment->status !== null)
                                                <tbody>
                                                @php
                                                    $timeDifference = 0;
                                                    if ($job->payment->received_time == null) {
                                                        $sentTime = \Carbon\Carbon::parse($job->payment->sent_time);
                                                        $nowTime= \Carbon\Carbon::now();
                                                        $timeDifference = $sentTime->diffInMinutes($nowTime);
                                                    }
                                                @endphp
                                                <tr class="{{$timeDifference >= 10 ? 'bg-danger' : ''}}" id="payment{{$job->payment->id}}">
                                                    <td class="text-gray-900">{{$job->payment->sent_by_user ? $job->payment->sent_by_user->name : ''}}</td>
                                                    <td class="text-gray-900"><span class="badge super-badge bg-{{$job->payment->status == "sent" ? 'success' : 'info'}} ms-1">{{$job->payment->status}}</span></td>
                                                    <td class="text-gray-900">{{$job->payment->sent_time}}</td>
                                                    <td class="text-gray-900">{{$job->payment->received_time}}</td>
                                                    <td class="text-gray-900">{{$job->payment->inform_time}}</td>
                                                    <td class="text-gray-900"></td>
                                                </tr>
                                            </tbody>
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                        @if ($key !== count($jobs) - 1)
                                            <hr class="p-1 contract-hr-padding">                                            
                                        @endif
                                    @endforeach