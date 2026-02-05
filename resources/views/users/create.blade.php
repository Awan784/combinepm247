@extends("layouts.dashboard")
@section("content")
 <!-- Delete Modal Start  -->
 <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <form method="post" id="deleteform" action="">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
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
                <p>Do you know to delete this User ?</p>
             </div>
          </div>
          <div class="modal-footer"><button type="submit" class="btn btn-sm btn-white">Yes</button></div>
       </div>
    </div>
    </form>
 </div>
 <!-- Delete Modal End  -->

 <div class="row my-4">
    <form action="{{url('users')}}" method="post" class="card border-0 shadow p-3 pb-4 mb-4">
        @csrf
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
           <h3 class="h5 mb-0">User Personal details</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputCompany1">User Name *</label>
                     <input type="text" placeholder="Name" name="username" class="form-control" value="{{ old('username') }}" required></div>
             </div>
              <div class="col-12 col-lg-6">
                 <div class="form-group mb-4"><label for="cartInputEmail1">Email address *</label>
                     <input type="email" class="form-control" name="email" placeholder="example@company.com" value="{{ old('email') }}" required>
                     {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                  </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Password *</label>
                    <input type="password" class="form-control" name="password" placeholder="********"   required></div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Confirm Password *</label>
                    <input type="password" class="form-control" name="password2" placeholder="********"   required></div>
             </div>


           </div>
        </div>
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
            <h3 class="h5 mb-0">User Access Role</h3>
         </div>
        <div class="card-body p-0 p-md-4">
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-bottom fw-bolder" scope="row">Module</th>
                                <th class="border-bottom fw-bolder" scope="col">Read</th>
                                <th class="border-bottom fw-bolder" scope="col">Write</th>
                                <th class="border-bottom fw-bolder" scope="col">Full</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                                <tr>
                                    <td class="text-gray-900" scope="row">{{$module->title}}</td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="1" name="{{$module->id . 'permission[]'}}" 
                                        {{ in_array(1, old($module->id . 'permission', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="2" name="{{$module->id . 'permission[]'}}" 
                                        {{ in_array(2, old($module->id . 'permission', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="3" name="{{$module->id . 'permission[]'}}" 
                                        {{ in_array(3, old($module->id . 'permission', [])) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>


               <div class="col-12"><button class="btn btn-gray-800 mt-5 animate-up-2" type="submit">Add User</button></div>
            </div>
         </div>
     </form>
</div>



@endsection
