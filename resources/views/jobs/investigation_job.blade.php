@extends("layouts.dashboard")

@section("content")
<div class="row my-4">
    <form action="{{route('job.investigation_store')}}" method="POST" class="card border-0 shadow p-3 pb-4 mb-4" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="engineer_id" value="{{$job->engineer_id ?? ''}}">
        <input type="hidden" name="job_id" value="{{$job->id ?? ''}}">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
           <h3 class="h5 mb-0">Investigation Form</h3>
        </div>
        <div class="card-body p-0 p-md-4 pb-md-0">
           <div class="row">
             
            <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Engineer Name *</label> 
                    <input type="text" name="engineer_name" value="{{$engineers->name ?? ''}}" class="form-control" placeholder="Engineer Name"   required></div>
             </div>
              <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Property Postcode *</label> 
                    <input type="text" name="postcode" value="{{$job->postcode ?? ''}}" class="form-control" placeholder="Postcode"   required >
                </div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Start Time *</label> 
                    <input type="text" name="start_time" class="form-control" placeholder="Start Time" value="{{$investigation->start_time ?? ''}}"   required disabled></div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">End Time *</label> 
                    <input type="text" name="end_time" class="form-control" placeholder="End Time" value="{{$investigation->end_time ?? ''}}"   required disabled></div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4">
                    <label for="cartInputEmail1">Type of Job *</label>
                        <input type="text" name="job_type" value="{{$job->job_type ?? ''}}" class="form-control" placeholder="Job Type"   required></div>
<!--                     <select class="form-select" id="country" name="job_type" aria-label="Default select example" required>
                        <option value="Plumbing" {{ $job->job_type == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                        <option value="Heating" {{ $job->job_type == 'Heating' ? 'selected' : '' }}>Heating</option>
                        <option value="Drainage" {{ $job->job_type == 'Drainage' ? 'selected' : '' }}>Drainage</option>
                    </select> -->
                </div>
                
             </div>
     
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Estimate time needed to rectify  *</label>
                    <select class="form-select" name="estimate_time" id="country" aria-label="Default select example" required>
                      
                        <option value="0min – 30 min " {{ $investigation?->estimate_time == '0min – 30 min' ? 'selected' : '' }}>0min – 30 min</option>
                        <option value="30min – 1hr" {{ $investigation?->estimate_time == '30 min – 1hr' ? 'selected' : '' }}>30min – 1hr</option>
                        <option value="1hr – 1.5hr" {{ $investigation?->estimate_time == '1hr – 1.5hr' ? 'selected' : '' }}>1hr – 1.5hr</option>
                        <option value="1.5hr – 2hr" {{ $investigation?->estimate_time == '1.5hr – 2hr' ? 'selected' : '' }}>1.5hr – 2hr</option>
                        <option value="2hr – 2.5hr" {{ $investigation?->estimate_time == '2hr – 2.5hr' ? 'selected' : '' }}>2hr – 2.5hr</option>
                        <option value="2.5hr – 3hr" {{ $investigation?->estimate_time == '2.5hr – 3hr' ? 'selected' : '' }}>2.5hr – 3hr</option>
                        <option value="3hr – 3.5hr" {{ $investigation?->estimate_time == '3hr – 3.5hr' ? 'selected' : '' }}>3hr – 3.5hr</option>
                        <option value="3.5hr – 4hr" {{ $investigation?->estimate_time == '3.5hr – 4hr' ? 'selected' : '' }}>3.5hr – 4hr</option>
                        <option value="4hr – 4.5hr" {{ $investigation?->estimate_time == '4hr – 4.5hr' ? 'selected' : '' }}>4hr – 4.5hr</option>
                        <option value="4.5hr – 5hr" {{ $investigation?->estimate_time == '4.5hr – 5hr' ? 'selected' : '' }}>4.5hr – 5hr</option>
                        <option value="5hr – 5.5hr" {{ $investigation?->estimate_time == '5hr – 5.5hr' ? 'selected' : '' }}>5hr – 5.5hr</option>
                        <option value="5.5hr – 6hr" {{ $investigation?->estimate_time == '5.5hr – 6hr' ? 'selected' : '' }}>5.5hr – 6hr</option>
                        <option value="6hr – 6.5hr" {{ $investigation?->estimate_time == '6hr – 6.5hr' ? 'selected' : '' }}>6hr – 6.5hr</option>
                        <option value="6.5hr – 7hr" {{ $investigation?->estimate_time == '6.5hr – 7hr' ? 'selected' : '' }}>6.5hr – 7hr</option>
                        <option value="7hr – 7.5hr" {{ $investigation?->estimate_time == '7hr – 7.5hr' ? 'selected' : '' }}>7hr – 7.5hr</option>
                        <option value="7.5hr – 8hr" {{ $investigation?->estimate_time == '7.5hr – 8hr' ? 'selected' : '' }}>7.5hr – 8hr</option>
                        <option value="8hr – 8.5hr" {{ $investigation?->estimate_time == '8hr – 8.5hr' ? 'selected' : '' }}>8hr – 8.5hr</option>
                        <option value="8.5hr – 9hr" {{ $investigation?->estimate_time == '8.5hr – 9hr' ? 'selected' : '' }}>8.5hr – 9hr</option>
                        <option value="9hr – 9.5hr" {{ $investigation?->estimate_time == '9hr – 9.5hr' ? 'selected' : '' }}>9hr – 9.5hr</option>
                        <option value="9.5hr – 10hr" {{ $investigation?->estimate_time == '9.5hr – 10hr' ? 'selected' : '' }}>9.5hr – 10hr</option>
                        <option value="10hr – 10.5hr" {{ $investigation?->estimate_time == '10hr – 10.5hr' ? 'selected' : '' }}>10hr – 10.5hr</option>
                        <option value="10.5hr – 11hr" {{ $investigation?->estimate_time == '10.5hr – 11hr' ? 'selected' : '' }}>10.5hr – 11hr</option>
                        <option value="11hr – 11.5hr" {{ $investigation?->estimate_time == '11hr – 11.5hr' ? 'selected' : '' }}>11hr – 11.5hr</option>
                        <option value="11.5hr – 12hr" {{ $investigation?->estimate_time == '11.5hr – 12hr' ? 'selected' : '' }}>11.5hr – 12hr</option>
                        <option value="12hr – 12.5hr" {{ $investigation?->estimate_time == '12hr – 12.5hr' ? 'selected' : '' }}>12hr – 12.5hr</option>
                        <option value="12.5hr – 13hr" {{ $investigation?->estimate_time == '12.5hr – 13hr' ? 'selected' : '' }}>12.5hr – 13hr</option>
                        <option value="13hr – 13.5hr" {{ $investigation?->estimate_time == '13hr – 13.5hr' ? 'selected' : '' }}>13hr – 13.5hr</option>
                        <option value="13.5hr – 14hr" {{ $investigation?->estimate_time == '13.5hr – 14hr' ? 'selected' : '' }}>13.5hr – 14hr</option>
                        <option value="14hr – 14.5hr" {{ $investigation?->estimate_time == '14hr – 14.5hr' ? 'selected' : '' }}>14hr – 14.5hr</option>
                        <option value="14.5hr – 15hr" {{ $investigation?->estimate_time == '14.5hr – 15hr' ? 'selected' : '' }}>14.5hr – 15hr</option>
                        <option value="15hr – 15.5hr" {{ $investigation?->estimate_time == '15hr – 15.5hr' ? 'selected' : '' }}>15hr – 15.5hr</option>
                        <option value="15.5hr – 16hr" {{ $investigation?->estimate_time == '15.5hr – 16hr' ? 'selected' : '' }}>15.5hr – 16hr</option>
                        <option value="16hr – 16.5hr" {{ $investigation?->estimate_time == '16hr – 16.5hr' ? 'selected' : '' }}>16hr – 16.5hr</option>
                        <option value="16.5hr – 17hr" {{ $investigation?->estimate_time == '16.5hr – 17hr' ? 'selected' : '' }}>16.5hr – 17hr</option>
                        <option value="17hr – 17.5hr" {{ $investigation?->estimate_time == '17hr – 17.5hr' ? 'selected' : '' }}>17hr – 17.5hr</option>
                        <option value="17.5hr – 18hr" {{ $investigation?->estimate_time == '17.5hr – 18hr' ? 'selected' : '' }}>17.5hr – 18hr</option>
                        <option value="18hr – 18.5hr" {{ $investigation?->estimate_time == '18hr – 18.5hr' ? 'selected' : '' }}>18hr – 18.5hr</option>
                        <option value="18.5hr – 19hr" {{ $investigation?->estimate_time == '18.5hr – 19hr' ? 'selected' : '' }}>18.5hr – 19hr</option>
                        <option value="19hr – 19.5hr" {{ $investigation?->estimate_time == '19hr – 19.5hr' ? 'selected' : '' }}>19hr – 19.5hr</option>
                        <option value="19.5hr – 20hr" {{ $investigation?->estimate_time == '19.5hr – 20hr' ? 'selected' : '' }}>19.5hr – 20hr</option>
                        <option value="20hr – 20.5hr" {{ $investigation?->estimate_time == '20hr – 20.5hr' ? 'selected' : '' }}>20hr – 20.5hr</option>
                        <option value="20.5hr – 21hr" {{ $investigation?->estimate_time == '20.5hr – 21hr' ? 'selected' : '' }}>20.5hr – 21hr</option>
                        <option value="21hr – 21.5hr" {{ $investigation?->estimate_time == '21hr – 21.5hr' ? 'selected' : '' }}>21hr – 21.5hr</option>
                        <option value="21.5hr – 22hr" {{ $investigation?->estimate_time == '21.5hr – 22hr' ? 'selected' : '' }}>21.5hr – 22hr</option>
                        <option value="22hr – 22.5hr" {{ $investigation?->estimate_time == '22hr – 22.5hr' ? 'selected' : '' }}>22hr – 22.5hr</option>
                        <option value="22.5hr – 23hr" {{ $investigation?->estimate_time == '22.5hr – 23hr' ? 'selected' : '' }}>22.5hr – 23hr</option>
                        <option value="23hr – 23.5hr" {{ $investigation?->estimate_time == '23hr – 23.5hr' ? 'selected' : '' }}>23hr – 23.5hr</option>
                        <option value="23.5hr – 24hr" {{ $investigation?->estimate_time == '23.5hr – 24hr' ? 'selected' : '' }}>23.5hr – 24hr</option>
                    </select>
                </div>
             </div>
           
          <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Estimate Materials needed *</label> 
                    <input type="text" class="form-control" name="estimate_materials" value="{{$investigation->estimate_materials ?? ''}}" placeholder="£0.00"   required>
                </div>
             </div>
             <div class="col-12 ">
                <div class="form-group mb-4"><label >What is the problem reported  *</label> 
                    <textarea class="form-control" name="problem_reported" placeholder="Enter your message..." id="textarea" rows="4" required>{{ $investigation->problem_reported ?? '' }}</textarea>
                </div>
             </div>
          
               
          
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="cartInputEmail1">Where is the problem located in the house  *</label> 
                    <textarea class="form-control" name="problem_location"   placeholder="Enter your message..." id="textarea" rows="4" required>{{ $investigation->problem_location ?? '' }}</textarea>
                </div>
             </div>
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="cartInputEmail1">What is needed to rectify the problem  *</label> 
                    <textarea class="form-control" name="rectify_needed" value="{{$investigation->rectify_needed ?? ''}}"  placeholder="Enter your message..." id="textarea" rows="4" required>{{ $investigation->rectify_needed ?? '' }}</textarea>
                </div>
             </div>
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="cartInputEmail1">Notes regarding potential other Issues after the work is done  *</label> 
                    <textarea class="form-control" placeholder="Enter your message..." name="notes" id="textarea" rows="4" required>{{ $investigation->notes ?? '' }}</textarea>
                </div>
             </div>
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="">Photo upload of the problem *</label>
                    <input type="file" name="photo_path[]" class="form-control" accept="image/*" multiple>
                    </div>
             </div>
          
                   <div class="col-12">
    <div class="form-group mb-4">
        <label>Uploaded Photos:</label>
        <div class="d-flex flex-wrap gap-2">
@if(!empty($investigation->photo_path))
    @php
        $photoPaths = json_decode($investigation->photo_path, true);
    @endphp

    @if(is_array($photoPaths))
        @foreach($photoPaths as $index => $image)
            <div class="image-preview position-relative" style="display: inline-block;">
               <a href="{{ asset('public/investigation_img/' . $image) }}" target="_blank" >
              <img src="{{ asset('public/investigation_img/' . $image) }}" alt="Uploaded Photo" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
              </a> 
                <button type="button" class="btn btn-danger btn-sm delete-image" data-index="{{ $index }}" data-image="{{ $image }}" style="position: absolute; top: 5px; right: 5px;">
<svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                </button>
            </div>
        @endforeach
    @else
        <p>No photos uploaded yet.</p>
    @endif
@else
    <p>No photos uploaded yet.</p>
@endif
        </div>
    </div>
</div>
          
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="">Video Upload of the problem *</label> 
                    <input type="file" name="video_path[]" class="form-control" accept="video/*"></div>
             </div>
          

 
          
                       <div class="col-12 ">
                                   @if(!empty($investigation->video_path))
                           @php
        $photoPaths = json_decode($investigation->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
                         
                <div class="form-group mb-4"><label for="">Video URL</label> 
                 <a href="{{asset('public/investigation_video/'.$image) ?? '#'}}" style="color: #007bff; text-decoration: none;">{{asset('public/investigation_video/'.$image) ?? 'https://vimeo.com/536424732'}}</a>
                  
                     <button type="button" 
                class="btn btn-danger btn-sm ml-2 delete-video-btn" 
               data-index="{{ $index }}" data-image="{{ $image }}">
<svg class="icon icon-xxs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
        </button>

             </div>
                         @endforeach
                         @else 
              <p>No video uploaded yet.</p>
                       @endif
             <div class="col-12">

                <div class="form-group mb-4"><label for="cartInputEmail1">Date & Time engineer accepted statement</label> 
                    <input type="text" name="accepted_at" class="form-control"  value="{{ $investigation && $investigation->accepted_at ? \Carbon\Carbon::parse($investigation->accepted_at)->format('d M Y h:i A') : '' }}" placeholder=""   disabled></div>
             </div>
             
            
             <div class="col-12"><button class="btn btn-gray-800 mt-3 animate-up-2" type="submit">Save</button></div>
           </div>
        </div>
     </form>
</div>

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
<script>
  
  document.addEventListener('DOMContentLoaded', function () {
    // Delete Image Functionality
    const deleteButtons = document.querySelectorAll('.delete-image');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const imageIndex = this.getAttribute('data-index');
            const imageName = this.getAttribute('data-image');
            const investigationId = @json($investigation?->id ?? null);


            if (confirm(`Are you sure you want to delete this image?`)) {
                fetch(`/investigation/${investigationId}/delete-image`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ image: imageName, index: imageIndex })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Image deleted successfully!');
                        location.reload(); // Reload to reflect the changes
                    } else {
                        alert('Failed to delete the image.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred.');
                });
            }
        });
    });

    // Delete Video Functionality
    const deleteVideoButtons = document.querySelectorAll('.delete-video-btn');
  

    deleteVideoButtons.forEach(button => {
        button.addEventListener('click', function () {
 const imageIndex = this.getAttribute('data-index');
            const imageName = this.getAttribute('data-image');
            const investigationId = @json($investigation?->id ?? null);
                                       


            if (confirm('Are you sure you want to delete this video?')) {
                fetch(`/investigation/${investigationId}/delete-video`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                     body: JSON.stringify({ image: imageName, index: imageIndex })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Video deleted successfully!');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Failed to delete video. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error deleting video:', error);
                });
            }
        });
    });
});


  
  
</script>