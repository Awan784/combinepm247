<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserType;
use App\Models\EngineerModel;
use App\Models\EngineerJobType;
use App\Models\EngineerAvailability;
use App\Models\JobType;
use App\Models\Job;
use App\Models\InfoBipModel;
use App\Models\Investigation;
use App\Models\CompletionForm;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL as FacadesURL;
use Illuminate\Support\Facades\Storage;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Kunnu\Dropbox\Dropbox;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Kunnu\Dropbox\DropboxApp;


class EngineerController extends Controller
{
  public function jobs(Request $request, $id)
{
    $engineer = User::find($id);

    if ($engineer) {
        $jobs = Job::with([
            'engineer_user:id,name',
            'agent_assigned:id,name',
            'handed_over:id,name',
            'contract.sent_by_user:id,name',
            'payment.sent_by_user:id,name'
        ])
        ->where('engineer_id', $id)
        ->where('status', 'Active')
        ->whereDate('date', Carbon::today()) // Assuming 'created_at' is the date column
        ->latest()
        ->get();

        return response()->json(["status" => "success", "jobs" => $jobs], 200);
    } else {
        return response()->json(["status" => "error", "message" => "Engineer not Found."], 404);
    }
}
 
  
  public function getAllEngineers(Request $request)
{
    $today = date("Y-m-d");

    $engineers = EngineerModel::with([
        'user:id,name,email',
        'jobTypeDetails:id,title,bgcolor',
        'availability' => function($q) use ($today) {
            $q->where('date_start', $today);
        }
    ])->get();

    // Format response
    $formatted = $engineers->map(function ($engineer) use ($today) {

        $todayAvailability = $engineer->availability->first();

        return [
            'name' => $engineer->user->name ?? null,
            'email' => $engineer->user->email ?? null,
            'postal_code' => $engineer->postal_codes,
            'job_types' => $engineer->jobTypeDetails->map(function ($jt) {
                return [
                    'title' => $jt->title,
                ];
            }),
            'today_availability' => $todayAvailability ? [
                'date_start' => $todayAvailability->date_start,
                'start_time' => $todayAvailability->start_time,
                'end_time'   => $todayAvailability->end_time,
                
            ] : null
        ];
    });

    return response()->json([
        'status' => true,
        'engineers' => $formatted
    ]);
}

  
  //engineer api with details
  
    public function getAllEngineersDetails(Request $request)
{
    $today = date("Y-m-d");

    $engineers = EngineerModel::with([
        'user:id,name,email',
        'jobTypeDetails:id,title',
        'availability' => function ($q) use ($today) {
            $q->whereDate('date_start', $today);
        }
    ])->get();

    $formatted = $engineers->map(function ($engineer) use ($today) {

        $todayAvailability = $engineer->availability->first();

        // Get jobs assigned TODAY to this engineer
        $todayJobs = Job::with([
                'agent_assigned:id,name',
                'created_by_user:id,name'
            ])
            ->whereDate('date', $today)
            ->where('engineer_id', $engineer->user_id) // IMPORTANT
            ->get();

        return [
            'name' => $engineer->user->name ?? null,
            'email' => $engineer->user->email ?? null,
            'postal_code' => $engineer->postal_codes,
            'lat' => $engineer->lat,
            'long' => $engineer->long,
            'home_postcode' => $engineer->home_postcode,
            'job_types' => $engineer->jobTypeDetails->map(function ($jt) {
                return [
                    'title' => $jt->title
                ];
            }),

            'today_availability' => $todayAvailability ? [
                'date_start' => $todayAvailability->date_start,
                'start_time' => $todayAvailability->start_time,
                'end_time'   => $todayAvailability->end_time,
            ] : null,

            'today_jobs' => $todayJobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'job_invoice_no' => $job->job_invoice_no,
                    'postcode' => $job->postcode,
                    'status' => $job->status,
                    'contract_status' => $job->contract_status,
                    'job_type' => $job->job_type,
                    'agent' => $job->agent_assigned->name ?? null,
                    'created_by' => $job->created_by_user->name ?? null,
                ];
            })
        ];
    });

    return response()->json([
        'status' => true,
        'engineers' => $formatted
    ]);
}

  
public function addAvailabilityByEmailOld(Request $request)
{
    // Validate input
    $request->validate([
        'email'       => 'required|email',
        'date_start'  => 'required|date',
        'availability' => 'required|in:yes,no'
    ]);

    // Find user by email
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Engineer with this email does not exist.'
        ], 404);
    }

    // Find engineer by user ID
    $engineer = EngineerModel::where('user_id', $user->id)->first();
    if (!$engineer) {
        return response()->json([
            'status' => false,
            'message' => 'No engineer profile found for this user.'
        ], 404);
    }

    // If availability = no → do not create availability
    if ($request->availability === "no") {
        return response()->json([
            'status' => true,
            'message' => 'Engineer marked unavailable.',
            'availability' => null
        ]);
    }

    // Check for duplicates for the SAME DATE
    $existing = EngineerAvailability::where('engineer_id', $engineer->id)
        ->whereDate('date_start', $request->date_start)
        ->first();

    if ($existing) {
        return response()->json([
            'status' => false,
            'message' => 'Availability for this date already exists.',
            'availability' => $existing
        ], 409); // Conflict
    }

    // Create new availability entry
    $availability = EngineerAvailability::create([
        'engineer_id' => $engineer->id,
        'date_start'  => $request->date_start,
        'start_time'  => '08:00:00',
        'end_time'    => '22:00:00',
        'title'       => 'Availability'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Availability added successfully.',
        'availability' => $availability
    ]);
}

  
  public function addAvailabilityByEmail(Request $request)
{
    // Validate input
    $request->validate([
        'email'       => 'required|email',
        'date_start'  => 'required|date',
        'availability' => 'required|in:yes,no'
    ]);

    // Find user by email
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Engineer with this email does not exist.'
        ], 404);
    }

    // Find engineer by user ID
    $engineer = EngineerModel::where('user_id', $user->id)->first();
    if (!$engineer) {
        return response()->json([
            'status' => false,
            'message' => 'No engineer profile found for this user.'
        ], 404);
    }

    // Check if an availability entry exists for this date
    $existing = EngineerAvailability::where('engineer_id', $engineer->id)
        ->whereDate('date_start', $request->date_start)
        ->first();

    /**
     * CASE 1: availability = "no"
     * → delete availability if exists
     */
    if ($request->availability === "no") {

        if ($existing) {
            $existing->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Engineer marked unavailable.',
            'availability' => null
        ]);
    }

    /**
     * CASE 2: availability = "yes"
     * → update OR create
     */

    if ($existing) {
        // Update to fixed time
        $existing->update([
            'start_time' => '08:00:00',
            'end_time'   => '22:00:00',
            'title'      => 'Availability'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Availability updated successfully.',
            'availability' => $existing
        ]);
    }

    // Create new availability
    $availability = EngineerAvailability::create([
        'engineer_id' => $engineer->id,
        'date_start'  => $request->date_start,
        'start_time'  => '08:00:00',
        'end_time'    => '22:00:00',
        'title'       => 'Availability'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Availability added successfully.',
        'availability' => $availability
    ]);
}

  
  


    public function jobsOld(Request $request, $id)
    {
        $engineer = User::find($id);
      
        if($engineer){
            $jobs = Job::with(['engineer_user:id,name', 'agent_assigned:id,name', 'handed_over:id,name', 'contract.sent_by_user:id,name', 'payment.sent_by_user:id,name'])
            ->where('engineer_id', $id)->where('status', 'Active')->latest()->get();
            return response()->json(["status" => "success", "jobs" => $jobs], 200);
        }else{
            return response()->json(["status" => "error", "message" => "Engineer not Found."], 404);
        }
    }
    public function AllJopTypes(Request $request)
    {
        $allJobs = JobType::all();
        return response()->json(["status" => "success", "allJobs" => $allJobs], 200);
    }
    public function engineerDetail(Request $request, $id)
    {
        $engineer = EngineerModel::where('id', $id)->with('jobTypes', 'availability')->first();
        if (!$engineer) {
            return response()->json(["status" => "error", "message" => "Engineer not found."], 404);
        }

        return response()->json(["status" => "success",  "engineer" => $engineer], 200);
    }

    public function signin(Request $request)
    {
        // Validate the request
        $rules=array(
            'email' => 'required|email',
            'password' => 'required'
        );
        $messages=array(
            'email.required' => 'Emai required.',
            'email.email' => 'Email is not in correct format.',
            'password.required' => 'Password Field is required.',
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["error"=>$messages], 400);
        }

        $user = User::where('email', $request->email)->where('user_type_id', 3)->first();

        if (!$user) {
            return response()->json(["status" => "error", "message" => "Email does not exist."], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(["status" => "error", "message" => "Wrong Password."], 200);
        }

        $engineer = EngineerModel::where('user_id', $user->id)->with('jobTypes', 'availability')->first();

        return response()->json([
            "status" => "success",
            "data" => [
                "user" => $user,
                "engineer" => $engineer,
            ]
        ], 200);
    }

    public function updateEngineer(Request $request, $id)
    {
        // Validate the request
        $rules = [
            'postcodes' => 'required|array',
            'jobtypes' => 'required|array|exists:jobtypes,id',
        ];
        $messages = [
            'postcodes.required' => 'The postcodes field is required.',
            'postcodes.array' => 'The postcodes field must be an array.',
            'jobtypes.required' => 'The jobtypes field is required.',
            'jobtypes.array' => 'The jobtypes field must be an array.',
            'jobtypes.exists' => 'The selected jobtype ID is invalid.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $engineer = EngineerModel::find($id);
        if (!$engineer) {
            return response()->json(["status" => "error", "message" => "Engineer not found."], 404);
        }

        $engineer->postal_codes = implode(",", $request->postcodes);
        if (isset($request->home_postcode)) {
            $engineer->home_postcode = $request->home_postcode;
        }
        $engineer->save();

        // Delete previously entered job types
        $engineer->jobTypes()->delete();

        // Add new job types
        foreach ($request->jobtypes as $type) {
            EngineerJobType::create([
                "engineer_id" => $engineer->id,
                "job_type_id" => $type,
            ]);
        }

        return response()->json(["status" => "success", "message" => "Engineer Updated successfully"], 200);
    }

    public function addAvailability(Request $request)
    {
        // Validate the request
        $rules = [
            'engineer_id' => 'required|exists:engineers,id',
            'date_start' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
        ];
        $messages = [
            'engineer_id.required' => 'The engineer ID is required.',
            'engineer_id.exists' => 'The selected engineer ID does not exist.',
            'date_start.required' => 'The start date is required.',
            'date_start.date' => 'The start date is not a valid date.',
            'start_time.required' => 'The start time is required.',
            'start_time.date_format' => 'The start time must be in the format HH:MM.',
            'end_time.required' => 'The end time is required.',
            'end_time.date_format' => 'The end time must be in the format HH:MM.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $request->all();
        $availability = new EngineerAvailability;
        $availability->fill($data);
        $availability->save();
        if ($request->monthAvailability > 0) {
            $allAvailabilities = array(); 
            array_push($allAvailabilities, $availability);
            for ($i=0; $i < 29; $i++) { 
                $data['date_start'] = Carbon::parse($data['date_start'])->addDay();
                $availability = new EngineerAvailability();
                $availability->fill($data);
                $availability->save();
                array_push($allAvailabilities, $availability);
            }
        }else{
            $allAvailabilities = $availability; 
        }

        return response()->json(["status" => "success", "data" => $allAvailabilities, "message" => "Engineer Availability Added Successfully."], 200);
    }

    public function updateAvailability(Request $request, $id)
    {
        // Validate the request
        $rules = [
            'engineer_id' => 'required|exists:engineers,id',
            'date_start' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
        ];
        $messages = [
            'engineer_id.required' => 'The engineer ID is required.',
            'engineer_id.exists' => 'The selected engineer ID does not exist.',
            'date_start.required' => 'The start date is required.',
            'date_start.date' => 'The start date is not a valid date.',
            'start_time.required' => 'The start time is required.',
            'start_time.date_format' => 'The start time must be in the format HH:MM.',
            'end_time.required' => 'The end time is required.',
            'end_time.date_format' => 'The end time must be in the format HH:MM.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $availability = EngineerAvailability::find($id);
        if (!$availability) {
            return response()->json(["status" => "error", "message" => "Availability not found."], 404);
        }
        $availability->fill($request->all());
        $availability->save();
        return response()->json(["status" => "success", "message" => "Engineer Availability Updated Successfully."], 200);
    }
    public function DeleteAvailability(Request $request, $id)
    {
        $availability = EngineerAvailability::find($id);
        if (!$availability) {
            return response()->json(["status" => "error", "message" => "Availability not found."], 404);
        }
        $availability->delete();
        return response()->json(["status" => "success",  "message" => "Availability deleted successfully."], 200);
    }

    
    public function AddLatLong(Request $request)
    {
        // Validate the request
        $rules = [
            'engineer_id' => 'required|exists:engineers,id',
            'lat' => 'required',
            'long' => 'required',
        ];
        $messages = [
            'engineer_id.required' => 'The engineer ID is required.',
            'engineer_id.exists' => 'The selected engineer ID does not exist.',
            'lat.required' => 'the engineer Latitude is required.',
            'long.required' => 'the engineer Longitude is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $request->all();
        $engineer = EngineerModel::find($data['engineer_id']);
        $engineer->fill($data);
        $engineer->save();

        return response()->json(["status" => "success", "data" => $engineer, "message" => "Engineer Latitude and Longitude Updated Successfully."], 200);
    }


    public function DeleteEngineer(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["status" => "error", "message" => "Engineer not found."], 404);
        }
        if ($user->engineerRow) {
            $engineer = $user->engineerRow;
            foreach($engineer->jobTypes as $type){
                $type->delete();
            }
    
            foreach($engineer->availability as $available){
                $available->delete();
            }
            $user->engineerRow->delete();
        }
        $user->delete();
        return response()->json(["status" => "success",  "message" => "Engineer deleted successfully."], 200);
    }
    public function signUp(Request $request)
    {
        // Validate the request
        $rules=array(
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'home_postcode' => 'required'
        );
        $messages=array(
            'name.required' => 'Name Field is required.',
            'email.required' => 'Emai Field is required.',
            'email.email' => 'Email is not in correct format.',
            'password.required' => 'Password Field is required.',
            'home_postcode.required' => 'Home Postcode Field is required.',
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["error"=>$messages], 400);
        }

        $data = $request->all();
        $password = $data["password"];
        $data["password"] = Hash::make($data["password"]);
        $user = new User();
        $user->fill($data);
        $user->user_type_id = UserType::ENGINEER;
        $user->save();
        $engineer = new EngineerModel;
        $engineer->fill($data);
        $engineer->user_id = $user->id;
        $engineer->save();

        return response()->json([
            "status" => "success",
            "message" => "Engineer SignUp Succussfully.",
            "data" => [
                "user" => $user,
                "engineer" => $engineer,
            ]
        ], 200);
    }

    public function EditProfile(Request $request, $id)
    {
        // Validate the request
        $rules=array(
            'name' => 'required',
        );
        $messages=array(
            'name.required' => 'Name Field is required.',
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["error"=>$messages], 400);
        }

        $data = $request->all();
        $user = User::find($id);
        $user->name = $data["name"];
        $user->save();
        $engineer = EngineerModel::where('user_id',$user->id)->first();
        $engineer->name = $data["name"];
        $engineer->save();

        return response()->json([
            "status" => "success",
            "message" => "Engineer Profile Updated Succussfully.",
            "data" => [
                "user" => $user,
                "engineer" => $engineer,
            ]
        ], 200);
    }
    public function ChangePassword(Request $request, $id)
    {
        // Validate the request
        $rules=array(
            'current_password' => 'required',
            'password' => 'required',
        );
        $messages=array(
            'current_password.required' => 'Current Password Field is required.',
            'password.required' => 'Password Field is required.',
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["error"=>$messages], 400);
        }

        $user = User::find($id);
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(["status" => "error", "message" => "Current Password is wrong."], 200);
        }
        $data = $request->all();
        $password = $data["password"];
        $data["password"] = Hash::make($data["password"]);
        $user->password = $data["password"];
        $user->save();

        return response()->json([
            "status" => "success",
            "message" => "Engineer Password Change Succussfully.",
            "data" => [
                "user" => $user,
            ]
        ], 200);
    }
    public function ForgetPassword(Request $request)
    {
        ob_start();
        // Validate the request
        $rules=array(
            'email' => 'required',
        );
        $messages=array(
            'email.required' => 'Email Field is required.',
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["error"=>$messages], 400);
        }
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(["status" => "error", "message" => "Engineer Not Found."], 400);
        }
        $hashEmail = md5($user->email);
        $reset_link = url('reset-password/' . $hashEmail);
        $html = view("mails.forgetPassword",compact('user','reset_link'))->render();
        // InfoBipModel::SendEmail($user->email,$html,"Forget Password");
        $this->messageBirdEmail($user->email, $html,"Forget Password");
        ob_end_clean();
        return response()->json([
            "status" => "success",
            "message" => "Forget Password Mail Send Successfully.",
        ], 200);
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
        'client_id' => env('DROPBOX_APP_KEY'),
        'client_secret' => env('DROPBOX_APP_SECRET'),
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
  
  public function InvestigationForm(Request $request, Dropbox $dropbox)
{
    
   $dropboxToken = DB::table('dropbox_tokens')->first(); 
   $accessToken = $dropboxToken->access_token;
   $refreshToken = $dropboxToken->refresh_token;
    
    $photoPaths = [];
    $videoPaths = [];

    // Check if an existing investigation record exists based on job_id
    $job = Job::where('id' , $request->job_id)->first();
    $job_type = $job->job_type;
    $investigation = Investigation::where('job_id', $request->job_id)->first();

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
        $existingPhotoPaths = $investigation->photo_path ? json_decode($investigation->photo_path) : [];
        $photoPaths = array_merge($existingPhotoPaths, $photoPaths);
    } else {
        // Retain existing photo paths if no new photos are uploaded
        $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path) : [];
    }

    
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

    // Update investigation data with request data
    $investigation->engineer_id = $request->engineer_id;
    $investigation->engineer_name = $request->engineer_name;
//     $investigation->start_time = $request->start_time;
//     $investigation->end_time = $request->end_time;
    $investigation->postcode = $request->postcode;
    $investigation->job_type = $job_type;
    $investigation->estimate_time = $request->estimate_time;
    $investigation->estimate_materials = $request->estimate_materials;
    $investigation->problem_reported = $request->problem_reported;
    $investigation->problem_location = $request->problem_location;
    $investigation->rectify_needed = $request->rectify_needed;
    $investigation->notes = $request->notes;
    $investigation->photo_path = json_encode($photoPaths);
    $investigation->video_path = json_encode($videoPaths);
    $investigation->accepted_at = $request->accepted_at;
    $investigation->job_id = $request->job_id;
    $investigation->created_at = now();

    // Save the investigation
    $investigation->save();
    
    // Generate PDF
    $pdf = PDF::loadView('jobs.investigation_pdf', compact('investigation'));

    $currentDate = now()->format('Y-m-d');
    $pdfFileName = 'Investigation_' . $investigation->engineer_name . '_' . $investigation->postcode . '_' . $currentDate . '.pdf';
    $pdfFilePath = public_path('investigationspdf/' . $pdfFileName);

    // Save the PDF locally
    $pdf->save($pdfFilePath);
    //
    $investigation->pdf = $pdfFileName;
    $investigation->save();
    //
    $pdfUrl = url('public/investigationspdf/' . $pdfFileName);
    $dropboxFilePath = '/investigations/' . $pdfFileName;

    try {
            $dropboxApp = new DropboxApp(
            env('DROPBOX_APP_KEY'),
            env('DROPBOX_APP_SECRET'),
            $accessToken
        );

        $dropbox = new Dropbox($dropboxApp);
        // Upload the PDF to Dropbox
        $dropboxFile = $dropbox->upload($pdfFilePath, $dropboxFilePath, ['autorename' => true]);
        $dropboxUrl = $dropbox->getTemporaryLink($dropboxFile->getPathDisplay());

        return response()->json([
            'message' => 'The form has been successfully submitted',
            'file_url' => $pdfUrl,
            'dropbox_url' => $dropboxUrl,
            'investigation' => $investigation,
        ]);
    } catch (DropboxClientException $e) {
        return response()->json([
            'message' => 'The form has been successfully submitted',
            'error' => $e->getMessage(),
        ], 500);
    }
}

        
 public function getInvestigation($id)
    {
        try {
            // Fetch the investigation data based on job_id
            $investigation = Investigation::where('job_id', $id)->get();

            // If no data found, return a 404 response
            if ($investigation->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No investigation data found for this job ID.'
                ], 404);
            }

            // Return the data as JSON
            return response()->json([
                'status' => 'success',
                'data' => $investigation
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
  
  public function getCompletion($id)
  {
    
     try {
            // Fetch the investigation data based on job_id
            $completion = CompletionForm::where('job_id', $id)->get();

            // If no data found, return a 404 response
            if ($completion->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Completion data found for this job ID.'
                ], 404);
            }

            // Return the data as JSON
            return response()->json([
                'status' => 'success',
                'data' => $completion
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
  }
  
  public function StartTime(Request $request)
    {
        // Retrieve data directly from the request
        $jobId = $request->input('job_id');
        $startTime = $request->input('start_time'); // Default to current time if not provided
        $endTime = $request->input('end_time'); // Optional

        try {
            // Check if a record already exists for the given job_id
            $investigation = Investigation::where('job_id', $jobId)->first();

            if ($investigation) {
                // Update the existing record with start_time and/or end_time
                $investigation->start_time = $startTime ?? $investigation->start_time;
                $investigation->end_time = $endTime ?? $investigation->end_time;
                $investigation->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'End time updated, please select the submit button to send the form.',
                    'data' => $investigation
                ]);
            } else {
                // Create a new record if it doesn't exist
                $newInvestigation = Investigation::create([
                    'job_id' => $jobId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Investigation has started.',
                    'data' => $newInvestigation
                ]);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
  
  
  public function deleteImage(Request $request)
{
    try {
        // Validate the request to ensure 'id' and 'image' are provided
        $request->validate([
            'id' => 'required|integer',
            'image' => 'required|string', // image name as a string
        ]);

        // Get 'id' and 'image' from the form-data
        $id = $request->input('id');
        $imageName = $request->input('image');


        $investigation = Investigation::findOrFail($id);


        $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];


        $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


        $investigation->photo_path = json_encode(array_values($updatedPaths));
        $investigation->save();


        $imagePath = public_path('investigation_img/' . $imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }


        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully',
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the image',
            'error' => $e->getMessage(),
        ], 500);
    }
}
  
   public function completiondeleteImage(Request $request)
{
    try {
        // Validate the request to ensure 'id' and 'image' are provided
        $request->validate([
            'id' => 'required|integer',
            'image' => 'required|string', // image name as a string
        ]);

        // Get 'id' and 'image' from the form-data
        $id = $request->input('id');
        $imageName = $request->input('image');


        $investigation = CompletionForm::findOrFail($id);


        $photoPaths = $investigation->photo_path ? json_decode($investigation->photo_path, true) : [];


        $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


        $investigation->photo_path = json_encode(array_values($updatedPaths));
        $investigation->save();


        $imagePath = public_path('completion_img/' . $imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }


        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully',
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the image',
            'error' => $e->getMessage(),
        ], 500);
    }
}
  
    public function deleteVideo(Request $request)
{
    //  dd($request->all());
    try {
        // Validate the request to ensure 'id' and 'image' are provided
        $request->validate([
            'id' => 'required|integer',
            'video' => 'required|string', // image name as a string
        ]);

        // Get 'id' and 'image' from the form-data
        $id = $request->input('id');
        $imageName = $request->input('video');


        $investigation = Investigation::findOrFail($id);


        $photoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];


        $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


        $investigation->video_path = json_encode(array_values($updatedPaths));
        $investigation->save();

        
        $imagePath = public_path('investigation_video/' . $imageName);
        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }


        return response()->json([
            'success' => true,
            'message' => 'video deleted successfully',
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the image',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    
    public function completiondeleteVideo(Request $request)
{
    //  dd($request->all());
    try {
        // Validate the request to ensure 'id' and 'image' are provided
        $request->validate([
            'id' => 'required|integer',
            'video' => 'required|string', // image name as a string
        ]);

        // Get 'id' and 'image' from the form-data
        $id = $request->input('id');
        $imageName = $request->input('video');


        $investigation = CompletionForm::findOrFail($id);


        $photoPaths = $investigation->video_path ? json_decode($investigation->video_path, true) : [];


        $updatedPaths = array_filter($photoPaths, fn($path) => $path !== $imageName);


        $investigation->video_path = json_encode(array_values($updatedPaths));
        $investigation->save();

        
        $imagePath = public_path('completion_video/' . $imageName);
        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }


        return response()->json([
            'success' => true,
            'message' => 'video deleted successfully',
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the image',
            'error' => $e->getMessage(),
        ], 500);
    }
}

  
  
  
public function deleteVideoPath(Request $request, $id)
{
    try {
        // Find the investigation by ID
        $investigation = Investigation::findOrFail($id);

        // Get the video path from the database
        $videoPath = $investigation->video_path;

        if (!$videoPath) {
            return response()->json([
                'success' => false,
                'message' => 'No video path found for this investigation.',
            ], 404);
        }

        // Delete the physical file
        $filePath = public_path('investigation_videos/' . $videoPath);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove the video path from the database
        $investigation->video_path = null;
        $investigation->save();

        return response()->json([
            'success' => true,
            'message' => 'Video path deleted successfully.',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the video path.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
  
public function completionStart(Request $request)
{
    try {
        $completion = CompletionForm::where('job_id', $request->job_id)->first();

        if ($completion) {
            // If opening_time is already set, do nothing
            if ($completion->opening_time) {
                return response()->json([
                    'status' => true,
                    'message' => 'Completion already started.',
                    'data' => $completion
                ], 200);
            }

            // If opening_time is null, update it
            $completion->opening_time = Carbon::now();
            $completion->save();

            return response()->json([
                'status' => true,
                'message' => 'Opening time updated successfully.',
                'data' => $completion
            ], 200);

        } else {
            // Create new record
            $completion = new CompletionForm();
            $completion->job_id = $request->job_id;
            $completion->opening_time = Carbon::now();
            $completion->save();

            return response()->json([
                'status' => true,
                'message' => 'Completion started successfully.',
                'data' => $completion
            ], 200);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}
  
  public function completionTime(Request $request){
    try {
        $completion = CompletionForm::where('job_id', $request->job_id)->first();

        if (!$completion) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found.'
            ], 404);
        }

        $completion->completion_time = Carbon::now();
        $completion->save();

        return response()->json([
            'status' => true,
            'message' => 'Completion time saved successfully.',
            'data' => $completion
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
  }
  
  public function submitCompletionForm(Request $request)
{
   $dropboxToken = DB::table('dropbox_tokens')->first(); 
   $accessToken = $dropboxToken->access_token;
   $refreshToken = $dropboxToken->refresh_token;
    
    try {
        $completion = CompletionForm::where('job_id', $request->job_id)->first();

        if (!$completion) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found.'
            ], 404);
        }

        // Decode existing image/video paths if available
        $photoPaths = $completion->photo_path ? json_decode($completion->photo_path, true) : [];
        $videoPaths = $completion->video_path ? json_decode($completion->video_path, true) : [];

        // Handle new image uploads
        if ($request->hasFile('photo_path')) {
            foreach ($request->file('photo_path') as $file) {
                if (strstr($file->getMimeType(), "image/")) {
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('completion_img'), $filename);
                    $photoPaths[] = $filename;
                }
            }
        }

        // Handle new video uploads
        if ($request->hasFile('video_path')) {
            foreach ($request->file('video_path') as $file) {
                if (strstr($file->getMimeType(), "video/")) {
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('completion_video'), $filename);
                    $videoPaths[] = $filename;
                }
            }
        }

        // Assign other fields
        $completion->engineer_name = $request->engineer_name;
        $completion->postcode  = $request->postcode;
        $completion->job_type  = $request->job_type;
        $completion->description  = $request->description;
        $completion->further_work  = $request->further_work;
        $completion->accepted_technician = $request->accepted_technician;
        $completion->customer_present = $request->customer_present;
        $completion->photo_path = json_encode($photoPaths);
        $completion->video_path  = json_encode($videoPaths);
        $completion->submitted_time = Carbon::now();

        $completion->save();

        // Generate PDF using Blade view
        $pdf = PDF::loadView('jobs.completion_pdf', compact('completion'));

        $currentDate = now()->format('Y-m-d');
        $pdfFileName = 'Completion_' . $completion->engineer_name . '_' . $completion->postcode . '_' . $currentDate . '.pdf';
        $pdfFilePath = public_path('completionpdf/' . $pdfFileName);

        // Save the PDF to public path
        $pdf->save($pdfFilePath);

        // Update completion with PDF file name
        $completion->pdf = $pdfFileName;
        $completion->save();
      
         $dropboxFilePath = '/CompletionForms/' . $pdfFileName;

    try {
            $dropboxApp = new DropboxApp(
            env('DROPBOX_APP_KEY'),
            env('DROPBOX_APP_SECRET'),
            $accessToken
        );

        $dropbox = new Dropbox($dropboxApp);
        // Upload the PDF to Dropbox
        $dropboxFile = $dropbox->upload($pdfFilePath, $dropboxFilePath, ['autorename' => true]);
        $dropboxUrl = $dropbox->getTemporaryLink($dropboxFile->getPathDisplay());
      

         return response()->json([
            'status' => true,
            'message' => 'Completion form submitted and PDF generated successfully.',
            'data' => $completion,
            'pdf_url' => url('public/completionpdf/' . $pdfFileName)
        ], 200);
    } catch (DropboxClientException $e) {
        return response()->json([
            'message' => 'The form has been successfully submitted',
            'error' => $e->getMessage(),
        ], 500);
    }

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}

  
  public function submitCompletionFormOld(Request $request)
{
    try {
        $completion = CompletionForm::where('job_id', $request->job_id)->first();

        if (!$completion) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found.'
            ], 404);
        }

        $photoPaths = [];
        $videoPaths = [];

        // Handle image uploads
        if ($request->hasFile('photo_path')) {
            foreach ($request->file('photo_path') as $file) {
                if (strstr($file->getMimeType(), "image/")) {
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('completion_img'), $filename);
                    $photoPaths[] = $filename;
                }
            }
        }

        // Handle video uploads
        if ($request->hasFile('video_path')) {
            foreach ($request->file('video_path') as $file) {
                if (strstr($file->getMimeType(), "video/")) {
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('completion_video'), $filename);
                    $videoPaths[] = $filename;
                }
            }
        }

        // Assign other fields
        $completion->engineer_name = $request->engineer_name;
        $completion->postcode  = $request->postcode;
        $completion->job_type  = $request->job_type;
        $completion->description  = $request->description;
        $completion->further_work  = $request->further_work;
        $completion->accepted_technician = $request->accepted_technician;
        $completion->customer_present = $request->customer_present;
        $completion->photo_path = json_encode($photoPaths);
        $completion->video_path  = json_encode($videoPaths);
        $completion->submitted_time = Carbon::now();

        $completion->save();
      
              // Generate PDF using Blade view
        $pdf = PDF::loadView('jobs.completion_pdf', compact('completion'));

        $currentDate = now()->format('Y-m-d');
        $pdfFileName = 'Completion_' . $completion->engineer_name . '_' . $completion->postcode . '_' . $currentDate . '.pdf';
        $pdfFilePath = public_path('completionpdf/' . $pdfFileName);

        // Save the PDF to public path
        $pdf->save($pdfFilePath);

        // Update completion with PDF file name
        $completion->pdf = $pdfFileName;
        $completion->save();

          return response()->json([
            'status' => true,
            'message' => 'Completion form submitted and PDF generated successfully.',
            'data' => $completion,
            'pdf_url' => url('public/completionpdf/' . $pdfFileName)
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}
  


}
