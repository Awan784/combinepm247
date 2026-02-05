@extends("layouts.dashboard")

@section("content")

<div class="row my-4">
    <form action="" method="post" class="card border-0 shadow p-3 pb-4 mb-4">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
            @csrf
            <h3 class="h5 mb-0">Assign Agent</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row">
               <div class="col-12">
                    <div class="form-group mb-4">
                        <label for="cartInputCompany1">Agent Name *</label> 
                        <select name="agent_id" id="agent_id" class="selectpicker form-select" data-live-search="true">
                            <option selected disabled> Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
               </div>
                <div class="col-12"><button class="btn btn-gray-800 mt-3 animate-up-2" type="submit">Assign</button></div>
           </div>
        </div>
     </form>

    <style>
        .content {
            overflow: visible !important;
        }
        .bootstrap-select .dropdown-menu {
            inset: inherit !important;
            transform: none !important;
            left: 0px !important;
        }
    </style>
@endsection
