<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PM247 Job Sheet</title>
</head>
<body style="font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-color: #f9fafb; color: #333;">

<header style="background: linear-gradient(90deg, #001f3f, #003566); text-align: center;">
  <div style="background-color: #11224d; color: #ffffff; padding: 0 30px 30px 30px;">
    <div style="background: #fff; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; padding: 1rem 2rem; font-size: 1.25rem; width: 200px; margin: 0 auto 30px;">
      <img src="https://www.pm247.co.uk/wp-content/uploads/2021/11/Logo.png" alt="PM247" width="132" height="85">
    </div>
    <h1 style="margin: 0; font-size: 28px; color: #ffa500;">Job Sheet</h1>
  </div>
</header>

<section style="margin: 20px auto; padding: 20px; background: #ffffff; border-radius: 10px; width: 90%; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">

     @php
                                use Carbon\Carbon;

                                @endphp
<!--   <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <div style="flex: 1; margin: 0 10px;">
      <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Job Details</h2>
      <p><strong>Engineer Name:</strong> {{$investigation->engineer_name ?? '--'}}</p>
      <p><strong>Job Type:</strong> {{ $completion->job_type ?? '--' }}</p>
      <p><strong>Postcode:</strong> {{ $completion->postcode ?? '--' }}</p>
      <p><strong>Date:</strong> {{ $completion ? \Carbon\Carbon::parse($completion->created_at)->format('d-F Y') : '--' }}</p>
      <p><strong>Investigation Start Time:</strong> {{ $investigation->start_time ?? '--' }}</p>
      <p><strong>Investigation End Time:</strong> {{ $investigation->end_time ?? '--' }}</p>
      <p><strong>Completion Time:</strong> {{ $completion->completion_time ? \Carbon\Carbon::parse($completion->completion_time)->format('d M Y h:i A') : '--' }}</p>
    </div>
    <div style="flex: 1; margin: 0 10px;">
      <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Company Address</h2>
      <p><strong>PM247 Ltd</strong></p>
      <p>1 Millbridge<br> Hertford<br> Hertfordshire<br> SG14 1PY</p>
      <p>Email: info@pm247.co.uk<br> Tel: 01992586311</p>
    </div>
  </div> -->
  
  
<!--   Our new table -->
  <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
  <tr>
    <td style="width: 50%; vertical-align: top; padding-right: 10px;">
      <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Job Details</h2>
       <p><strong>Engineer Name:</strong> {{$investigation->engineer_name ?? '--'}}</p>
      <p><strong>Job Type:</strong> {{ $completion->job_type ?? '--' }}</p>
      <p><strong>Postcode:</strong> {{ $completion->postcode ?? '--' }}</p>
      <p><strong>Date:</strong> {{ $completion ? \Carbon\Carbon::parse($completion->created_at)->format('d-F Y') : '--' }}</p>
      <p><strong>Investigation Start Time:</strong> {{ $investigation->start_time ?? '--' }}</p>
      <p><strong>Investigation End Time:</strong> {{ $investigation->end_time ?? '--' }}</p>
      <p><strong>Completion Time:</strong> {{ $completion->completion_time ? \Carbon\Carbon::parse($completion->completion_time)->format('d M Y h:i A') : '--' }}</p>
    </td>
    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
      <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Company Address</h2>
      <p><strong>PM247 Ltd</strong></p>
      <p>1 Millbridge<br> Hertford<br> Hertfordshire<br> SG14 1PY</p>
      <p>Email: info@pm247.co.uk<br> Tel: 01992586311</p>
    </td>
  </tr>
</table>

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Investigation Summary</h2>
  <p><strong>The problem described:</strong></p><p>{{ $investigation->problem_reported ?? '--' }}</p>
  <p><strong>The problem location:</strong></p><p>{{ $investigation->problem_location ?? '--' }}</p>
  <p><strong>What is needed to rectify:</strong></p><p>{{ $investigation->rectify_needed ?? '--' }}</p>
  <p><strong>Any further information needed:</strong></p><p>{{ $investigation->notes ?? '--' }}</p>

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Investigation Photos</h2>
  <div style="display: flex; flex-wrap: wrap; gap: 10px;">
      @if(!empty($investigation->photo_path))
                    @php
                        // Decode the JSON array of file paths
                        $photoPaths = json_decode($investigation->photo_path);
                    @endphp
     @foreach($photoPaths as $photoPath)
    <img src="{{ url('public/investigation_img/' . $photoPath) }}" width="300">
     @endforeach
    @else
                    <p style="color: #fff;">No photos added.</p>
                @endif
  </div>

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Investigation Video</h2>
   @if(!empty($investigation->video_path))
                           @php
        $photoPaths = json_decode($investigation->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
  <p><a href="{{asset('public/investigation_video/'.$image) ?? '#'}}" style="color: #003566;" target="_blank">
    {{asset('public/investigation_video/'.$image) ?? '#'}}
  </a></p>
   @endforeach
                         @else 
              <p>No video uploaded yet.</p>
                       @endif

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Completion Summary</h2>
  <p><strong>The Completion work described:</strong></p><p>{{$completion->description ?? '--'}}</p>
  <p><strong>Any further information needed:</strong></p><p>{{$completion->further_work ?? '--'}}</p>

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Completion Photos</h2>
  
  <div style="display: flex; flex-wrap: wrap; gap: 10px;">
       @if(!empty($completion->photo_path))
                    @php
                        // Decode the JSON array of file paths
                        $photoPaths = json_decode($completion->photo_path);
                    @endphp
     @foreach($photoPaths as $photoPath)
    <img src="{{ url('public/completion_img/' . $photoPath) }}" width="300">
    @endforeach
       @else
                    <p style="color: #fff;">No photos added.</p>
                @endif
    
  </div>

  <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Completion Video</h2>
           @if(!empty($completion->video_path))
                           @php
        $photoPaths = json_decode($completion->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
  <p><a href="{{asset('public/completion_video/'.$image) ?? '#'}}" style="color: #003566;" target="_blank">
    {{asset('public/completion_video/'.$image) ?? '#'}}
  </a></p>
      @endforeach
                         @else 
              <p>No video uploaded yet.</p>
                       @endif

  <div style="margin-top: 20px;">
    <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Engineer Acknowledges Work Completed</h2>
    <ul>
      <li>The work specified in the fixed price has been completed to a high standard.</li>
      <li>The work has been thoroughly checked and tested.</li>
      <li>The work area has been left clean and tidy.</li>
      <li>Any additional work required will be communicated to the office and recorded on this completion form.</li>
      <li>The customer has been informed that the work has been completed and the engineer will be leaving the property shortly.</li>
    </ul>
    <p><strong>Signed By:</strong> {{$completion->engineer_name ?? '--'}}</p>
    <p><strong>Date Accepted:</strong> {{ $completion->opening_time ? \Carbon\Carbon::parse($completion->opening_time)->format('d/m/Y') : '--' }}</p>
    <p><strong>Time Accepted:</strong>  {{ $completion->opening_time ? \Carbon\Carbon::parse($completion->opening_time)->format('H:i:s') : '--' }}</p>
  </div>

  <div style="margin-top: 20px;">
    <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Engineer Statement</h2>
    <p>The work has been completed to a professional standard, and I have uploaded photographic and video evidence to demonstrate the completed tasks.</p>
    <p>I acknowledge that if any issues arise with the work performed, I will be responsible for returning to assess the situation.</p>
    <p>If I am unavailable to revisit the property, PM247 reserves the right to assign another engineer to inspect the work, and the associated costs will be deducted from my next commission.</p>
    <p><strong>Accepted By Engineer:</strong> {{$completion->engineer_name ?? '--'}}</p>
    <p><strong>Date:</strong> {{ Carbon::parse($completion->submitted_time ?? '--')->format('d/m/Y') }}</p>
    <p><strong>Time:</strong> {{ Carbon::parse($completion->submitted_time ?? '--')->format('H:i:s') }}</p>
  </div>

  <div style="margin-top: 20px;">
    <h2 style="font-size: 20px; color: #001f3f; border-bottom: 3px solid #ffa500;">Customer Statement</h2>
    <p>I, the undersigned (the customer/responsible person), acknowledge that the work has been completed and tested. I am satisfied with the work performed. This does not affect my statutory rights.</p>
    @if($completion->customer_present == 1)
    <p><strong>Accepted By:</strong> Customer</p>
      @else
            <p><strong>Accepted By:</strong> Engineer</p>
                                </span>
        @endif
     
    <p><strong>Date:</strong> {{ Carbon::parse($completion->submitted_time ?? '--')->format('d/m/Y') }}</p>
    <p><strong>Time:</strong> {{ Carbon::parse($completion->submitted_time ?? '--')->format('H:i:s') }}</p>
  </div>

</section>

<footer style="text-align: center; padding: 15px; background-color: #001f3f; color: #fff; margin-top: 20px;">
  <p style="margin: 0; font-size: 14px;"><strong>PM247</strong> | 1 Millbridge, Hertford, SG14 1PY</p>
  <p style="margin: 10px 0 0; font-size: 14px;">
    Email: <a href="mailto:info@pm247.co.uk" style="color: #ffa500;">info@pm247.co.uk</a> |
    Phone: <a href="tel:01992586311" style="color: #ffa500;">01992586311</a> |
    <a href="https://www.pm247.co.uk" style="color: #ffa500;">www.pm247.co.uk</a>
  </p>
</footer>

</body>
</html>
