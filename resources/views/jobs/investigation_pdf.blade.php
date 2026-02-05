<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investigation Report</title>
    
</head>
<body>
    <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
        <div style="width: 100%; max-width: 100%;">
            <div style="box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; padding: 1.5rem; border-radius: 8px; position: relative;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 1.5rem; margin-bottom: 1.5rem; ">
                    <img src="https://combine.pm247engineers.co.uk/public/logo.png" width="200" alt="pm247 Logo" style="height: auto;">
                    <div style="float: right">
                        <h4 style="margin-bottom: 0; color: #333;">PM247 Ltd</h4>
                        <ul style="list-style: none; padding-left: 0;">
                            <li style="color: black; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                1 Millbridge <br> Hertford <br> SG14 1PY<br> 01992586311
                            </li>
                            <li style="margin-bottom: 0.5rem;">
                                <a href="mailto:info@pm247.co.uk" style="color: #007bff; text-decoration: none; font-weight: bold;">info@pm247.co.uk</a>
                            </li>
                            <li>
                                <a href="http://www.pm247.co.uk/" style="color: #007bff; text-decoration: none; font-weight: bold;">www.pm247.co.uk</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div style="border-bottom: 1px solid #ddd;margin-top: 10rem"></div>
                <div style="margin-top: 1rem; text-align: center;">
                    <h2 style="font-weight: bold; color: #333; margin-top: 0%;">Investigation Form</h2>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #ddd;">
                    <div style="flex: 0 0 100%; max-width: 100%;">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; border-bottom: 1px solid #ddd;">
                            <tbody style="font-size:14px">
                                <!-- Row 1 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Postcode:</td>
                                    <td style="padding: 3px; text-align: left;">{{ $investigation->postcode ?? '--' }}</td>
                                    <td style="padding: 3px; text-align: right; ">Date:</td>
                                    <td style="padding: 3px; text-align: right;">{{ $investigation->created_at ?? '--' }}</td>
                                </tr>
                                <!-- Row 2 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Job Type:</td>
                                    <td style="padding: 3px; text-align: left;">{{ $investigation->job_type ?? '--' }}</td>
                                    <td style="padding: 3px; text-align: right; ">Start Time:</td>
                                    <td style="padding: 3px; text-align: right;">{{ $investigation->start_time ?? '--' }}</td>
                                </tr>
                                <!-- Row 3 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Estimate Materials:</td>
                                    <td style="padding: 3px; text-align: left;">£{{ $investigation->estimate_materials ?? '' }}</td>
                                    <td style="padding: 3px; text-align: right; ">End Time:</td>
                                    <td style="padding: 3px; text-align: right;">{{ $investigation->end_time ?? '--' }}</td>
                                </tr>
                                <!-- Row 4 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Estimate Time:</td>
                                    <td style="padding: 3px; text-align: left;">{{ $investigation->estimate_time ?? '--' }}</td>
                                    <td style="padding: 3px; text-align: right; ">Status:</td>
                                    <td style="padding: 3px; text-align: right;">Completed</td>
                                </tr>
                            </tbody>
                        </table>
                        
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <h6 style="font-size: 14px">The problem described:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $investigation->problem_reported ?? '--' }}</p>
                    </div>
                </div>
                <div style="margin-bottom: 0rem;">
                    <h6 style="font-size: 14px">The problem Location:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $investigation->problem_location ?? '--' }}</p>
                    </div>
                </div>
                <div style="margin-bottom: 0rem;">
                    <h6 style="font-size: 14px">What is needed to rectify:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $investigation->rectify_needed ?? '--' }}</p>
                    </div>
                </div>
                <div style="margin-bottom: 0rem;">
                    <h6 style="font-size: 14px">Any further information needed:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $investigation->notes ?? '--' }}</p>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <h6 style="margin-bottom: 0; font-size: 14px;">Photos added:</h6>
               <div style="margin-top:10px;">
                @if(!empty($investigation->photo_path))
                    @php
                        // Decode the JSON array of file paths
                        $photoPaths = json_decode($investigation->photo_path);
                    @endphp
                    <div style="display: block; overflow: hidden; margin-top:10px;">
                        @foreach($photoPaths as $photoPath)
                            <div style="display: inline-block; margin-right: 10px; width: 100px;">
                                <img src="{{ url('public/investigation_img/' . $photoPath) }}" alt="Photo" style="width: 100%; height: auto; border-radius: 8px;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #fff;">No photos added.</p>
                @endif
            </div>
                </div>
                
<!--                 <div style="margin-top: 2rem; border-bottom: 1px solid #ddd; padding-bottom:0;">
                    <h6 style="font-size: 14px">Video links added:</h6>
                    <div>
                        <a href="{{asset('public/investigation_video/'.$investigation->video_path) ?? '#'}}" style="color: #007bff; text-decoration: none;">{{asset('public/investigation_video/'.$investigation->video_path) ?? 'https://vimeo.com/536424732'}}</a>
                    </div>
                </div> -->
                  
                      @if(!empty($investigation->video_path))
                           @php
        $photoPaths = json_decode($investigation->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
                         
                <div class="form-group mb-4"><label for="">Video URL</label> 
                 <a href="{{asset('public/investigation_video/'.$image) ?? '#'}}" style="color: #007bff; text-decoration: none;">{{asset('public/investigation_video/'.$image) ?? 'https://vimeo.com/536424732'}}</a>

             </div>
                         @endforeach
                         @else 
              <p>No video uploaded yet.</p>
                       @endif
                  
                <div style="display: flex; flex-wrap: nowrap;  padding-top: 1rem; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                    <div style="flex: 1 1 100%; padding-bottom: 1rem;">
                        <span>Has a thorough investigation been carried out and the information provided is accurate at the time of testing.</span>
                    </div>
                    <table style="width: 100%; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                        <tr>

                            <td style="text-align: left; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Signed By:  </h5>
                                <span>{{$investigation->engineer_name ?? '--'}}
                                </span>
                            </td>
                            <td style="text-align: center; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Date:  </h5>
                                @php
                                use Carbon\Carbon;

                                @endphp
                                <span>{{ Carbon::parse($investigation->created_at ?? '--')->format('d/m/Y') }}</span>

                            </td>
                            <td style="text-align: right; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Time:   </h5>
                                <span>{{ Carbon::parse($investigation->created_at ?? '--')->format('d/m/Y H:i:s') }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                
                
                
            </div>
        </div>
    </div>
    
    
    <!-- Add more fields as needed -->

</body>
</html>
