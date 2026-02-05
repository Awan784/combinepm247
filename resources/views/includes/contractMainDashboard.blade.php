
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col"><h2 class="fs-5 fw-bold mb-0">Contracts</h2></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contracts as $contract)
                            @php
                                $timeDifference = 0;
                                if ($contract->received_time == null) {
                                    $sentTime = \Carbon\Carbon::parse($contract->sent_time);
                                    $nowTime= \Carbon\Carbon::now();
                                    $timeDifference = $sentTime->diffInMinutes($nowTime);
                                }
                            @endphp
                            <tr class="{{$timeDifference >= 10 ? 'bg-danger' : ''}}" id="contract{{$contract->id}}">
                                <td class=" text-gray-900">{{$contract->job->engineer_user->name ?? "Not Assign Yet"}}</td>
                                <td class=" text-gray-900">{{$contract->job->customer_email}}</td>
                                <td class=" text-gray-900">{{$contract->job->postcode}}</td>
                                <td class=" text-gray-900">{{$contract->job->job_invoice_no}}</td>
                                <td class=" text-gray-900">{{$contract->sent_by_user->name ?? ""}}</td>
                                <td class=" text-gray-900">
                                    <span class="badge super-badge bg-{{$contract->status == "sent" ? 'success' : 'info'}} ms-1">
                                        {{$contract->status}}
                                    </span>
                                </td>
                                <td class=" text-gray-900">{{$contract->sent_time}}</td>
                                <td class=" text-gray-900">{{$contract->received_time}}</td>
                                <td class=" text-gray-900">{{$contract->inform_time}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col"><h2 class="fs-5 fw-bold mb-0">Payments</h2></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom fw-bolder" scope="col">Engineer Name</th>
                            <th class="border-bottom fw-bolder" scope="col">Customer Email</th>
                            <th class="border-bottom fw-bolder" scope="col">Postcode</th>
                            <th class="border-bottom fw-bolder" scope="col">Job Invoice Number</th>
                            <th class="border-bottom fw-bolder" scope="col">Sent By</th>
                            <th class="border-bottom fw-bolder" scope="col">Status</th>
                            <th class="border-bottom fw-bolder" scope="col">Payment Sent</th>
                            <th class="border-bottom fw-bolder" scope="col">Payment Received</th>
                            <th class="border-bottom fw-bolder" scope="col">Engineer Informed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        @php
                            $timeDifference = 0;
                            if ($payment->received_time == null) {
                                $sentTime = \Carbon\Carbon::parse($payment->sent_time);
                                $nowTime= \Carbon\Carbon::now();
                                $timeDifference = $sentTime->diffInMinutes($nowTime);
                            }
                        @endphp
                        <tr class="{{$timeDifference >= 10 ? 'bg-danger' : ''}}" id="payment{{$payment->id}}">
                                <td class=" text-gray-900">{{$payment->job->engineer_user->name ?? 'Not Assign Yet'}}</td>
                                <td class=" text-gray-900">{{$payment->job->customer_email}}</td>
                                <td class=" text-gray-900">{{$payment->job->postcode}}</td>
                                <td class=" text-gray-900">{{$payment->job->job_invoice_no}}</td>
                                <td class=" text-gray-900">{{$payment->sent_by_user ? $payment->sent_by_user->name : ''}}</td>
                                <td class=" text-gray-900">
                                    <span class="badge super-badge bg-{{$payment->status == "sent" ? 'success' : 'info'}} ms-1">
                                        {{$payment->status}}
                                    </span>
                                </td>
                                <td class=" text-gray-900">{{$payment->sent_time}}</td>
                                <td class=" text-gray-900">{{$payment->received_time}}</td>
                                <td class=" text-gray-900">{{$payment->inform_time}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>