<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Gmail;
use App\Models\EngineerJobType;
use App\Models\EngineerModel;
use App\Models\JobType;
use App\Models\Investigation;
use App\Models\CompletionForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL as FacadesURL;
use Illuminate\Support\Facades\Storage;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Kunnu\Dropbox\Dropbox;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Kunnu\Dropbox\DropboxApp;



class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display all jobs (no user filter).
     */
    public function index()
    {
        $currentDate = Carbon::now()->toDateString();
        $job = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '<' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job1 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job2 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '>' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job3 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('status', 'Completed')->orderBy('date', 'asc')->get();
        return view("jobs/index", compact('job', 'job1', 'job2', 'job3'));
    }

    /**
     * Display only jobs where current user is Agent ASSIGNED or HANDED OVER (not added by).
     */
    public function myJobs()
    {
        $currentDate = Carbon::now()->toDateString();
        $user = auth()->user();
        $job = Job::visibleToUserAgentOrHandover($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '<' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job1 = Job::visibleToUserAgentOrHandover($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job2 = Job::visibleToUserAgentOrHandover($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '>' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
        $job3 = Job::visibleToUserAgentOrHandover($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('status', 'Completed')->orderBy('date', 'asc')->get();
        $isMyJobs = true;
        return view("jobs/index", compact('job', 'job1', 'job2', 'job3', 'isMyJobs'));
    }
  
 public function investigationPdf()
{
    
    $jobs = Job::orderBy('created_at', 'desc')->cursor();
    return view("jobs/investigation_job_show",compact('jobs'));

}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobtypes = JobType::all();
        return view("jobs/create", compact('jobtypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $job = new Job();
        $data['created_by'] = auth()->user()->id;
        $data['date'] = Carbon::today();
        $job->fill($data);
        $job->save();
       // $html = view("mails.jobcreated",compact('job'))->render();
        // $this->InfoBipMail("info@pm247.co.uk",$html,"Contracts & Payments request made");
        // $this->InfoBipMail("nealmartinpm247@gmail.com",$html,"Contracts & Payments request made");
        // $this->messageBirdEmail("info@pm247.co.uk",$html,"Contracts & Payments request made");
       // $this->messageBirdEmail("dirlas24@gmail.com",$html,"Contracts & Payments request made");
        return redirect("jobs")->with("success","Job Saved Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jobtypes = JobType::all();
        $engineers = User::where('user_type_id', 3)->get();
        $agents = User::where('user_type_id', 2)->get();
        $job = Job::find($id);
        return view("jobs/edit",compact('engineers','job','agents','jobtypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $job = Job::find($id);
        $prev_engineer_id = (int) $job->engineer_id;
        $prev_agent_id = (int) $job->agent_id;
        $job->fill($data);
        $job->save();

        if ($prev_engineer_id !== (int) $job->engineer_id || $prev_agent_id !== (int) $job->agent_id) {
            if ($job->engineer_user) {
                $message = "Dear ".($job->engineer_user ? $job->engineer_user->name : '').", we assign you the agent name ".($job->agent_assigned ? $job->agent_assigned->name : '')." on this job which postcode is ". $job->postcode .".";
                $correctphone = ($job->engineer_user ? $job->engineer_user->phone : '');
                if (substr($correctphone, 0, 1) === '0') {
                    $correctphone = substr($correctphone, 1);
                }
                $correctphone = 44 . $correctphone;
               $html = view("mails.assignEngineer",compact('job'))->render();
               $this->messageBirdEmail($job->engineer_user->email, $html, "Agent Assign");
               // $dataa = $this->messageBirdSMS($correctphone,$message);
                // $this->InfoBipMail($job->engineer_user->email,$message,"Agent Assign");
               // $this->messageBirdEmail($job->engineer_user->email,$message,"Agent Assign");
            }
        }
        return redirect("jobs")->with("success","Job Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $job = Job::find($id);
        if ($job) {
            $job->delete();
            return back()->with("error","Job Removed Successfully");
        }else {
            return back()->with('error', 'Job not found');
        }
    }
  
  //investigation function
    public function Investigation($id){
      
      $job = Job::find($id);
      $engineers = User::where('id', '=', $job->engineer_id)->latest('id')->first();
    
      $investigation = Investigation::where('job_id', '=', $id)->latest('id')->first();


      return view('jobs.investigation_job', compact('engineers', 'job' , 'investigation'));
      
     // dd($job , $engineer);
    }
  
      public function completion($id){
      
      $job = Job::find($id);
      $engineers = User::where('id', '=', $job->engineer_id)->latest('id')->first();
    
      $completion = CompletionForm::where('job_id', '=', $id)->latest('id')->first();

      return view('jobs.completion_job', compact('engineers', 'job' , 'completion'));
      
     // dd($job , $engineer);
    }
  
  public function jobSheetPdf($id){
    $job = Job::find($id);
    $investigation = Investigation::where('job_id', '=', $id)->latest('id')->first();
    $completion = CompletionForm::where('job_id', '=', $id)->latest('id')->first();
    
    if (!$job) {
        return redirect()->back()->with('error', 'Job not found.');
    }
    
    if(!$investigation){
      
      return redirect()->back()->with('error', 'Investigation not found.'); 
    }
    
    if(!$completion){
      
      return redirect()->back()->with('error', 'Completion not found.');
    }

    // Generate PDF using Blade view
    $pdf = Pdf::loadView('jobs.jobsheet_new_pdf', compact('job', 'investigation', 'completion'));

    $fileName = 'JobSheet_' . $job->id . '_' . now()->format('Ymd_His') . '.pdf';

    // Return PDF download response
    return $pdf->download($fileName);
    
    
  }
  
  
  
    public function investigationPdfPreview($id){
       
      $investigation = Investigation::where('job_id', '=', $id)->latest('id')->first();

    if (!$investigation || !$investigation->pdf) {
        return redirect()->back()->with('error', 'Investigation not found for this job');
    }

    // Pass investigation to the view
    return view('jobs.investigation_pdf_preview', compact('investigation'));
    }
  
    public function completionPdfPreview($id){
       
      $completion = CompletionForm::where('job_id', '=', $id)->latest('id')->first();

    if (!$completion || !$completion->pdf) {
        return redirect()->back()->with('error', 'Completion Form not found for this job');
    }

    // Pass investigation to the view
    return view('jobs.completion_pdf_preview', compact('completion'));
    }
  
    public function authorizeDropbox()
    {
  
        $dropboxAuthUrl = 'https://www.dropbox.com/oauth2/authorize';
        $clientId = "ktzbnpygga71euv"; //env('DROPBOX_APP_KEY'); // Your Dropbox App Key
       // dd($clientId);
        $redirectUri = route('dropbox.callback'); // Callback URL

        // Build the Dropbox authorization URL
        $url = "{$dropboxAuthUrl}?client_id={$clientId}&response_type=code&token_access_type=offline&redirect_uri={$redirectUri}";

        // Redirect the user to the Dropbox authorization URL
        return redirect($url);
    }
  
   public function handleCallback(Request $request)
    {
        // Get the authorization code from the request
        $authorizationCode = $request->query('code');


        if (!$authorizationCode) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        // Exchange the authorization code for access and refresh tokens
        $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'client_id' =>  "ktzbnpygga71euv",//env('DROPBOX_APP_KEY'),
            'client_secret' => "a5yd9lzqdco7gwr",//env('DROPBOX_APP_SECRET'),
            'redirect_uri' => route('dropbox.callback'),
        ]);


        $data = $response->json();

        // Check if the tokens are received
        if (isset($data['access_token']) && isset($data['refresh_token'])) {
            // Save the tokens to the database
            DB::table('dropbox_tokens')->updateOrInsert(
                ['user_id' => 1], // Replace with your user logic
                [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json(['message' => 'Authorization successful']);
        }

        return response()->json(['error' => $data['error_description'] ?? 'Authorization failed'], 400);
    }

  public function refreshToken()
{
    // Get the refresh token from the database
    $refreshToken = DB::table('dropbox_tokens')->where('user_id', 1)->value('refresh_token');

    if (!$refreshToken) {
        return response()->json(['error' => 'No refresh token found'], 400);
    }

    // Request a new access token
    $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refreshToken,
        'client_id' => "ktzbnpygga71euv",//env('DROPBOX_APP_KEY'),
        'client_secret' => "a5yd9lzqdco7gwr",//env('DROPBOX_APP_SECRET'),
    ]);

    $data = $response->json();

    if (isset($data['access_token'])) {
        // Update the access token in the database
        DB::table('dropbox_tokens')->where('user_id', 1)->update([
            'access_token' => $data['access_token'],
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Token refreshed successfully']);
    }

    return response()->json(['error' => $data['error_description'] ?? 'Failed to refresh token'], 400);
}

  public function InvestigationStore(Request $request, Dropbox $dropbox)
{

   $dropboxToken = DB::table('dropbox_tokens')->first(); 
   $accessToken = $dropboxToken->access_token;
   $refreshToken = $dropboxToken->refresh_token;

    $photoPaths = [];
    $videoPaths = [];

    // Find or create an investigation based on engineer_id and job_id
    $investigation = Investigation::where('engineer_id', $request->engineer_id)
                                   ->where('job_id', $request->job_id)
                                   ->first();

    // If no existing record, create a new instance
    if (!$investigation) {
        $investigation = new Investigation();
    }

    // Handle photo uploads
    if ($request->hasFile('photo_path')) {
        foreach ($request->file('photo_path') as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . rand(1, 1000) . '.' . $extension;

            // Check if the file is an image
            if (strstr($file->getMimeType(), "image/")) {
                $file->move(public_path('investigation_img'), $filename);
                $photoPaths[] = $filename;
            }
        }
        // Merge new photo paths with existing ones
        $existingPhotoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];
        $photoPaths = array_merge($existingPhotoPaths, $photoPaths);
    } else {
        // Retain existing photo paths if no new photos are uploaded
        $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];
    }

    // Handle video uploads
    if ($request->hasFile('video_path')) {
        foreach ($request->file('video_path') as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . rand(1, 1000) . '.' . $extension;

            // Check if the file is a video
            if (strstr($file->getMimeType(), "video/")) {
                $file->move(public_path('investigation_video'), $filename);
                $videoPaths[] = $filename;
            }
        }
        // Merge new video paths with existing ones
        $existingVideoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];
        $videoPaths = array_merge($existingVideoPaths, $videoPaths);
    } else {
        // Retain existing video paths if no new videos are uploaded
        $videoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];
    }

     
   
    // Update investigation data
    $investigation->engineer_id = $request->engineer_id;
    $investigation->engineer_name = $request->engineer_name;
    $investigation->postcode = $request->postcode;
    $investigation->job_type = $request->job_type;
    $investigation->estimate_time = $request->estimate_time;
    $investigation->estimate_materials = $request->estimate_materials;
    $investigation->problem_reported = $request->problem_reported;
    $investigation->problem_location = $request->problem_location;
    $investigation->rectify_needed = $request->rectify_needed;
    $investigation->notes = $request->notes;
    $investigation->photo_path = json_encode($photoPaths); 
    $investigation->video_path = json_encode($videoPaths);
    $investigation->accepted_at = now(); 
    $investigation->job_id = $request->job_id;
    $investigation->created_at = now();

    

    // Generate the PDF
    $currentDate = now()->format('Y-m-d');
    $pdf = Pdf::loadView('jobs.investigation_pdf', compact('investigation'));

    $pdfFileName = 'Investigation_' . $investigation->engineer_name . '_' . $investigation->postcode . '_' . $currentDate . '.pdf';
    
    $pdfFilePath = public_path('investigationspdf/' . $pdfFileName);

    // Save the PDF locally
    $pdf->save($pdfFilePath);
    $investigation->pdf = $pdfFileName;

    $investigation->save();

    // Define the Dropbox path
    $dropboxFilePath = '/investigations/' . $pdfFileName;

    try {
      $dropboxApp = new DropboxApp(
            env('DROPBOX_APP_KEY'),
            env('DROPBOX_APP_SECRET'),
            $accessToken
        );

        $dropbox = new Dropbox($dropboxApp);

        $dropboxFile = $dropbox->upload($pdfFilePath, $dropboxFilePath, ['autorename' => true]);

        // Get the temporary Dropbox URL
        $dropboxUrl = $dropbox->getTemporaryLink($dropboxFile->getPathDisplay());

        // Redirect with success message
        return redirect('/jobs')->with('success', 'Investigation updated successfully.');
    } catch (DropboxClientException $e) {
             return response()->json([
            'message' => 'PDF saved locally, but Dropbox upload failed.',
            'error' => $e->getMessage(),
        ], 500);
    
       
    }
}
  
    public function CompletionStore(Request $request, Dropbox $dropbox)
{

   $dropboxToken = DB::table('dropbox_tokens')->first(); 
   $accessToken = $dropboxToken->access_token;
   $refreshToken = $dropboxToken->refresh_token;

    $photoPaths = [];
    $videoPaths = [];

    // Find or create an investigation based on engineer_id and job_id
    $completion = CompletionForm::where('job_id', $request->job_id)->first();
    // If no existing record, create a new instance
    if (!$completion) {
        $completion = new CompletionForm();
    }

    // Handle photo uploads
    if ($request->hasFile('photo_path')) {
        foreach ($request->file('photo_path') as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . rand(1, 1000) . '.' . $extension;

            // Check if the file is an image
            if (strstr($file->getMimeType(), "image/")) {
                $file->move(public_path('completion_img'), $filename);
                $photoPaths[] = $filename;
            }
        }
        // Merge new photo paths with existing ones
        $existingPhotoPaths = $completion->photo_path ? json_decode($completion->photo_path, true) : [];
        $photoPaths = array_merge($existingPhotoPaths, $photoPaths);
    } else {
        // Retain existing photo paths if no new photos are uploaded
        $photoPaths = $completion->photo_path ? json_decode($completion->photo_path, true) : [];
    }

    // Handle video uploads
    if ($request->hasFile('video_path')) {
        foreach ($request->file('video_path') as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = time() . rand(1, 1000) . '.' . $extension;

            // Check if the file is a video
            if (strstr($file->getMimeType(), "video/")) {
                $file->move(public_path('completion_video'), $filename);
                $videoPaths[] = $filename;
            }
        }
        // Merge new video paths with existing ones
        $existingVideoPaths = $completion->video_path ? json_decode($completion->video_path, true) : [];
        $videoPaths = array_merge($existingVideoPaths, $videoPaths);
    } else {
        // Retain existing video paths if no new videos are uploaded
        $videoPaths = $completion->video_path ? json_decode($completion->video_path, true) : [];
    }

     
   
    // Update investigation data
    $completion->engineer_name = $request->engineer_name;
    $completion->postcode = $request->postcode;
    $completion->job_type = $request->job_type;
    $completion->description = $request->description;
    $completion->further_work = $request->further_work;
    $completion->photo_path = json_encode($photoPaths); 
    $completion->video_path = json_encode($videoPaths);
    $completion->submitted_time = now(); 
    $completion->job_id = $request->job_id;
    $completion->created_at = now();

    

    // Generate the PDF
    $currentDate = now()->format('Y-m-d');
    $pdf = Pdf::loadView('jobs.completion_pdf', compact('completion'));

    $pdfFileName = 'Completion_' . $completion->engineer_name . '_' . $completion->postcode . '_' . $currentDate . '.pdf';
    
    $pdfFilePath = public_path('completionpdf/' . $pdfFileName);

    // Save the PDF locally
    $pdf->save($pdfFilePath);
    $completion->pdf = $pdfFileName;

    $completion->save();

    // Define the Dropbox path
    $dropboxFilePath = '/CompletionForms/' . $pdfFileName;

    try {
      $dropboxApp = new DropboxApp(
            env('DROPBOX_APP_KEY'),
            env('DROPBOX_APP_SECRET'),
            $accessToken
        );

        $dropbox = new Dropbox($dropboxApp);

        $dropboxFile = $dropbox->upload($pdfFilePath, $dropboxFilePath, ['autorename' => true]);

        // Get the temporary Dropbox URL
        $dropboxUrl = $dropbox->getTemporaryLink($dropboxFile->getPathDisplay());
      

        // Redirect with success message
        return redirect('/jobs')->with('success', 'Completion Form updated successfully.');
    } catch (DropboxClientException $e) {
             return response()->json([
            'message' => 'PDF saved locally, but Dropbox upload failed.',
            'error' => $e->getMessage(),
        ], 500);
    
       
    }
}
  
  
 

  // InvestigationController.php
public function deleteImage(Request $request, $id)
{
    $investigation = Investigation::findOrFail($id);

    // Decode photo_path
    $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];

    // Remove the image from the array
    $imageName = $request->input('image');
    $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);

    // Save updated paths to the database
    $investigation->photo_path = json_encode(array_values($updatedPaths));
    $investigation->save();

    // Delete the physical file (optional)
    $imagePath = public_path('investigation_img/' . $imageName);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    return response()->json(['success' => true]);
}
  
  public function completionDeleteImage(Request $request, $id)
{
    $investigation = CompletionForm::findOrFail($id);

    // Decode photo_path
    $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];

    // Remove the image from the array
    $imageName = $request->input('image');
    $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);

    // Save updated paths to the database
    $investigation->photo_path = json_encode(array_values($updatedPaths));
    $investigation->save();

    // Delete the physical file (optional)
    $imagePath = public_path('completion_img/' . $imageName);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    return response()->json(['success' => true]);
}
  
  
  public function deleteVideo(Request $request, $id)
{
    
    $investigation = Investigation::findOrFail($id);
  

    // Decode photo_path
    $photoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];



    $imageName = $request->input('image');

    $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


    $investigation->video_path = json_encode(array_values($updatedPaths));
    $investigation->save();

    // Delete the physical file (optional)
    $imagePath = public_path('investigation_video/' . $imageName);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    return response()->json(['success' => true]);
}
  

   public function completionDeleteVideo(Request $request, $id)
{
    
    $investigation = CompletionForm::findOrFail($id);
  

    // Decode photo_path
    $photoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];



    $imageName = $request->input('image');

    $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


    $investigation->video_path = json_encode(array_values($updatedPaths));
    $investigation->save();

    // Delete the physical file (optional)
    $imagePath = public_path('completion_video/' . $imageName);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    return response()->json(['success' => true]);
}
  



  
  
  public function preview($id)
{
    $investigation = Investigation::findOrFail($id); // Retrieve the investigation by ID
    return view('jobs.investigation_pdf', compact('investigation'));
}

public function showPdf($id)
{
    $investigation = Investigation::findOrFail($id); // Retrieve the investigation by ID
    $logoPath = URL::to('/assets/img/brand/light.svg');
    $pdf = Pdf::loadView('jobs.investigation_pdf', compact('investigation', 'logoPath'));

    // Stream the PDF for preview in an iframe
    return $pdf->stream('InvestigationReport.pdf');
}

    // assign functions

    // GET: Assign Engineer
    public function AssignEngineer($id)
    {
        $engineers = User::where('user_type_id', 3)->get();
        $job = Job::find($id);

        return view('jobs.assign_engineer', compact('engineers', 'job'));
    }

    // POST: Assign Engineer
    public function AssignEngineerPost(Request $request, $id)
    {
        $job = Job::find($id);
        $job->update([
            'engineer_id' => $request->input('engineer_id')
        ]);
        $message = "Dear ".($job->engineer_user ? $job->engineer_user->name : '').", we assign you the job which postcode is ". $job->postcode .".";
            $correctphone = ($job->engineer_user ? $job->engineer_user->phone : '');
            if (substr($correctphone, 0, 1) === '0') {
                $correctphone = substr($correctphone, 1);
            }
            $correctphone = 44 . $correctphone;
        
        $html = view("mails.assignAgent",compact('job'))->render();
        $this->messageBirdEmail($job->engineer_user->email, $html, "Job Assign");
       // $dataa = $this->messageBirdSMS($correctphone,$message);
      
            // $this->InfoBipMail($job->engineer_user->email,$message,"Agent Assign");
         //   $this->messageBirdEmail($job->engineer_user->email,$message,"Job Assign");

        return redirect('/jobs')->with('success', 'Engineer assigned successfully.');
    }

    public function AssignAgent($id)
    {
        $agents = User::where('user_type_id', 2)->get();
        $job = Job::find($id);

        return view('jobs.assign_agent', compact('agents', 'job'));
    }

    public function AssignAgentPost(Request $request, $id)
    {
        $job = Job::find($id);
        $job->update([
            'agent_id' => $request->input('agent_id')
        ]);

        if ($job->engineer_user) {
            $message = "Dear ".($job->engineer_user ? $job->engineer_user->name : '').", we assign you the agent name ".($job->agent_assigned ? $job->agent_assigned->name : '')." on this job which postcode is ". $job->postcode .".";
            $correctphone = ($job->engineer_user ? $job->engineer_user->phone : '');
            if (substr($correctphone, 0, 1) === '0') {
                $correctphone = substr($correctphone, 1);
            }
            $correctphone = 44 . $correctphone;
        $html = view("mails.assignEngineer",compact('job'))->render();
        $this->messageBirdEmail($job->engineer_user->email, $html, "Agent Assign");
           // $dataa = $this->messageBirdSMS($correctphone,$message);
            // $this->InfoBipMail($job->engineer_user->email,$message,"Agent Assign");
           // $this->messageBirdEmail($job->engineer_user->email,$message,"Agent Assign");
        }
        return redirect('/jobs')->with('success', 'Agent assigned successfully.');
    }

    public function AssignHandover($id)
    {
        $job = Job::find($id);
        $agents = User::where('user_type_id', 2)->get();

        return view('jobs.assign_hand_over', compact('agents', 'job'));
    }

    public function AssignHandoverPost(Request $request, $id)
    {
        $job = Job::find($id);
        $job->update([
            'hand_overed_agent' => $request->input('hand_overed_agent')
        ]);

        $message = "Dear ".$job->engineer_user->name.", we assign you the new agent name ".$job->handed_over->name." on this job which postcode is ". $job->postcode .".";
       // $this->messageBirdEmail($job->engineer_user->email,$message,"Agent Assign");
        $html = view("mails.assignEngineer",compact('job'))->render();
        $this->messageBirdEmail($job->engineer_user->email, $html, "Agent Assign");
        // $this->InfoBipMail($job->engineer_user->email,$message,"Agent Assign");
        // $correctphone = $job->engineer_user->phone;
        // if (substr($correctphone, 0, 1) === '0') {
        //     $correctphone = substr($correctphone, 1);
        // }
        // $correctphone = 44 . $correctphone;
        // $dataa = $this->messageBirdSMS($correctphone,$message);
        return redirect('/jobs')->with('success', 'Agent handover assigned successfully.');
    }

    //  Accept Job
    public function AcceptJob(Request $request,$id)
    {
        if($request->job_invoice_no){
            $job = Job::find($id);
            if($job){
                $job->update([
                    'job_invoice_no' => $request->job_invoice_no,
                    'contract_status' => '1'
                ]);

                $html = view("mails.jobcreated",compact('job'))->render();
                // $this->messageBirdEmail("info@pm247.co.uk",$html,"Contracts & Payments request made");
                $this->messageBirdEmail("contracts@pm247.co.uk",$html,"Contracts & Payments request made");
                // $this->InfoBipMail("info@pm247.co.uk",$html,"Contracts & Payments request made");
                // $this->InfoBipMail("nealmartinpm247@gmail.com",$html,"Contracts & Payments request made");
                return redirect()->route('jobs.index')->with('success', 'Job Accept successfully.');
            }else{
                return redirect()->route('jobs.index')->with('error', 'Job is not found.');
            }
        }else{
            return redirect()->route('jobs.index')->with('error', 'Job Invoice No is required.');
        }
    }
    //  Reject Job
    public function RejectJob($id)
    {
        $job = Job::find($id);
        if($job){
            $job->update([
                'contract_status' => '2'
            ]);
            return redirect()->route('jobs.index')->with('success', 'Job Reject successfully.');
        }else{
            return redirect()->route('jobs.index')->with('error', 'Job is not found.');
        }
    }


    // Check latest Data Ajax function
    public function latestData(Request $request)
    {
        $loadedTime = Carbon::parse($request->input('loaded_time'));
        $sevenDaysAgo = Carbon::now()->subDays(3);
        $filterMyJobs = $request->input('filter') === 'my';
        $user = auth()->user();
        $job = $filterMyJobs
            ? Job::visibleToUser($user)->where(function ($q) use ($loadedTime) {
                $q->where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime);
            })->first()
            : Job::where(function ($q) use ($loadedTime) {
                $q->where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime);
            })->first();

        if ($job) {
            $currentDate = Carbon::now()->toDateString();
            if ($filterMyJobs) {
                $job = Job::visibleToUser($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '<' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job1 = Job::visibleToUser($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job2 = Job::visibleToUser($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '>' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job3 = Job::visibleToUser($user)->where('created_at', '>=', Carbon::now()->subDays(3))->where('status', 'Completed')->orderBy('date', 'asc')->get();
            } else {
                $job = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '<' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job1 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job2 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', '>' , $currentDate)->where('status', 'Active')->orderBy('date', 'asc')->get();
                $job3 = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('status', 'Completed')->orderBy('date', 'asc')->get();
            }
            $html = view('includes.mainDashboard', ['jobs' => $job])->render();
            $html1 = view('includes.mainDashboard', ['jobs' => $job1])->render();
            $html2 = view('includes.mainDashboard', ['jobs' => $job2])->render();
            $html3 = view('includes.mainDashboard', ['jobs' => $job3])->render();
            return response()->json([
                'status' => 'success',
                'data0' => $html,
                'data1' => $html1,
                'data2' => $html2,
                'data3' => $html3
            ]);
        } else {
            return response()->json(['status' => 'no_updates']);
        }
    }
    // Check latest Data Ajax function
    public function latestTvData(Request $request)
    {
        $loadedTime = Carbon::parse($request->input('loaded_time'));

        // Query to get new or updated jobs since the page was loaded
        $job = Job::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();

        if ($job) {
            $currentDate = Carbon::now()->toDateString();
            $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(3))->where('date', $currentDate)->where('status','active')->get();
            $html = view('tv.data.assign', ['jobs' => $jobs])->render();
            return response()->json([
                'status' => 'success',
                'data' => $html
            ]);
        } else {
            return response()->json(['status' => 'no_updates']);
        }
    }


    // Contracts Functions
    public function Contracts()
    {
        $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('contract_status', '1')
            ->latest()
            ->get();
        return view("jobs/contracts",compact('jobs'));
    }
    public function ContractSent($id)
    {
        $contract = new Contract;
        $contract->job_id = $id;
        $contract->sent_by = auth()->user()->id;
        $contract->status = "sent";
        $contract->sent_time = Carbon::now();
        $contract->save();
        return redirect("contracts")->with("success","Contract Sent Successfully");
    }
    public function ContractReceived($id)
    {
        $contract = Contract::find($id);
        $contract->status = "received";
        $contract->received_time = Carbon::now();
        if ($contract->job->engineer_user) {
            $html = view("mails.contractSign",compact('contract'))->render();
            // $this->InfoBipMail($contract->job->engineer_user->email,$html,"Contract has been signed");
            $this->messageBirdEmail($contract->job->engineer_user->email,$html,"Contract has been signed");
            $message = "Dear ".$contract->job->engineer_user->name.", The contract has been signed for the Job at ".$contract->job->postcode.". Please only proceed when payment has also been confirmed as paid. You will be informed once payment is received by email and sms.";
            $correctphone = $contract->job->engineer_user->phone;
            if (substr($correctphone, 0, 1) === '0') {
                $correctphone = substr($correctphone, 1);
            }
            $correctphone = 44 . $correctphone;
           // $dataa = $this->messageBirdSMS($correctphone,$message);
        }
        $contract->inform_time = Carbon::now();
        $contract->save();
        return redirect("contracts")->with("success","Contract Received Successfully");
    }

    // Payment Functions

    public function Payments()
    {

        $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('contract_status', '1')
            ->latest()
            ->get();

        return view("jobs/payments",compact('jobs'));
    }
    public function PaymentSent($id)
    {
        $payment = new Payment;
        $payment->job_id = $id;
        $payment->sent_by = auth()->user()->id;
        $payment->status = "sent";
        $payment->sent_time = Carbon::now();
        $payment->save();
        return redirect("payments")->with("success","Payment Sent Successfully");
    }
    public function PaymentReceived($id)
    {
        $payment = Payment::find($id);
        $payment->status = "received";
        $payment->received_time = Carbon::now();
        if ($payment->job->engineer_user) {
            $html = view("mails.paymentSign",compact('payment'))->render();
            // $this->InfoBipMail($payment->job->engineer_user->email,$html,"Payment has been Received");
            $this->messageBirdEmail($payment->job->engineer_user->email,$html,"Payment has been Received");
            $message = "Dear ".$payment->job->engineer_user->name.", The Payment has been received for the Job at ".$payment->job->postcode.". Please only proceed when contract has also been confirmed as paid. You will be informed once the contract is received by email and sms";
            $correctphone = $payment->job->engineer_user->phone;
            if (substr($correctphone, 0, 1) === '0') {
                $correctphone = substr($correctphone, 1);
            }
            $correctphone = 44 . $correctphone;
         //   $dataa = $this->messageBirdSMS($payment->job->engineer_user->phone,$message);
        }
        $payment->inform_time = Carbon::now();
        $payment->save();
        return redirect("payments")->with("success","Payment Received Successfully");
    }

    // Check latest Data Ajax function
    public function contractLatestData(Request $request)
    {
        $loadedTime = Carbon::parse($request->input('loaded_time'));
        $sevenDaysAgo = Carbon::now()->subDays(7);
        // Query to get new or updated jobs since the page was loaded
        $contract = Contract::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();
        $payment = Payment::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();

        if ($contract || $payment) {
            $contracts = Contract::latest()->take(10)->get()->reject(function ($contract) use ($sevenDaysAgo) {
                            return $contract->job->created_at < $sevenDaysAgo;
                        });
            $payments = Payment::latest()->take(10)->get()->reject(function ($payment) use ($sevenDaysAgo) {
                            return $payment->job->created_at < $sevenDaysAgo;
                        });
            $html = view('includes.contractMainDashboard', ['contracts' => $contracts , 'payments' => $payments])->render();
            return response()->json([
                'status' => 'success',
                'data' => $html
            ]);
        } else {
            return response()->json(['status' => 'no_updates']);
        }
    }
    public function contractLatestTvData(Request $request)
    {
        $loadedTime = Carbon::parse($request->input('loaded_time'));

        // Query to get new or updated jobs since the page was loaded
        $job = Job::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();
        $contract = Contract::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();
        $payment = Payment::where('created_at', '>', $loadedTime)->orWhere('updated_at', '>', $loadedTime)->first();

        if ($job || $contract || $payment) {
            $jobs = Job::where('created_at', '>=', Carbon::now()->subDays(7))->where('contract_status', '1')->latest()->get();
            $html = view('tv.data.contract', ['jobs' => $jobs])->render();
            return response()->json([
                'status' => 'success',
                'data' => $html
            ]);
        } else {
            return response()->json(['status' => 'no_updates']);
        }
    }

    // Email-check function

    public function refreshTokenIfNeeded()
    {
        $user = User::find(auth()->user()->id);
        $refreshToken = $user->gmail_refresh_token;

        if (!$refreshToken) {
            return "error";
        }

        $client = new GoogleClient();
        $client->setClientId(config('gmailApiEnv.GOOGLE_CLIENT_ID'));
        $client->setClientSecret(config('gmailApiEnv.GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(config('gmailApiEnv.GOOGLE_REDIRECT_URI'));

        $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

        if (isset($newToken['access_token'])) {

            if (isset($newToken['refresh_token'])) {
                // Save the new refresh token if it's returned
                $user->gmail_refresh_token = $newToken['refresh_token'];
            }

            $user->save();
            return $newToken['access_token'];
        }

        return "error";
    }

    public function listEmails1(Request $request)
    {
        
        $client = new GoogleClient();
        dd($client);
        if (!$request->session()->has('gmail_token')) {
            $accessToken = $this->refreshTokenIfNeeded();
            if($accessToken == "error"){
                return redirect()->route('login.google');
            }
            $request->session()->put('gmail_token', $accessToken);
            $client->setAccessToken($accessToken);
        }else{
            try {
                $token = $request->session()->get('gmail_token');
                $client->setAccessToken($token);
                if ($client->isAccessTokenExpired()) {
                    $accessToken = $this->refreshTokenIfNeeded();
                    if($accessToken == "error"){
                        return redirect()->route('login.google');
                    }
                    $request->session()->put('gmail_token', $accessToken);
                    $client->setAccessToken($accessToken);
                }

            } catch (\Throwable $th) {
                return redirect()->route('login.google');
            }
        }
        $data = $this->MailChecker($client);

    }

    public function GmailErrorCheck(Request $request){
        $updatedUser = User::where('is_login',1)->where('gmail_login',1)->first();
        if (!$updatedUser) {
            return response()->json(['status' => 'no gmail login']);
        }
        return response()->json(['status' => 'gmial login']);
    }

    public function listEmails(Request $request)
    {
        $client = new GoogleClient();
        if (!$request->session()->has('gmail_token')) {
            $accessToken = $this->refreshTokenIfNeeded();
            if($accessToken == "error"){
                return redirect()->route('login.google');
            }
            $request->session()->put('gmail_token', $accessToken);
            $client->setAccessToken($accessToken);
        }else{
            try {
                $token = $request->session()->get('gmail_token');
                $client->setAccessToken($token);
                if ($client->isAccessTokenExpired()) {
                    $accessToken = $this->refreshTokenIfNeeded();
                    if($accessToken == "error"){
                        return redirect()->route('login.google');
                    }
                    $request->session()->put('gmail_token', $accessToken);
                    $client->setAccessToken($accessToken);
                }
            } catch (\Throwable $th) {
                return redirect()->route('login.google');
            }
        }
        $this->MailChecker($client);
        return redirect('/');
    }

    public function GmailCheck(Request $request)
    {
        $client = new GoogleClient();
        if (!$request->session()->has('gmail_token')) {
            $accessToken = $this->refreshTokenIfNeeded();
            if($accessToken == "error"){
                $updatedUser = User::find(auth()->user()->id);
                $updatedUser->gmail_login = 0;
                $updatedUser->save();
                return response()->json(['status' => 'error']);
            }
            $request->session()->put('gmail_token', $accessToken);
            $client->setAccessToken($accessToken);
        }else{
            try {
                $token = $request->session()->get('gmail_token');
                $client->setAccessToken($token);
                if ($client->isAccessTokenExpired()) {
                    $accessToken = $this->refreshTokenIfNeeded();
                    if($accessToken == "error"){
                        $updatedUser = User::find(auth()->user()->id);
                        $updatedUser->gmail_login = 0;
                        $updatedUser->save();
                        return response()->json(['status' => 'error']);
                    }
                    $request->session()->put('gmail_token', $accessToken);
                    $client->setAccessToken($accessToken);
                }

            } catch (\Throwable $th) {
                $updatedUser = User::find(auth()->user()->id);
                $updatedUser->gmail_login = 0;
                $updatedUser->save();
                return response()->json(['status' => 'error']);
            }
        }
        $changes = $this->MailChecker($client);
        $updatedUser = User::find(auth()->user()->id);
        $updatedUser->gmail_login = 1;
        $updatedUser->save();
        if ($changes > 0) {
            return response()->json(['status' => 'data_updated']);
        }
        return response()->json(['status' => 'no_error']);
    }

    public function MailChecker($client){
        $service = new Gmail($client);
        $user = 'me';
        $results = $service->users_messages->listUsersMessages($user, ['maxResults' => 25]);
        $messages = [];

        foreach ($results->getMessages() as $message) {
            $msg = $service->users_messages->get($user, $message->getId());
            $payload = $msg->getPayload();
            $headers = collect($payload->getHeaders());

            // Retrieve the subject header safely
            $subjectHeader = $headers->first(fn($header) => $header->getName() === 'Subject');
            $subject = $subjectHeader ? $subjectHeader->getValue() : null;

            // Retrieve the to header safely
            $toHeader = $headers->first(fn($header) => $header->getName() === 'To');
            $toEmail = $toHeader ? $toHeader->getValue() : null;

            // Extract the message body
            $body = $this->getBodyFromPayload($payload);
            $body = trim($body);

            $emailData = [
                'id' => $msg->getId(),
                'subject' => $subject,
                'toEmail' => $toEmail,
                'body' => $body,
            ];
            $messages[] = $emailData;
        }

        $changes = 0;
        foreach ($messages as $message) {
            // echo $message['subject'] . "</br>";
            $changes += $this->assignMailGetter($message, $changes);
            $changes += $this->contractMailGetter($message, $changes);
        }
        return $changes;
    }

  //new method
  
  private function assignMailGetter($message, $changes)
{
    $subject = $message['subject'];

    $body = preg_replace('/\s+/', ' ', strip_tags($message['body']));
    $body = trim($body);

    // Check for specific subjects
    if (strpos($subject, "PM247 || NEW JOB BOOKED") !== false || strpos($subject, "PM247 || JOB CANCELLED") !== false || strpos($subject, "PM247 || UPDATED BOOKED JOB") !== false) {
        $details = $this->extractJobDetails($body);



        // Base query for finding jobs based on subject type
        if (strpos($subject, "PM247 || NEW JOB BOOKED") !== false) {

            // Handle NEW JOB BOOKED
            $job = Job::withTrashed()->where('customer_email', $details['email'])
                      ->where('postcode', $details['postcode'])
                      ->where('added_by', $details['username'])
                     // ->where('update_status', 0)
                      ->first();

          
          
              if (!$job){
                Job::create([
                    'customer_email' => $details['email'],
                    'postcode' => $details['postcode'],
                    'date' => $details['date'],
                    'added_by' => $details['username'],
                    'job_type' => $details['jobtype'],
                ]);
                $changes++;
            }
        } elseif (strpos($subject, "PM247 || JOB CANCELLED") !== false) {
            // Handle JOB CANCELLED

            $job = Job::withTrashed()->where('customer_email', $details['email'])
                      ->where('postcode', $details['postcode'])
                      ->where('added_by', $details['username'])
                      ->first();

            if ($job && $job->deleted_at == null) {
                $job->delete();
                $changes++;
            }
        } elseif (strpos($subject, "PM247 || UPDATED BOOKED JOB") !== false) {
          

            // Handle UPDATED BOOKED JOB based on postcode match only
            $job = Job::where('postcode', $details['postcode'])->first();

            
            
            if ($job) {
                $job->update([
                    'customer_email' => $details['email'],
                    'date' => $details['date'],
                    'added_by' => $details['username'],
                    'job_type' => $details['jobtype'],
                    'update_status' => 1
                ]);
               //dd("Job updated successfully", $job);
                $changes++;
            }
        }
    }

    return $changes;
}

  
  //end new method
  
  
  
//     private function assignMailGetterOld($message, $changes){
//         $subject = $message['subject'];
//         $body = preg_replace('/\s+/', ' ', strip_tags($message['body']));
//         $body = trim($body);
//         if (strpos($subject, "PM247 || NEW JOB BOOKED") !== false || strpos($subject, "PM247 || JOB CANCELLED") !== false) {
//             $details = $this->extractJobDetails($body);

//             $job = Job::withTrashed()->where('customer_email', $details['email'])
//                       ->where('postcode', $details['postcode'])
//                     //   ->where('date', $details['date'])
//                       ->where('added_by', $details['username'])
//                       ->first();
//             if (strpos($subject, "PM247 || NEW JOB BOOKED") !== false) {
//                 if (!$job) {
//                     Job::create([
//                         'customer_email' => $details['email'],
//                         'postcode' => $details['postcode'],
//                         'date' => $details['date'],
//                         'added_by' => $details['username'],
//                         'job_type' => $details['jobtype'],
//                     ]);
//                     $changes++;
//                 }
//             } elseif (strpos($subject, "PM247 || JOB CANCELLED") !== false) {
//                 if ($job && $job->deleted_at == null) {
//                     $job->delete();
//                     $changes++;
//                 }
//             }
//         }
//         return $changes;
//     }
  
  
  
    private function contractMailGetter($message, $changes){
        $subject = $message['subject'];
        if (strpos($subject, "Bank Transfer Received") !== false) {
            $body = $message['body'];
            // $body = preg_replace('/\s+/', ' ', strip_tags($message['body']));
            // $body = trim($body);
            // if (preg_match('/Payment from\s+([a-zA-Z\s]+)\s+([a-zA-Z]*)?(\d+)/', $body, $matches)) {
            // if (preg_match('/Payment from\s+([a-zA-Z\s]+)\s+([a-zA-Z]*)?(\d{5})([a-zA-Z0-9]+)(.{2})\s*$/m', $body, $matches)) {
            if (preg_match('/Payment from\s+([a-zA-Z\s]+)\s+([a-zA-Z]{2})+([a-zA-Z]*)?(\d{5})([a-zA-Z]{2})([a-zA-Z0-9]+)(.{3})\s*$/m', $body, $matches)) {
                $invoice_number = $matches[4];
                $job = Job::where('job_invoice_no',$invoice_number)->where('contract_status', '!=', '2')->latest()->first();
                // if(!$job){
                //     $postcode = $matches[6];
                //     $job = Job::where('postcode', 'like' , '%' . $postcode . '%')->first();
                // }
                if($job){
                    if($job->payment){
                        $payment = $job->payment;
                        if($payment->status !== 'received'){
                            $this->PaymentReceived($payment->id);
                            $changes++;
                        }
                    }else{
                        $payment = new Payment;
                        $payment->job_id = $job->id;
                        $payment->save();
                        $this->PaymentReceived($payment->id);
                    }
                }
            }
        }else{
            $body = preg_replace('/\s+/', ' ', strip_tags($message['body']));
            $body = trim($body);
            if ((strpos($subject, "Contract") !== false && strpos($subject, "sent to") !== false) || $subject === "PM247 Service Contract Sent") {    
                if (preg_match('/Recipient\s*(.*?)\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $body, $matches)) {
                    $email = $matches[2];
                    $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                    if($job && $job->contract == null){
                        $this->ContractSent($job->id);
                        $changes++;
                    }
                }
            } else if ((strpos($subject, "Contract") !== false && strpos($subject, "has been signed by") !== false) || $subject === "PM247 Service Contract Signed") {
                if (preg_match('/Recipient\s*(.*?)\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $body, $matches)) {
                    $email = $matches[2];
                    $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                    if($job && $job->contract){
                        $contract = $job->contract;
                        if($contract->status !== 'received'){
                            $this->ContractReceived($contract->id);
                            $changes++;
                        }
                    }
                }
            } else if (strpos($subject, "A new invoice was created for ") !== false) {
                if (preg_match('/Customer\s*(.*?)\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $body, $matches)) {
                    $email = $matches[2];
                    $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                    if($job && $job->payment == null){
                        $this->PaymentSent($job->id);
                        $changes++;
                    }
                }
            } else if (strpos($subject, "An invoice was paid by") !== false) {
                if (preg_match('/Customer\s*(.*?)\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $body, $matches)) {
                    $email = $matches[2];
                    $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                    if($job && $job->payment){
                        $payment = $job->payment;
                        if($payment->status !== 'received'){
                            $this->PaymentReceived($payment->id);
                            $changes++;
                        }
                    }
                }
            } else if (strpos($subject, "VoltPayByLinkMail") !== false) {
                $email = $message['toEmail'];
                $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                if($job && $job->payment == null){
                    $this->PaymentSent($job->id);
                    $changes++;
                }
            } else if (strpos($subject, "Successful Volt Payment Detail") !== false) {
                $email = $message['toEmail'];
                $job = Job::where('customer_email',$email)->where('contract_status', '!=', '2')->latest()->first();
                    if($job && $job->payment){
                        $payment = $job->payment;
                        if($payment->status !== 'received'){
                            $this->PaymentReceived($payment->id);
                            $changes++;
                        }
                    }
            } 
            // previous bank tranfer mail
            // } else if (strpos($subject, "Bank Transfer Received") !== false) {
            //     if (preg_match('/Payment from\s+([a-zA-Z\s]+)\s+([a-zA-Z]{2})\d{5}([a-zA-Z]+)(\d{2})([a-zA-Z0-9]+)(.{2})\s*$/m', $body, $matches)) {
            //         $invoice_number = $matches[3];
            //         $job = Job::where('job_invoice_no',$invoice_number)->latest()->first();
            //         // if(!$job){
            //             // dd($matches);
            //         //     $postcode = $matches[5];
            //         //     // $job = Job:::whereRaw('REPLACE(postcode, " ", "") = ?', [str_replace(' ', '', $extractedPostcode)])->first();
            //         // }
            //         if($job){
            //             if($job->payment){
            //                 $payment = $job->payment;
            //                 if($payment->status !== 'received'){
            //                     $this->PaymentReceived($payment->id);
            //                     $changes++;
            //                 }
            //             }else{
            //                 $payment = new Payment;
            //                 $payment->job_id = $job->id;
            //                 $payment->save();
            //                 $this->PaymentReceived($payment->id);
            //             }
            //         }
            //     }
        }
        return $changes;
    }

    private function getBodyFromPayload($payload) {
        $body = '';
        $parts = $payload->getParts();

        if (empty($parts)) {
            // Single part email
            $body = $payload->getBody()->getData();
            // Decode base64url
            if ($body) {
                $body = strtr($body, '-_', '+/');
                $body = base64_decode($body);
            }
        } else {
            // Multi-part email
            foreach ($parts as $part) {
                $mimeType = $part->getMimeType();
                $bodyPart = $part->getBody()->getData();
                if ($bodyPart) {
                    $bodyPart = strtr($bodyPart, '-_', '+/');
                    $bodyPart = base64_decode($bodyPart);
                    // Append body part to body
                    if ($mimeType === 'text/plain') {
                        $body .= $bodyPart;
                    } elseif ($mimeType === 'text/html') {
                        // You might want to process HTML content separately if needed
                        $body .= strip_tags($bodyPart);
                    }
                }
            }
        }

        return $body;
    }
  private function extractJobDetails($body) {
    preg_match('/Owner email address:\s*([^\s]+)/', $body, $email);
    preg_match('/Job Created User Name:\s*(.+?)\s*Job Created Time:/s', $body, $username);
    preg_match('/Job Postcode:\s*(.+?)\s*Job Address:/s', $body, $postcode);
    preg_match('/Job Date:\s*([^\s]+)/', $body, $date);
    
    // Updated jobtype pattern
    preg_match('/Job Type:\s*(.+?)(?:\s*Advertising|$)/', $body, $jobtype);

    return [
        'email' => $email[1],
        'username' => trim(preg_replace('/[\s>]+/', ' ', $username[1])),
        'postcode' => trim(preg_replace('/[\s>]+/', ' ', $postcode[1])),
        'jobtype' => trim(preg_replace('/[\s>]+/', ' ', $jobtype[1] ?? '')),
        'date' => date("Y-m-d", strtotime(str_replace('/', '-', $date[1]))),
    ];
}


//     private function extractJobDetails($body) {
//         preg_match('/Owner email address:\s*([^\s]+)/', $body, $email);
//         preg_match('/Job Created User Name:\s*(.+?)\s*Job Created Time:/s', $body, $username);
//         preg_match('/Job Postcode:\s*(.+?)\s*Job Address:/s', $body, $postcode);
//         preg_match('/Job Date:\s*([^\s]+)/', $body, $date);
//       //  preg_match('/Job Type:\s*(.+?)(?:\s*Advertising|$)/', $body, $jobtype); // Updated jobtype pattern
//         preg_match('/Job Type:\s*(.+?)\s*Advertising:/s', $body, $jobtype);

//        // preg_match('/Job Type:\s*([^\s]+)/', $body, $jobtype);
//        // preg_match('/Job Type:\s*(.+?)\s', $body, $jobtype);

//         return [
//             'email' => $email[1],
//             'username' => trim(preg_replace('/[\s>]+/', ' ', $username[1])),
//             'postcode' => trim(preg_replace('/[\s>]+/', ' ', $postcode[1])),
//            // 'jobtype' => trim(preg_replace('/[\s>]+/', ' ', $jobtype[1] ?? '')),
//             'jobtype' => trim(preg_replace('/[\s>]+/', ' ', $jobtype[1] ?? '')),
//             'date' => date("Y-m-d", strtotime(str_replace('/', '-', $date[1]))),
//         ];
//     }

}
