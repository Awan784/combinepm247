<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completion Form</title>
    
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
                    <h2 style="font-weight: bold; color: #333; margin-top: 0%;">Completion Form</h2>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #ddd;">
                    <div style="flex: 0 0 100%; max-width: 100%;">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; border-bottom: 1px solid #ddd;">
                            <tbody style="font-size:14px">
                                <!-- Row 1 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Postcode:</td>
                                    <td style="padding: 3px; text-align: left;">{{ $completion->postcode ?? '--' }}</td>
                                    <td style="padding: 3px; text-align: right; ">Job Date:</td>
                                    <td style="padding: 3px; text-align: right;">{{ $completion ? \Carbon\Carbon::parse($completion->created_at)->format('d-F Y') : '--' }}</td>
                                </tr>
                                <!-- Row 2 -->
                                <tr>
                                    <td style="padding: 3px; text-align: left; ">Job Type:</td>
                                    <td style="padding: 3px; text-align: left;">{{ $completion->job_type ?? '--' }}</td>
                                    <td style="padding: 3px; text-align: right; ">Completion date/time:</td>
                                    <td style="padding: 3px; text-align: right;">{{ $completion->completion_time ? \Carbon\Carbon::parse($completion->completion_time)->format('d M Y h:i A') : '--' }}</td>
                                </tr>
                            
                            </tbody>
                        </table>
                        
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <h6 style="font-size: 14px">The Completion work Described:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $completion->description ?? '--' }}</p>
                    </div>
                </div>
                <div style="margin-bottom: 0rem;">
                    <h6 style="font-size: 14px">Any further Information needed:</h6>
                    <div>
                        <p style="color: black; margin: 0;font-size: 14px">{{ $completion->further_work ?? '--' }}</p>
                    </div>
                </div>
                <div style="margin-bottom: 0rem;">
                    <h6 style="font-size: 14px">Engineer Acknowledges work completed:</h6>
                    <div>
                        <h6 style="font-size: 14px">I acknowledge that the work completed has been carried out in accordance with the fixed price notes outlined in the
job. The following tasks have been accomplished: <br>• The work specified in the fixed price has been completed to a high standard. <br>
• The work has been thoroughly checked and tested.<br>
• The work area has been left clean and tidy.<br>
• Any additional work required will be communicated to the office and recorded on this completion form.<br>
• The customer has been informed that the work has been completed, and I will be leaving the property shortly.</h6>
                    </div>
                </div>
                  
                                  <div style="display: flex; flex-wrap: nowrap;  padding-top: 1rem; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                    
                    <table style="width: 100%; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                        <tr>

                            <td style="text-align: left; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Engineer Name:  </h5>
                                <span>{{$completion->engineer_name ?? '--'}}
                                </span>
                            </td>
                           <td style="text-align: center; padding-bottom: 1rem;font-size: 15px;">
    <h5 style="margin-bottom: 1px; font-weight: bold;">Date Accepted:</h5>
                             @php
                                use Carbon\Carbon;

                                @endphp
    <span>
        {{ $completion->opening_time ? \Carbon\Carbon::parse($completion->opening_time)->format('d/m/Y') : '--' }}
    </span>
</td>
<td style="text-align: right; padding-bottom: 1rem;font-size: 15px;">
    <h5 style="margin-bottom: 1px; font-weight: bold;">Time Accepted:</h5>
    <span>
        {{ $completion->opening_time ? \Carbon\Carbon::parse($completion->opening_time)->format('H:i:s') : '--' }}
    </span>
</td>
                        </tr>
                    </table>
                </div>
              
                <div style="display: flex; justify-content: space-between;">
                    <h6 style="margin-bottom: 0; font-size: 14px;">Photos added:</h6>
               <div style="margin-top:10px;">
                @if(!empty($completion->photo_path))
                    @php
                        // Decode the JSON array of file paths
                        $photoPaths = json_decode($completion->photo_path);
                    @endphp
                    <div style="display: block; overflow: hidden; margin-top:10px;">
                        @foreach($photoPaths as $photoPath)
                            <div style="display: inline-block; margin-right: 10px; width: 100px;">
                                <img src="{{ url('public/completion_img/' . $photoPath) }}" alt="Photo" style="width: 100%; height: auto; border-radius: 8px;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #fff;">No photos added.</p>
                @endif
            </div>
                </div>
                
                  
                      @if(!empty($completion->video_path))
                           @php
        $photoPaths = json_decode($completion->video_path, true);
    @endphp
                          @foreach($photoPaths as $index => $image)
                         
                <div class="form-group mb-4"><label for="">Video URL</label> 
                 <a href="{{asset('public/completion_video/'.$image) ?? '#'}}" style="color: #007bff; text-decoration: none;">{{asset('public/completion_video/'.$image) ?? 'https://vimeo.com/536424732'}}</a>

             </div>
                         @endforeach
                         @else 
              <p>No video uploaded yet.</p>
                       @endif
                  
                <div style="display: flex; flex-wrap: nowrap;  padding-top: 1rem; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                    <div style="flex: 1 1 100%; padding-bottom: 1rem;">
                        <span><b>Engineer Statement</b> <br> <br>The work has been completed to a professional standard, and I have uploaded photographic and video evidence to demonstrate the
completed tasks. I acknowledge that if any issues arise with the work performed, I will be responsible for returning to assess the
situation. If I am unavailable to revisit the property, PM247 reserves the right to assign another engineer to inspect the work, and
the associated costs will be deducted from my next commission.</span>
                    </div>
                  
                                          
                    <table style="width: 100%; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                        <tr>

                            <td style="text-align: left; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Accepted By Engineer:  </h5>
                                <span>{{$completion->engineer_name ?? '--'}}
                                </span>
                            </td>
                            <td style="text-align: center; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Date:  </h5>
                             
                                <span>{{ Carbon::parse($completion->submitted_time ?? '--')->format('d/m/Y') }}</span>

                            </td>
                            <td style="text-align: right; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Time:   </h5>
                                <span>{{ Carbon::parse($completion->submitted_time ?? '--')->format('H:i:s') }}</span>
                            </td>
                        </tr>
                    </table>
                  
                       
    <div style="flex: 1 1 100%; padding-bottom: 1rem;">
        
        <span>
          <b>Customer Statement</b> <br><br>
           I, the undersigned (the customer/responsible person), acknowledge that the work has been completed and tested. I
am satisfied with the work performed. By accepting this statement, I understand that it does not affect my statutory
rights.
        </span>
    </div>

                    <table style="width: 100%; margin-top: 0; margin-bottom: 0; font-size: 14px; color: black;">
                        <tr>

                            <td style="text-align: left; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Accepted By:  </h5>
                         
                                    @if($completion->customer_present == 1)
            <span>Customer
                                </span>
        @else
            <span>Engineer
                                </span>
        @endif
                            </td>
                            <td style="text-align: center; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Date:  </h5>
                             
                                <span>{{ Carbon::parse($completion->submitted_time ?? '--')->format('d/m/Y') }}</span>

                            </td>
                            <td style="text-align: right; padding-bottom: 1rem;font-size: 15px;">
                                <h5 style="margin-bottom: 1px; font-weight: bold;">Time:   </h5>
                                <span>{{ Carbon::parse($completion->submitted_time ?? '--')->format('H:i:s') }}</span>
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
