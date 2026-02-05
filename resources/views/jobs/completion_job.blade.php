@extends("layouts.dashboard")

@section("content")
<div class="row my-4">
    <form action="{{route('job.completion_store')}}" method="POST" class="card border-0 shadow p-3 pb-4 mb-4" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="engineer_id" value="{{$job->engineer_id ?? ''}}">
        <input type="hidden" name="job_id" value="{{$job->id ?? ''}}">
        <div class="card-header mx-lg-4 p-0 py-3 py-lg-4 mb-4 mb-md-0">
           <h3 class="h5 mb-0">Completion Form</h3>
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
                <div class="form-group mb-4"><label for="cartInputEmail1">Engineer Accepts Completion Statement Time *</label> 
                    <input type="text" name="opening_time" class="form-control" placeholder="Opening Time" value="{{ $completion && $completion->opening_time ? \Carbon\Carbon::parse($completion->opening_time)->format('d M Y h:i A') : '' }}"   required disabled></div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="form-group mb-4"><label for="cartInputEmail1">Completion Form Start Time *</label> 
                    <input type="text" name="completion_time" class="form-control" placeholder="Completion Time" value="{{ $completion && $completion->completion_time ? \Carbon\Carbon::parse($completion->completion_time)->format('d M Y h:i A') : '' }}"   required disabled></div>
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
     
        
             <div class="col-12 ">
                <div class="form-group mb-4"><label >Enter Description  *</label> 
                    <textarea class="form-control" name="description" placeholder="Enter your message..." id="textarea" rows="4" required>{{ $completion->description ?? '' }}</textarea>
                </div>
             </div>
          
               
          
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="cartInputEmail1">Further Work  *</label> 
                    <textarea class="form-control" name="further_work"   placeholder="Enter your message..." id="textarea" rows="4" required>{{ $completion->further_work ?? '' }}</textarea>
                </div>
             </div>
            
            
             <div class="col-12 ">
                <div class="form-group mb-4"><label for="">Photo upload *</label>
                    <input type="file" name="photo_path[]" class="form-control" accept="image/*" multiple>
                    </div>
             </div>
          
                   <div class="col-12">
    <div class="form-group mb-4">
        <label>Uploaded Photos:</label>
        <div class="d-flex flex-wrap gap-2">
@if(!empty($completion->photo_path))
    @php
        $photoPaths = json_decode($completion->photo_path, true);
    @endphp

    @if(is_array($photoPaths))
        @foreach($photoPaths as $index => $image)
            <div class="image-preview position-relative" style="display: inline-block;">
               <a href="{{ asset('public/completion_img/' . $image) }}" target="_blank" >
              <img src="{{ asset('public/completion_img/' . $image) }}" alt="Uploaded Photo" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
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
                <div class="form-group mb-4"><label for="">Video Upload *</label> 
                    <input type="file" name="video_path[]" class="form-control" accept="video/*"></div>
             </div>
          

 
          
                       <div class="col-12 ">
                                   @if(!empty($completion->video_path))
                           @php
        $photoPaths = json_decode($completion->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
                         
                <div class="form-group mb-4"><label for="">Video URL</label> 
                 <a href="{{asset('public/completion_video/'.$image) ?? '#'}}" style="color: #007bff; text-decoration: none;">{{asset('public/completion_video/'.$image) ?? 'https://vimeo.com/536424732'}}</a>
                  
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
               
                <div class="form-group mb-4">
                  <label for="cartInputEmail1">
                   @if(!$completion)
    Date & Time when engineer accepts statement on the behalf of customer.
@elseif($completion->customer_present === 1)
    Date & Time when customer accepts statement
@else
    Date & Time when engineer accepts statement on the behalf of customer.
@endif
                  </label> 
                    <input type="text" name="accepted_at" class="form-control"  value="{{ $completion && $completion->submitted_time ? \Carbon\Carbon::parse($completion->submitted_time)->format('d M Y h:i A') : '' }}" placeholder=""   disabled></div>
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
            const investigationId = @json($completion?->id ?? null);


            if (confirm(`Are you sure you want to delete this image?`)) {
                fetch(`/completion/${investigationId}/delete-image`, {
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
            const investigationId = @json($completion?->id ?? null);
                                       


            if (confirm('Are you sure you want to delete this video?')) {
                fetch(`/completion/${investigationId}/delete-video`, {
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